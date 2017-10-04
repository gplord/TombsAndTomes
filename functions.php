<?php

// Prints message as a Bootstrap alert, for debugging queries, etc.
function alert_query($message) {

    echo "<div class='alert alert-info' role='alert'>\n";
    echo $message;
    echo "</div>\n";

}

?>