<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "dungeon");

$ability_type_id = $conn->real_escape_string($_POST['ability_type_id']);
$ability_damage = $conn->real_escape_string($_POST['ability_damage']);
$vinst_id = $conn->real_escape_string($_POST['vinst_id']);
$hero_name = $conn->real_escape_string($_POST['hero_name']);
$villain_name = $conn->real_escape_string($_POST['villain_name']);
$ability_name = $conn->real_escape_string($_POST['ability_name']);
$ability_cost = $conn->real_escape_string($_POST['ability_cost']);
$element_id = $conn->real_escape_string($_POST['element_id']);
$element_name = $conn->real_escape_string($_POST['element_name']);
$hinst_id = $conn->real_escape_string($_POST['hinst_id']);

$session_id = $conn->real_escape_string($_SESSION['session_id']);

$ability_modified_damage = $ability_damage; // For now, keep these equal, to potentially adjust later, and then compare

// Process elemental effects on the damage value
$element_resist_query = sprintf("SELECT 
    villain_element.element_id, villain_element.element_resist
    FROM villain_element
    LEFT JOIN villain
        ON villain_element.villain_id = villain.villain_id
    LEFT JOIN villain_instance
        ON villain_instance.villain_id = villain.villain_id
    WHERE villain_instance.vinst_id = '%s'",
    $vinst_id
);
$element_resist_result = $conn->query($element_resist_query);
$element_resists = array();
$element_resists = $element_resist_result->fetch_all(MYSQLI_ASSOC);

foreach ($element_resists as $resist) {
    if ($resist["element_id"] == $element_id) {
        $ability_modified_damage = round($ability_damage * $resist["element_resist"]);
    }
}

// TODO: Add damage strengths/weaknesses as part of this processing
$process_damage_query = sprintf("UPDATE
    villain_instance
    SET villain_instance.vinst_hp = villain_instance.vinst_hp - %d
    WHERE villain_instance.vinst_id = '%s'",
    $ability_modified_damage,                   // Even if no elements changed this, it should hold the original value by default
    $vinst_id
);
$process_damage_result = $conn->query($process_damage_query);

// TODO: Confirm availability of energy this ability costs
$update_energy_query = sprintf("UPDATE
hero_instance
SET hero_instance.hinst_energy = hero_instance.hinst_energy - %d
WHERE hero_instance.hinst_id = '%s'",
$ability_cost,                   // Even if no elements changed this, it should hold the original value by default
$hinst_id
);
$update_energy_result = $conn->query($update_energy_query);

$log_string = "";
if ($ability_modified_damage > $ability_damage) {
    $log_string = sprintf('<li data-t="h" data-id="%s">%s attacks %s with %s. The attack seems especially effective, inflicting %d points of damage, plus an additional %d points of %s damage!</li>',
        $hinst_id, $hero_name, $villain_name, $ability_name, $ability_damage, ($ability_modified_damage - $ability_damage), $element_name
    );
} else if ($ability_modified_damage < $ability_damage) {
    $log_string = sprintf('<li data-t="h" data-id="%s">%s attacks %s with %s of %s damage. The attack doesn\'t seem as effective as usual, inflicting only %d points of damage.</li>',
        $hinst_id, $hero_name, $villain_name, $ability_name, $ability_damage, $element_name, $ability_modified_damage
    ); 
} else {
    $log_string = sprintf('<li data-t="h" data-id="%s">%s attacks %s with %s, inflicting %d points of damage.</li>',
        $hinst_id, $hero_name, $villain_name, $ability_name, $ability_damage
    );
}
$update_log_query = sprintf("UPDATE
    session
    SET session.session_log = concat(session.session_log, '%s')
    WHERE session.session_id = '%s'",
    $log_string,
    $session_id
);
$update_log_result = $conn->query($update_log_query);

if ($conn->affected_rows > 0) {
    echo $log_string;
} else {
    //echo "Error: " . $conn->error;
    // TODO: Replace this with a more meaningful error state, to be handled by the requesting script
    echo "0";
}

?>