<?php
session_start();

// header('Content-Type: application/json; charset=utf-8');  // DEBUG: Enable this for readable printing to screen
// $session_id = $_SESSION['session_id'];
// $player_id = $_SESSION['player_id'];

$conn = new mysqli("localhost", "root", "root", "dungeon");
$session_id = $conn->real_escape_string($_SESSION['session_id']);


function QueryActiveVillainInstance($conn, $session_id) {
    $villain_instance_query = sprintf("SELECT
        villain_instance.*, villain.villain_name, villain.villain_desc, villain.villain_image
        FROM villain_instance
        LEFT JOIN villain
            ON villain_instance.villain_id = villain.villain_id
        LEFT JOIN session_villain
            ON villain_instance.vinst_id = session_villain.vinst_id
        LEFT JOIN session
            ON session_villain.session_id = session.session_id
        WHERE session.session_id = '%s'
        AND villain_instance.vinst_active = 1",
        $session_id
    );
    $villain_instance_result = $conn->query($villain_instance_query);
    $villain_instance = array();
    $villain_instance = $villain_instance_result->fetch_all(MYSQLI_ASSOC);
    return $villain_instance;
}

function QueryVillainAbilities($conn, $villain_id) {
    $ability_query = sprintf("SELECT
        ability.*, effect.*, type_ability.*
        FROM villain_ability
        LEFT JOIN ability
            ON ability.ability_id = villain_ability.ability_id
        LEFT JOIN type_ability
            ON type_ability.ability_type_id = ability.ability_type_id
        LEFT JOIN effect
            ON ability.effect_id = effect.effect_id
        LEFT JOIN villain
            ON villain_ability.villain_id = villain.villain_id
        WHERE villain.villain_id = '%d'",
        $villain_id);
    $ability_result = $conn->query($ability_query);
    $abilities = array();
    $abilities = $ability_result->fetch_all(MYSQLI_ASSOC);
    return $abilities;
}

function QueryVillainInstanceEffects($conn, $vinst_id) {
    $effect_query = sprintf("SELECT
        effect.*
        FROM villain_instance_effect
        LEFT JOIN effect
            ON villain_instance_effect.effect_id = effect.effect_id
        WHERE villain_instance_effect.vinst_id = '%s'",
        $vinst_id);
    $effect_result = $conn->query($effect_query);
    $effects = array();
    $effects = $effect_result->fetch_all(MYSQLI_ASSOC);
    return $effects;
}

$villain_instances = QueryActiveVillainInstance($conn, $session_id);

foreach ($villain_instances as &$villain_instance) {

    $abilities = QueryVillainAbilities($conn, $villain_instance['villain_id']);
    for ($i = 0; $i < count($abilities); $i++) {
        $villain_instance['abilities'][$i] = $abilities[$i];
    }

    $effects = QueryVillainInstanceEffects($conn, $villain_instance['vinst_id']);
    for ($i = 0; $i < count($effects); $i++) {
        $villain_instance['effects'][$i] = $effects[$i];
    }

}

echo json_encode($villain_instances, JSON_PRETTY_PRINT);

?>