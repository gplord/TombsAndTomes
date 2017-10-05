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
        NextTurn($conn, $session_id, $player_order);
    } else {
        echo "Error updating: " . $conn->error;
    }
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

// echo json_encode($readys, JSON_PRETTY_PRINT);

?>