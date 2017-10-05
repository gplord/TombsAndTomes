<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "dungeon");

$ability_type_id = $conn->real_escape_string($_POST['ability_type_id']);
$ability_damage = $conn->real_escape_string($_POST['ability_damage']);
$vinst_id = $conn->real_escape_string($_POST['vinst_id']);

// TODO: Add damage strengths/weaknesses as part of this processing
$process_damage_query = sprintf("UPDATE
    villain_instance
    SET villain_instance.vinst_hp = villain_instance.vinst_hp - %d
    WHERE villain_instance.vinst_id = '%s'",
    $ability_damage,
    $vinst_id
);
$process_damage_result = $conn->query($process_damage_query);
if ($conn->affected_rows > 0) {
    echo "1";
} else {
    echo "Error: " . $conn->error;
}

?>