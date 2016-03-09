<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(empty($_POST["username"]))
        {
            apologize("Please enter a username!");
        }
        
        $result = CS50::query("SELECT * FROM users WHERE username = ?", $_POST["username"]);
        
        if($result === true)
        {
            apologize("Username taken");
        }
        
        if(empty($_POST["password"]))
        {
            apologize("Please enter a valid password");
        }
        
        if(empty($_POST["confirmation"]))
        {
            apologize("Please confirm your password");
        }
        
        if($_POST["password"] != $_POST["confirmation"])
        {
            apologize("Passwords do not match!");
        }
        
        
        
        else
        {
            $query = CS50::query("INSERT IGNORE INTO users (username, hash, cash) 
            VALUES(?, ?, 10000.0000)", $_POST["username"], 
            password_hash($_POST["password"], PASSWORD_DEFAULT));
            
            if($query === false)
            {
                apologize("Could not register");
            }
            
            else
            {
                $rows = CS50::query("SELECT LAST_INSERT_ID() as id");
                if (count($rows) == 1)
                {
                    $id = $rows[0]["id"];
                    $_SESSION["id"] = $id;
                    redirect("/");
                }
            }
        }
    }
?>