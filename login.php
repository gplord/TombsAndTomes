<?php

if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

if (!isset($_SESSION['username'])) {

    $login_error = false;
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if ( ($_POST['username'] != null) ) {
            if ( ($_POST['password'] != null) ) {

                $conn = new mysqli("localhost", "root", "root", "dungeon");

                $user = $conn->real_escape_string($_POST['username']);
                $pass = $conn->real_escape_string($_POST['password']);

                $login_query = sprintf("SELECT
                    player_id, player_email, player_password, player_name, player_firstname
                    FROM player
                    WHERE player.player_email = '%s'
                    AND player.player_password = '%s'",
                    $user, $pass);
                $login_result = $conn->query($login_query);
                $login = array();

                if ($login_result->num_rows > 0) {
                    $login_row = $login_result->fetch_assoc();
                    $_SESSION['username'] = $login_row['player_email'];
                    $_SESSION['firstname'] = $login_row['player_firstname'];
                    $_SESSION['name'] = $login_row['player_name'];
                    $_SESSION['player_id'] = $login_row['player_id'];
                    $_SESSION['session_id'] = "s12345678"; // Placeholder, for testing prior to implementing game sessions
                } else {
                    $login_error = true;
                }

                // if ($_POST['username'] == "Greg") {
                //     if ($_POST['password'] == "test") {
                //         $_SESSION['username'] = "Greg";
                //     }
                // }
            } else {
                $login_error = true;
            }
        } else {
            $login_error = true;
        }
    }

}

$pagetitle = "Login";
include ('header.php');
?>

<div class="container">


<?php 
// -- Not logged in -------------------------------------------------------- //
if (!isset($_SESSION['username'])) : 
?>

<?php if ($login_error) : ?>

<div class="row">
    <div class="col-12">
        <div class="alert alert-danger bg-danger text-white" role="alert"> 
            <strong>Login Error</strong> Incorrect username or password.
        </div>
    </div>
</div>

<?php endif; ?>

<div class="row justify-content-md-center mt-3">
    <div class="col-md-6">
        
<form action="" method="post">
<div class="card">
    <div class="card-header">
        Player Login
    </div>
    <div class="card-body">
        
        <fieldset class="form-group">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" id="btnGroupAddon" style="width:100px">Email</span>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Your email address" aria-label="Input group example" aria-describedby="btnGroupAddon">
                </div>
            </div>  
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" id="btnGroupAddon" style="width:100px">Password</span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Your password" aria-label="Input group example" aria-describedby="btnGroupAddon">
                </div>
            </div>
            <div class="clearfix mb-3">
                <button type="submit" class="btn btn-primary float-right" value="Login">Log In</button>
            </div>
            <div class="alert alert-primary" role="alert">
                <h4 class="alert-heading">Warning</h4>
                <p>This application is in development, and does not yet store passwords securely.</p>
                <p>Please do not reuse any real passwords for player accounts.</p>
            </div>
            
        </fieldset>

    </div>
</div>
</form>


<? 
// -- Logged in ------------------------------------------------------------ //
else : 
?>

<p>You are logged in as <?php echo $_SESSION['username']; ?>.  <a href="logout.php">Logout</a>


<? endif; ?>