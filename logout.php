<?php
session_start();
session_destroy();
include('header.php');
?>

<div class="row justify-content-md-center mt-3">
    <div class="col-md-6">
        
<div class="card">
    <div class="card-header">
        Player Logout
    </div>
    <div class="card-body text-center">
        
        <p>You are now logged out.</p>
        <p><a href="login.php">Login</a></p>

    </div>
</div>

<?php
include ('footer.php');
?>