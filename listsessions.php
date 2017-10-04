<?php
session_start();
include ('header.php');

$conn = new mysqli("localhost", "root", "root", "dungeon");

$session_query = sprintf("SELECT session.session_id, session.session_name, session.session_created, session.session_time, session.session_desc FROM session WHERE session.session_complete = 0;");

$session_result = $conn->query($session_query);

?>

<div class="container mt-3">

    <div class="row">
        <div class="col-12">
            <div class="alert alert-info bg-info text-white" role="alert"> 
                <strong>Test Page:</strong> This page is intended for design and layout testing, and does not connect with the rest of the application.
            </div>
        </div>
    </div>

    <div class='alert alert-info' role='alert'>
        <?php echo $session_query; ?>
    </div>

<?php 
if ($session_result->num_rows > 0) {
    // output data of each row

    echo "<table class='table table-hover table-striped'>\n";

    echo "<thead class='thead-default'>\n";
    echo "<tr>\n";
    //echo "<th>ID</th>\n";
    echo "<th>Game Name</th>\n";
    //echo "<th>Created On</th>\n";
    echo "<th>Last Updated</th>\n";
    echo "<th>Join Game</th>\n";
    echo "</tr>\n";
    echo "</thead>\n";

    echo "<tbody>\n";

    $i = 1;

    while($session_row = $session_result->fetch_assoc()) {

        echo "<tr>\n";
        //echo "<th scope='row'>" . $session_row['session_id'] . "</td>\n";
        echo "<th scope='row'>" . $session_row['session_name'] . "</td>\n";
        //echo "<td>" . $session_row['session_created'] . "</td>\n";
        echo "<td>" . $session_row['session_time'] . "</td>\n";
        echo "<td><button class='btn btn-outline-primary btn-sm' data-session-id='" . $session_row['session_time'] . "'>Join</button></td>\n";
        echo "<tr>\n";

    }

    echo "</tbody>\n";

    echo "</table>\n";

} else {
    echo "<p class='error'>No active games found.</p>";
}

echo "<div>\n";
include ('footer.php');

?>
