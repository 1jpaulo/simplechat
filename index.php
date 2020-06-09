<?php 

    session_start();
    
    // if user isn't logged in, we'll redirect its session.
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        die();
    }
?>

<h1> Hello, <?php echo $_SESSION["name"]; ?> </h1>
<a href="logout.php">Log out.</a>