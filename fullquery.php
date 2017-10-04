<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

$session_id = 's12345678';

$conn = new mysqli("localhost", "root", "root", "dungeon");

function QueryHeroInstances($conn, $session_id) {
    $hero_instance_query = sprintf("SELECT
        hero_instance.*, hero.hero_name, player.player_name, player.player_email, player.player_id,
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
        WHERE session.session_id = '%s'",
        $session_id);
    $hero_instance_result = $conn->query($hero_instance_query);
    $hero_instances = array();
    $hero_instances = $hero_instance_result->fetch_all(MYSQLI_ASSOC);
    return $hero_instances;
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

// echo $hero_instance_query;
// echo "\n\n";
// echo $ability_query;
// echo "\n\n";

$hero_instances = QueryHeroInstances($conn, $session_id);

foreach ($hero_instances as &$hero_instance) {
    
    $abilities = QueryHeroAbilities($conn, $hero_instance['hero_id']);

    for ($i = 0; $i < count($abilities); $i++) {
        $hero_instance['abilities'][$i] = $abilities[$i];
    }

}

echo json_encode($hero_instances, JSON_PRETTY_PRINT);
// echo json_encode(array("response" => "0")); // No updates required -- JSON parser will test for this in updating application

?>