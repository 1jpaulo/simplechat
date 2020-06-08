<?php 
    session_start();
    
    // if user isn't logged in, we'll redirect its session.
    if(isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === true){
        header("location: index.php");
        exit;
    }
    //echo(password_hash("joao", PASSWORD_BCRYPT));
    if (isset($_POST["user"]) AND isset($_POST["password"])){
        if (trim($_POST["user"]) != NULL AND trim($_POST["password"]) != NULL)
        {
            // user and password aren't null, so we have to look for it on database
            require_once "config.php";

            // validate credentials
            // here :username will be the binded parameter
            $query = "SELECT credentials_id, name, password FROM credentials WHERE username = :username";
            
            // successfull preparation, otherwise development error
            if($fetch_user_stmt = $db->prepare($query))
            {
                $fetch_user_stmt->bindValue(":username", trim($_POST["user"]));
                
                // search for username
                if ($fetch_user_stmt->execute()){
                    
                    if($fetch_user_stmt->rowCount() == 1)
                    {
                        if ($row = $fetch_user_stmt->fetch()){
                            
                            $hash_password = $row["password"];
                            
                            // if password matches
                            if(password_verify(trim($_POST["password"]), $hash_password)){
                                // session variables for main page session

                                $_SESSION['user_id'] = $row["credentials_id"];
                                $_SESSION['name'] = $row["name"];
                                $_SESSION['loggedin'] = true;

                                header('location: index.php');
                            }
                        }
                    }else{
                        // error message
                        echo "Either username or password aren't correct. Please try again.";
                    }
                }else{
                    // error message
                    echo "User " . $_POST["user"] . "doesn't exist.";
                }
                
            }else{
                // error message
                echo "User fetch couldn't be prepared for execution.";
            }


        }else{
            // error message
            echo "Username or password fields cannot be blank.";
        }
    }
?>

<form action="login.php" method="POST">
    <label>User:</label><br>
    <input type="text" name="user"><br>
    <label>Password:</label><br>
    <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>