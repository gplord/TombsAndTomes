<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "dungeon");

$session_id = $_POST['session_id'];


function QueryVillainInstances($conn, $session_id) {
    $villain_instance_query = sprintf("SELECT
        villain_instance.*, villain.villain_name, villain.villain_desc
        FROM villain_instance
        LEFT JOIN villain
            ON villain_instance.villain_id = villain.villain_id
        LEFT JOIN session_villain
            ON villain_instance.vinst_id = session_villain.vinst_id
        LEFT JOIN session
            ON session_villain.session_id = session.session_id
        WHERE session.session_id = '%s'
        AND villain_instance.vinst_active = 1",
        $session_id);
    $villain_instance_result = $conn->query($villain_instance_query);
    $villain_instances = array();
    $villain_instances = $villain_instance_result->fetch_all(MYSQLI_ASSOC);
    return $villain_instances;
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

// echo $villain_instance_query;
// echo "\n\n";
// echo $ability_query;
// echo "\n\n";

$villain_instances = QueryVillainInstances($conn, $session_id);

foreach ($villain_instances as &$villain_instance) {
    
    $abilities = QueryVillainAbilities($conn, $villain_instance['villain_id']);

    for ($i = 0; $i < count($abilities); $i++) {
        $villain_instance['abilities'][$i] = $abilities[$i];
    }

}

echo json_encode($villain_instances, JSON_PRETTY_PRINT);
// echo json_encode(array("response" => "0")); // No updates required -- JSON parser will test for this in updating application

?>