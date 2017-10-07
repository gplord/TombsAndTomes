<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "dungeon");
$session_id = $conn->real_escape_string($_SESSION['session_id']);
$player_id = $conn->real_escape_string($_SESSION['player_id']);
// $player_order = $conn->real_escape_string($_POST['player_order']);

$player_order = null;

// Get the player's player order from their player ID
// TODO: We could keep this in SESSION, but will need to tie that to login vs game selection later
$player_order_query = sprintf("SELECT
    session_player.player_order
    FROM session_player
    WHERE session_player.player_id = '%s'",
    $player_id
);
$player_order_result = $conn->query($player_order_query);
while ($player_order_row = $player_order_result->fetch_row()) {
    $player_order = $player_order_row[0];                           // The result: this session's player count
}

// $player_order = 1; // DEBUG

$ready_query = sprintf("UPDATE 
    session_player 
    SET session_player.player_ready = '1'
    WHERE session_player.player_id = '%s' 
    AND session_player.session_id = '%s';",
        $player_id,
        $session_id
);
$ready_result = $conn->query($ready_query);
if ($conn->affected_rows > 0) {
    //echo "1";
} else {
    echo "Error updating: " . $conn->error;
}

// Count how many other players are still unready -- the next turn doesn't start until everyone is checked in
// NOTE: Single-turn check-ins, like taking one's own turn, do not unready other players
// Unreadying will happen upon the start of their turn, and ending their turn will ready them.  
// Thus, only one player should be unready at a time, and they will be the active player
// Special events will unready everyone, and these will trigger checks for other actions (end of combat, end of task, etc)
// (Those will be implemented down the line, and this will be updated accordingly)
$count_players_unready_query = sprintf("SELECT 
    session_player.player_ready 
    FROM session_player 
    WHERE session_player.player_ready = '0' 
    AND session_player.session_id = '%s'",
    $session_id
);
$count_players_unready_result = $conn->query($count_players_unready_query);
$unready_count = $count_players_unready_result->num_rows;
if ($unready_count > 0) {
    // Do nothing for now
} else {
    // No one is unready (everyone is ready), so we can ready the session
    // NOTE: This doesn't technically do anything right now, but will be important for task gating down the line
    $session_ready_query = sprintf("UPDATE 
    session 
    SET session.session_ready = '1' 
    WHERE session.session_id = '%s'",
        $session_id
    );
    $session_ready_result = $conn->query($session_ready_query);

    // Check if updating was successful -- otherwise we break the flow, which might require a kind of "repair" button for now
    // TODO: Mock up repair query sequence, to placehold for better testing -- add to an "admin" menu
    if ($conn->affected_rows > 0) {
        echo "1";
    } else {
        echo "Error updating: " . $conn->error;
    }

    // TODO: This should ideally be in the above "affected rows" check, but that can fail if the session is already "ready" for some reason
    NextTurn($conn, $session_id, $player_order);
}

// Deactivates all players, sets the next player to active, unreadies them
function NextTurn($conn, $session_id, $player_order) {

    // Placeholder variables, to be set further down
    $next_player_order = null;
    $player_count = null;

    // Retrieve the player count, to compare against the player checking in's player order
    // TODO: We could easily keep this in $_SESSION, but login vs game selection logic will have to be implemented first
    $player_count_query = sprintf("SELECT 
        session.session_player_count
        FROM session
        WHERE session.session_id = '%s'",
        $session_id
    );
    $player_count_result = $conn->query($player_count_query);
    while ($player_count_row = $player_count_result->fetch_row()) {
        $player_count = $player_count_row[0];                           // The result: this session's player count
    }
    $next_player_order = $player_order + 1;                             // Next player's player_order: test increment the current player's player order
    if ($next_player_order > $player_count) {                           
        $next_player_order = 1;                                         // Clamp this to 1-[Player Count]
        VillainTurn($conn, $session_id);
    }
    //echo "<p>Next player: " . $next_player_order . "</p>\n";          // Debug
    
    // Deactivate all players (set player_current to 0)
    // NOTE: We could just deactivate the one that is reporting in, but this is just as easy, and probably safer in terms of sync
    $deactivate_all_query = sprintf("UPDATE 
        session_player 
        SET session_player.player_current = '0' 
        WHERE session_player.session_id = '%s'",
        $session_id
    );  
    $deactivate_all_result = $conn->query($deactivate_all_query);
    // echo "<p>". $deactivate_all_query . "</p>\n";                    // Debug

    // Using the next player's player_order from above, set that player to the current player
    $activate_next_player_query = sprintf("UPDATE 
        session_player 
        SET session_player.player_current = '1' 
        WHERE session_player.session_id = '%s'
        AND session_player.player_order = '%s'",
        $session_id,
        $next_player_order
    );  
    $activate_next_player_result = $conn->query($activate_next_player_query);
    // echo "<p>". $activate_next_player_query . "</p>\n";              // Debug

    // Recover one of the new player's energy points (if they're at less than max) at the beginning of their turn
    $recover_energy_query = sprintf("UPDATE 
        hero_instance 
        JOIN player_hero_instance
            ON hero_instance.hinst_id = player_hero_instance.hinst_id
        JOIN session_player
            ON player_hero_instance.player_id = session_player.player_id
        SET hero_instance.hinst_energy = hero_instance.hinst_energy + 1
        WHERE session_player.session_id = '%s'
        AND session_player.player_order = '%d'
        AND hero_instance.hinst_energy < hero_instance.hinst_energy_max",
        $session_id,
        $next_player_order
    );  
    $recover_energy_result = $conn->query($recover_energy_query);



    // Update the Session Update value, to signal to all other clients that this change has happened
    SessionUpdate($conn, $session_id);

    // Set the next player to unready, halting the player turn flow until they take their action
    $unready_next_player_query = sprintf("UPDATE 
        session_player 
        SET session_player.player_ready = '0' 
        WHERE session_player.session_id = '%s'
        AND session_player.player_order = '%s'",
        $session_id,
        $next_player_order
    );  
    $unready_next_player_result = $conn->query($unready_next_player_query);

    // Set the session to unready, halting the session turn flow until the player readies
    // NOTE: This doesn't technically do anything right now, but will be important for task gating down the line
    $unready_session_query = sprintf("UPDATE 
        session 
        SET session.session_ready = '0' 
        WHERE session.session_id = '%s'",
        $session_id
    );  
    $unready_session_result = $conn->query($unready_session_query);

}

function SessionUpdate($conn, $session_id) {
    $session_update_query = sprintf("UPDATE 
        session 
        SET session.session_update = session.session_update + 1 
        WHERE session.session_id = '%s'",
        $session_id
    );
    $session_update_result = $conn->query($session_update_query);
    // echo "<p>". $session_update_query . "</p>\n";                    // Debug
}

function VillainTurn($conn, $session_id) {

    $vinst_id = null;           // TODO: Ideally we'd get this somewhere else, instead of needing an extra query
    $villain_name = null;        // This too
    $hinst_id = null;
    $hero_name = null;
    $villain_ability = null;

    $get_vinst_id_query = sprintf("SELECT 
        villain_instance.vinst_id, villain.villain_name
        FROM villain_instance
        LEFT JOIN session_villain ON villain_instance.vinst_id = session_villain.vinst_id
        LEFT JOIN villain ON villain_instance.villain_id = villain.villain_id
        WHERE villain_instance.vinst_active = 1
        AND session_villain.session_id = '%s'",
        $session_id
    );
    $get_vinst_id_result = $conn->query($get_vinst_id_query);
    while ($vinst_row = $get_vinst_id_result->fetch_assoc()) {
        $vinst_id = $vinst_row["vinst_id"];                                     // Our villain instance id
        $villain_name = $vinst_row["villain_name"];                             // Our villain name
    }

    // This will choose one target from the players' party at random, excluding injured or dead heroes
    $choose_random_target_query = sprintf("SELECT 
        hero_instance.hinst_id, hero.hero_name FROM hero_instance 
        LEFT JOIN hero ON hero_instance.hero_id = hero.hero_id
        LEFT JOIN player_hero_instance ON hero_instance.hinst_id = player_hero_instance.hinst_id 
        LEFT JOIN player ON player_hero_instance.player_id = player.player_id 
        LEFT JOIN session_player ON player.player_id = session_player.player_id 
        WHERE session_player.session_id = '%s' 
        AND hero_instance.hinst_injured = 0 
        AND hero_instance.hinst_dead = 0 
        ORDER BY rand() 
        LIMIT 1",
        $session_id
    );
    $choose_random_target_result = $conn->query($choose_random_target_query);
    while ($target_row = $choose_random_target_result->fetch_assoc()) {
        $hinst_id = $target_row["hinst_id"];                                    // Our random target. Poor them.
        $hero_name = $target_row["hero_name"];                                  
    }

    $choose_ability_query = sprintf("SELECT 
            ability.*, villain_ability.dialogue
        FROM ability
        LEFT JOIN villain_ability ON ability.ability_id = villain_ability.ability_id 
        LEFT JOIN villain ON villain_ability.villain_id = villain.villain_id 
        LEFT JOIN villain_instance ON villain.villain_id = villain_instance.villain_id 
        LEFT JOIN villain_instance_ability ON villain_instance_ability.ability_id = ability.ability_id
        WHERE villain_instance.vinst_id = '%s'
        AND ability.ability_cost < villain_instance.vinst_energy
        AND villain_instance_ability.cooldown_left = 0
        ORDER BY ability.ability_priority 
        DESC
        LIMIT 1",
        $vinst_id
    );
    $choose_ability_result = $conn->query($choose_ability_query);
    while ($ability_result = $choose_ability_result->fetch_assoc()) {
        $villain_ability = $ability_result;
    }

    $process_cooldown_query = sprintf("UPDATE
        villain_instance_ability
        SET villain_instance_ability.cooldown_left = '%d'
        WHERE villain_instance_ability.vinst_id = '%s'
        AND villain_instance_ability.ability_id = '%d'",
        $villain_ability["ability_cooldown"],
        $vinst_id,
        $villain_ability["ability_id"]
    );
    $process_cooldown_result = $conn->query($process_cooldown_query);

    $damage_player_query = sprintf("UPDATE
        hero_instance
        SET hero_instance.hinst_hp = hero_instance.hinst_hp - %d
        WHERE hero_instance.hinst_id = '%s'",
        $villain_ability["ability_damage"],
        $hinst_id
    );
    $damage_player_result = $conn->query($damage_player_query);

    // Lower any existing cooldowns we might have, now that the turn is complete
    $reduce_cooldowns_query = sprintf("UPDATE 
        villain_instance_ability
        SET cooldown_left = cooldown_left - 1 
        WHERE villain_instance_ability.vinst_id = '%s' 
        AND cooldown_left > 0",
        $vinst_id
    );
    $reduce_cooldowns_result = $conn->query($reduce_cooldowns_query);

    $log_string = sprintf('<li class="logv" data-t="v" data-id="%s">%s says, "%s"<br><hr>%s attacks %s with %s, inflicting %d points of damage.</li>',
        $vinst_id, $villain_name, $villain_ability["dialogue"], $villain_name, $hero_name, $villain_ability["ability_name"], $villain_ability["ability_damage"]
    );
    $update_log_query = sprintf("UPDATE
        session
        SET session.session_log = concat(session.session_log, '%s')
        WHERE session.session_id = '%s'",
        $log_string,
        $session_id
    );
    $update_log_result = $conn->query($update_log_query);

}


// echo json_encode($readys, JSON_PRETTY_PRINT);

?>