<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "dungeon");
$session_update = $conn->real_escape_string($_POST["session_update"]);  // Received from polling page in $_POST
$session_id = $conn->real_escape_string($_SESSION['session_id']);       // Part of the $_SESSION for logged in players

// Retrieve the current session.session_update value for this game
$update_query = sprintf("SELECT session.session_update 
    from session 
    WHERE session.session_id = '%s'",
    $session_id);
$update_result = $conn->query($update_query);
$updates = array();
$updates = $update_result->fetch_all(MYSQLI_ASSOC);

// Convert both values to int, for safety in comparison below
$val1 = (int)$updates[0]["session_update"];
$val2 = (int)$session_update;

// If the two are equal, return TRUE, if not, FALSE
if ($val1 == $val2) {
    echo "1";           // TRUE: We have the most recent update
} else {
    echo "0";           // FALSE: We are now out of date
}
?>