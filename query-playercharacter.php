<?php
session_start();

// $session_id = $_SESSION['session_id'];
// $player_id = $_SESSION['player_id'];

$conn = new mysqli("localhost", "root", "root", "dungeon");
$session_id = $conn->real_escape_string($_POST['session_id']);
$player_id = $conn->real_escape_string($_POST['player_id']);

function QueryPlayerHeroInstance($conn, $session_id, $player_id) {
    $hero_instance_query = sprintf("SELECT
        hero_instance.*, hero.hero_name, hero.hero_desc, hero.hero_image, player.player_name, player.player_email, player.player_id,
        session_player.player_order, session_player.player_current, session_player.player_ready
        FROM hero_instance
        LEFT JOIN player_hero_instance
            ON hero_instance.hinst_id = player_hero_instance.hinst_id
        LEFT JOIN hero
            ON hero_instance.hero_id = hero.hero_id
        LEFT JOIN player
            ON player.player_id = player_hero_instance.player_id
        LEFT JOIN session_player
            ON player.player_id = session_player.player_id
        LEFT JOIN session
            ON session_player.session_id = session.session_id
        WHERE session.session_id = '%s'
        AND player.player_id = '%s'",
        $session_id,
        $player_id
    );
    $hero_instance_result = $conn->query($hero_instance_query);
    $hero_instance = array();
    $hero_instance = $hero_instance_result->fetch_all(MYSQLI_ASSOC);
    return $hero_instance;
}

function QueryHeroAbilities($conn, $hero_id) {
    $ability_query = sprintf("SELECT
        ability.*, effect.*, type_ability.*
        FROM hero_ability
        LEFT JOIN ability
            ON ability.ability_id = hero_ability.ability_id
        LEFT JOIN type_ability
            ON type_ability.ability_type_id = ability.ability_type_id
        LEFT JOIN effect
            ON ability.effect_id = effect.effect_id
        LEFT JOIN hero
            ON hero_ability.hero_id = hero.hero_id
        WHERE hero.hero_id = '%d'",
        $hero_id);
    $ability_result = $conn->query($ability_query);
    $abilities = array();
    $abilities = $ability_result->fetch_all(MYSQLI_ASSOC);
    return $abilities;
}

function QueryHeroInstanceEffects($conn, $hinst_id) {
    $effect_query = sprintf("SELECT
        effect.*
        FROM hero_instance_effect
        LEFT JOIN effect
            ON hero_instance_effect.effect_id = effect.effect_id
        WHERE hero_instance_effect.hinst_id = '%d'",
        $hinst_id);
    $effect_result = $conn->query($effect_query);
    $effects = array();
    $effects = $effect_result->fetch_all(MYSQLI_ASSOC);
    return $effects;
}

// echo $hero_instance_query;
// echo "\n\n";
// echo $ability_query;
// echo "\n\n";

$hero_instances = QueryPlayerHeroInstance($conn, $session_id, $player_id);

foreach ($hero_instances as &$hero_instance) {
    
    $abilities = QueryHeroAbilities($conn, $hero_instance['hero_id']);
    for ($i = 0; $i < count($abilities); $i++) {
        $hero_instance['abilities'][$i] = $abilities[$i];
    }

    $effects = QueryHeroInstanceEffects($conn, $hero_instance['hinst_id']);
    for ($i = 0; $i < count($effects); $i++) {
        $hero_instance['effects'][$i] = $effects[$i];
    }

}

echo json_encode($hero_instances, JSON_PRETTY_PRINT);
// echo json_encode(array("response" => "0")); // No updates required -- JSON parser will test for this in updating application

?>