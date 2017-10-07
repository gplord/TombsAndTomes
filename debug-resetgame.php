<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "dungeon");
$session_id = $conn->real_escape_string($_SESSION['session_id']);

// List of queries that reset the game to a default position
// Array structure for easy adding/removing/modifying
$query_array = array(
    "UPDATE `session` SET `session_log` = '' WHERE `session`.`session_id` = 's12345678'",
    "UPDATE `session` SET `session_update` = '1' WHERE `session`.`session_id` = 's12345678'",
    "UPDATE `session_player` SET `player_current` = '1' WHERE `session_player`.`session_id` = 's12345678' AND `session_player`.`player_id` = 'p11111111'",
    "UPDATE `session_player` SET `player_ready` = '0' WHERE `session_player`.`session_id` = 's12345678' AND `session_player`.`player_id` = 'p11111111'",
    "UPDATE `session_player` SET `player_current` = '0' WHERE `session_player`.`session_id` = 's12345678' AND `session_player`.`player_id` = 'p22222222'",
    "UPDATE `session_player` SET `player_ready` = '1' WHERE `session_player`.`session_id` = 's12345678' AND `session_player`.`player_id` = 'p22222222'",
    "UPDATE `session_player` SET `player_current` = '0' WHERE `session_player`.`session_id` = 's12345678' AND `session_player`.`player_id` = 'p33333333'",
    "UPDATE `session_player` SET `player_ready` = '1' WHERE `session_player`.`session_id` = 's12345678' AND `session_player`.`player_id` = 'p33333333'",
    "UPDATE `villain_instance` SET `vinst_hp` = '30' WHERE `villain_instance`.`vinst_id` = 'i10101010'",
    "UPDATE `villain_instance_ability` SET `cooldown_left` = '0' WHERE `villain_instance_ability`.`vinst_id` = 'i10101010' AND `villain_instance_ability`.`ability_id` = 5",
    "UPDATE `villain_instance_ability` SET `cooldown_left` = '0' WHERE `villain_instance_ability`.`vinst_id` = 'i10101010' AND `villain_instance_ability`.`ability_id` = 6",
    "UPDATE `villain_instance_ability` SET `cooldown_left` = '0' WHERE `villain_instance_ability`.`vinst_id` = 'i10101010' AND `villain_instance_ability`.`ability_id` = 7",
    "UPDATE `hero_instance` SET `hinst_hp` = `hinst_hp_max` WHERE `hero_instance`.`hinst_id` = 'c01010101'",
    "UPDATE `hero_instance` SET `hinst_hp` = `hinst_hp_max` WHERE `hero_instance`.`hinst_id` = 'c02020202'",
    "UPDATE `hero_instance` SET `hinst_hp` = `hinst_hp_max` WHERE `hero_instance`.`hinst_id` = 'c03030303'",
    "UPDATE `hero_instance` SET `hinst_energy` = `hinst_energy_max` WHERE `hero_instance`.`hinst_id` = 'c01010101'",
    "UPDATE `hero_instance` SET `hinst_energy` = `hinst_energy_max` WHERE `hero_instance`.`hinst_id` = 'c02020202'",
    "UPDATE `hero_instance` SET `hinst_energy` = `hinst_energy_max` WHERE `hero_instance`.`hinst_id` = 'c03030303'",
    "UPDATE `villain_instance_ability` SET `cooldown_left` = 0 WHERE `villain_instance_ability`.`vinst_id` = 'i10101010' AND `cooldown_left` > 0"
);

// Run the full list of queries
foreach ($query_array as &$query) {
    $result = $conn->query($query);
}

// Timestamped confirmation
echo "Game reset at: " . date('l F j Y h:i:s A');

?>