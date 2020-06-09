<?php
    session_start();
    require_once "config.php";
    // since the only way of accessing this page is through login page,
    // there's no need for redirection when user is loggedin

    $valid_name = $valid_password = $valid_username = $name = $password = $username = "";
    // username and password validation
    // is http post request happening?
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate name
        $name = trim($_POST["name"]);
        if (empty($name))
        {
            echo "Name field cannot be empty.";
        }else if (strlen($name) > 50){
            echo "Name maximum length allowed is 50 characters.";
        }else if (!preg_match("/^[a-zA-Z\ ]+/", $name)){ // checks for non-alphabetic or non-space characters
            echo "Name must contain letters only.";
        }else{
            $valid_name = $name;
        }
        
        // validate password and confirm password
        $password = $_POST["password"];
        // checks password for alphanumeric and . (dot), @ (at sign), - (dash)
        if (preg_match("/^[a-zA-Z0-9|.|@|\-|_]{6,}$/", $password))
        {
            if (trim($password) == trim($_POST["confirmpassword"])):
                $valid_password = $password;
            else:
                echo "Password and confirm password fields must match."; // error message
            endif;
        }else{
            // error message
            echo "Password must be 6 length minimum and must contain only alphanumeric or . (dot), @, - (dash), _ (underscore) characters.";
        }

        // validate username
        if (empty(trim($_POST["user"])))
        {
            // error message
            echo "Username field cannot be empty.";
        }else{
            $query = "SELECT username from credentials where username = :username";
            $username_fetch = $db->prepare($query);
            $username_fetch->bindValue(":username", trim($_POST["user"]));
            if($username_fetch->execute())
                if($username_fetch->rowCount() == 1){
                    echo "User " . trim($_POST["user"]) . "does exist.";
                }else{
                    $valid_username = trim($_POST["user"]);
                }

        }

        if (!empty($valid_username) and !empty($valid_password) and !empty($valid_name))
        {
            $query = "INSERT INTO credentials (username, name, password) VALUES (:username, :name, :password)";
            $insertion_stmt = $db->prepare($query);

            // binding all validated values
            $insertion_stmt->bindValue(":username", $valid_username);
            $insertion_stmt->bindValue(":name", $valid_name);
            $insertion_stmt->bindValue(":password", password_hash($valid_password, PASSWORD_BCRYPT));

            // if successfully carried out, we can redirect user to main page
            if ($insertion_stmt->execute()){
                

                $_SESSION['user_id'] = $db->lastInsertId();
                $_SESSION['name'] = $valid_name;
                $_SESSION['loggedin'] = true;

                header('location: index.php');
            }
        }
    }

?>

<form action="register.php" method="POST">
    <label>Username:</label><em> Will be used to log in, none besides you will see it.</em><br>
    <input type="text" name="user"><br>
    
    <label>Name:</label><em> Will be show to other users, anyone you talk can see it.</em><br>
    <input type="text" name="name"><br>
    
    <label>Password:</label><br>
    <input type="password" name="password"><br>
    
    <label>Confirm password:</label><br>
    <input type="password" name="confirmpassword"><br><br>
    
    <input type="submit" value="Register">
</form>