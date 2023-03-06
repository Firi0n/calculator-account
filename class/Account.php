<?php

class Account
{
    // Variables;
    private int $user_id;
    private string $username;
    private string $email;
    private string $password;
    private bool $twoFA;
    private $db;

    // Constructor;
    public function __construct()
    {
        //Set db;
        $db = mysqli_connect("localhost", "root");

        //Create db if it doesn't exist;
        $query = "CREATE DATABASE IF NOT EXISTS account;";
        if ($db->query($query)) {
            $db = mysqli_connect("localhost", "root", database: "account");
        } else {
            throw new Exception("Error creating database!");
        }

        //Create tables if they don't exist;
        $query = file_get_contents(__DIR__ . "/../sql/table_creation.sql");

        if (!$db->query($query)) {
            throw new Exception("Error creating tables!");
        }

        $this->db = $db;
    }

    //This private function is used to check if a given condition on database data evaluates to true or false;
    private function controlDB(string $data, string $table, string $condition) : bool
    {
        //Generic query with function parameters;
        $query = "SELECT EXISTS(SELECT " . $data . " FROM `" . $table . "` WHERE (" . $condition . ")) AS `existence`;";
        //Return only true or false;
        return mysqli_fetch_assoc($this->db->query($query))['existence'];
    }

    //This function is used to first part of registration;
    public function registration(string $username, string $email, string $password) : int
    {
        //If username is already taken, return 1;
        if ($this->controlDB("`username`", "users", "`username`='$username'")) {
            return 1;
        //If email is already taken, return 2:
        } else if ($this->controlDB("`email`", "users", "`email`='$email'")) {
            return 2;
        }else{
            //If username and email are not taken, redirect to twoFA page;
            $this->twoFAPage($username, $email, $password);
        }
    }

    //This function is used to first part of login;
    public function login(string $username, string $password) : int
    {
        //If username is not taken, return 2;
        if($this->controlDB("`username`", "users", "`username`='$username'")){
            //If username and password are correct and attempts are not 0 or last attempt is more than 30 minutes ago continue;
            if ($this->controlDB(
                "`username`, `password` , `attempts`, `last_attempt`",
                "users",
                "`username`='$username' AND `password`='$password' AND 
                (`attempts` > 0 OR `last_attempt` < (NOW() - INTERVAL 30 MINUTE))"
            )){
                //If login is successful, reset attempts to 3;
                $this->db->query("UPDATE `users` SET `attempts` = 3, `last_attempt` = CURRENT_TIMESTAMP WHERE (`username` = '$username')");
                //Get email and twoFA of user.
                $result = mysqli_fetch_assoc($this->db->query("SELECT `email`, `twoFA` FROM `users` WHERE(`username` = '$username')"));
                $email = $result["email"];
                $twoFA = $result["twoFA"];
                //If twoFA is true, redirect to twoFA page;
                if($twoFA){
                    $this->twoFAPage($username, $email, $password);
                }else{
                    //If twoFA is false, set variables and redirect to home page;
                    $this->username = $username;
                    $this->email = $email;
                    $this->password = $password;
                    $this->termLogOrReg();
                }
            }else{
                //If login is not successful, decrease attempts by 1 and return 1;
                $this->db->query("UPDATE `users` SET `attempts` = (`attempts`-1), `last_attempt` = CURRENT_TIMESTAMP WHERE (`username` = '$username' AND `attempts` > 0)");
                return 1;
            }
        }else{
            return 2;
        }
    }

    //This function is used to second part of registration and login;
    public function termLogOrReg(){
        // Reconnect to database;
        $this->db = mysqli_connect("localhost", "root", database: "account");
        //If username doesn't exist, register;
        if( !$this->controlDB("`username`", "users", "`username`='$this->username'" ) ){
            //Register;
            $query = "INSERT INTO `users`(`username`, `email`, `password`) VALUES('$this->username', '$this->email', '$this->password');";
            $this->db->query($query);
        }
        //Get user_id and twoFA;
        $query = "SELECT `user_id`, `twoFA` FROM `users` WHERE(`username` = '$this->username')";
        $result = mysqli_fetch_assoc($this->db->query($query));
        $this->user_id = $result["user_id"];
        $this->twoFA = $result["twoFA"];
        //Set session variables;
        session_start();
        $_SESSION["id"] = session_id();
        $_SESSION["account"] = serialize($this);
        //Redirect to home page;
        header("Location: ../");

    }

    //This function is used set variables and redirect to twoFA page;
    private function twoFAPage($username, $email, $password){
        //Set variables;
        session_start();
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        //Save this object to session;
        $_SESSION["account"] = serialize($this);
        //Redirect to twoFA page;
        header("Location: twoFA.php");
    }

    public function twoFACode(IMail $message): int
    {
        // Generate random code;
        $auth = random_int(100000, 999999);
        // Send email;
        $message->send($this->email, "Two Factor Authentication", "Your two factor authentication code is: " . $auth);
        // Return code;
        return $auth;
    }

    // Change data function;
    public function changeData(string $username, string $email, string $password, string $twoFA): int
    {
        // Reconnect to database;
        $this->db = mysqli_connect("localhost", "root", database: "account");
        // combine data into array;
        $data = [
            "username" => $username,
            "email" => $email,
            "password" => $password,
            "twoFA" => $twoFA
        ];
        // Use foreach loop to update data;
        // Use $i to return the number of the data that failed to update;
        $i = 0;
        foreach($data as $key => $value){
            $i++;
            $query = "UPDATE `users` SET `$key` = '$value' WHERE (`user_id` = '$this->user_id' AND NOT `$key` = '$value');";
            // If query fails, return $i;
            if($this->db->query($query) === FALSE){
                return $i;
            }
        }
        // If all data is updated, repeat login;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->termLogOrReg();
        return 0;
    }

    // Logout function;
    public static function logout()
    {
        // Destroy session;
        session_start();
        session_destroy();
        // Redirect to home page;
        header("Location: ../");
    }

    // Delete account function;
    public function deleteAccount(): bool
    {
        // Reconnect to database;
        $this->db = mysqli_connect("localhost", "root", database: "account");
        // Delete account;
        $query = "DELETE FROM `users` WHERE (`user_id` = '" . $this->user_id . "');";
        $result = $this->db->query($query);
        // If account is deleted, logout and return true;
        if ($result === TRUE) {
            Account::logout();
            return true;
        // If account is not deleted, return false;
        }else{
            return false;
        }
    }
    // Get data function;
    public function getData(): array
    {
        // Return array with data;
        return [
            "user_id" => $this->user_id,
            "username" => $this->username,
            "email" => $this->email,
            "password" => $this->password,
            "twoFA" => $this->twoFA
        ];
    }

}

?>
