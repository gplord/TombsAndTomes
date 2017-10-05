<?php
session_start();

$conn = new mysqli("localhost", "root", "root", "dungeon");
$session_id = $conn->real_escape_string($_SESSION['session_id']);
$player_id = $conn->real_escape_string($_SESSION['player_id']);

function QueryPlayerOrder($conn, $session_id, $player_id) {
    $player_order_query = sprintf("SELECT session_player.*, player.player_firstname, hero.hero_name
        FROM session_player 
        JOIN player ON session_player.player_id = player.player_id
        JOIN player_hero_instance ON player.player_id = player_hero_instance.player_id
        JOIN hero_instance ON player_hero_instance.hinst_id = hero_instance.hinst_id
        JOIN hero ON hero_instance.hero_id = hero.hero_id
        WHERE session_player.session_id = 's12345678';",
        $session_id,
        $player_id
    );
    $player_order_result = $conn->query($player_order_query);
    $player_order = array();
    $player_order = $player_order_result->fetch_all(MYSQLI_ASSOC);
    return $player_order;
}

$player_order = QueryPlayerOrder($conn, $session_id, $player_id);

// foreach ($player_order as &$player) {
    
//     $abilities = QueryHeroAbilities($conn, $hero_instance['hero_id']);
//     for ($i = 0; $i < count($abilities); $i++) {
//         $hero_instance['abilities'][$i] = $abilities[$i];
//     }

//     $effects = QueryHeroInstanceEffects($conn, $hero_instance['hinst_id']);
//     for ($i = 0; $i < count($effects); $i++) {
//         $hero_instance['effects'][$i] = $effects[$i];
//     }

// }

echo json_encode($player_order, JSON_PRETTY_PRINT);
// echo json_encode(array("response" => "0")); // No updates required -- JSON parser will test for this in updating application

?>