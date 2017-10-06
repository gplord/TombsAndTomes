<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "dungeon");

$ability_type_id = $conn->real_escape_string($_POST['ability_type_id']);
$ability_damage = $conn->real_escape_string($_POST['ability_damage']);
$vinst_id = $conn->real_escape_string($_POST['vinst_id']);
$hero_name = $conn->real_escape_string($_POST['hero_name']);
$villain_name = $conn->real_escape_string($_POST['villain_name']);
$ability_name = $conn->real_escape_string($_POST['ability_name']);
$hinst_id = $conn->real_escape_string($_POST['hinst_id']);

$session_id = $conn->real_escape_string($_SESSION['session_id']);

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

$log_string = sprintf('<li data-t="h" data-id="%s">%s attacks %s with %s, inflicting %d points of damage.</li>',
    $hinst_id, $hero_name, $villain_name, $ability_name, $ability_damage
);
$update_log_query = sprintf("UPDATE
    session
    SET session.session_log = concat(session.session_log, '%s')
    WHERE session.session_id = '%s'",
    $log_string,
    $session_id
);
$update_log_result = $conn->query($update_log_query);

?>