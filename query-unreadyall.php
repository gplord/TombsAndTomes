<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "dungeon");
$session_id = $conn->real_escape_string($_SESSION['session_id']);

$unready_player_query = sprintf("UPDATE 
    session_player
    SET session_player.player_ready = '0'
    WHERE session_player.session_id = '%s'",
        $session_id
    );
$unready_player_result = $conn->query($unready_player_query);
if ($conn->affected_rows > 0) {

} else {
    die("Error updating: " . $conn->error);
}

$unready_session_query = sprintf("UPDATE 
session
SET session.session_ready = '0'
WHERE session.session_id = '%s'",
    $session_id
);
$unready_session_result = $conn->query($unready_session_query);
if ($conn->affected_rows > 0) {
    echo "1";
} else {
    die("Error updating: " . $conn->error);
}


// echo json_encode($readys, JSON_PRETTY_PRINT);

?>