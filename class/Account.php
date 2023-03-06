<?php

class Account
{
    // Variables
    private int $user_id;
    private string $username;
    private string $email;
    private string $password;
    private bool $twoFA;
    private $db;

    // Constructor
    public function __construct()
    {
        //Set db
        $db = mysqli_connect("localhost", "root");

        //Create db if it doesn't exist
        $query = "CREATE DATABASE IF NOT EXISTS account;";
        if ($db->query($query)) {
            $db = mysqli_connect("localhost", "root", database: "account");
        } else {
            throw new Exception("Error creating database!");
        }

        //Create tables if they don't exist
        $query = file_get_contents(__DIR__ . "/../sql/table_creation.sql");

        if (!$db->query($query)) {
            throw new Exception("Error creating tables!");
        }

        $this->db = $db;
    }

    //This private function is used to check if a given condition on database data evaluates to true or false.
    private function controlDB(string $data, string $table, string $condition) : bool
    {
        //Generic query with function parameters.
        $query = "SELECT EXISTS(SELECT " . $data . " FROM `" . $table . "` WHERE (" . $condition . ")) AS `existence`;";
        //Return only true or false.
        return mysqli_fetch_assoc($this->db->query($query))['existence'];
    }

    //This function is used to first part of registration.
    public function registration(string $username, string $email, string $password) : int
    {
        if ($this->controlDB("`username`", "users", "`username`='$username'")) {
            return 1;
        } else if ($this->controlDB("`email`", "users", "`email`='$email'")) {
            return 2;
        }else{
            $this->twoFAPage($username, $email, $password);
        }
    }

    public function login(string $username, string $password) : int
    {
        if($this->controlDB("`username`", "users", "`username`='$username'")){
            if ($this->existData(
                "`username`, `password` , `attempts`, `last_attempt`",
                "users",
                "`username`='$username' AND `password`='$password' AND 
                (`attempts` > 0 OR `last_attempt` < (NOW() - INTERVAL 30 MINUTE))"
            )){
                $this->db->query("UPDATE `users` SET `attempts` = 3, `last_attempt` = CURRENT_TIMESTAMP WHERE (`username` = '$username')");
                $email = mysqli_fetch_assoc($this->db->query("SELECT `email` FROM `users` WHERE(`username` = '$username')"))["email"];
                if($this->twoFA){
                    $this->twoFAPage($username, $email, $password);
                }else{
                    $this->username = $username;
                    $this->email = $email;
                    $this->password = $password;
                    $this->termLogOrReg();
                    header("Location: ../");
                }
            }else{
                $this->db->query("UPDATE `users` SET `attempts` = (`attempts`-1), `last_attempt` = CURRENT_TIMESTAMP WHERE (`username` = '$username' AND `attempts` > 0)");
                return 1;
            }
        }else{
            return 2;
        }
    }

    private function termLogOrReg(){
        if( !$this->existData("`username`", "users", "`username`='$this->username'" ) ){
            $query = "INSERT INTO `users`(`username`, `email`, `password`) VALUES('$this->username', '$this->email', '$this->password');";
            $this->db->query($query);
        }
        
    }

    private function twoFAPage($username, $email, $password){
        session_start();
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $_SESSION["account"] = serialize($this);
        header("Location: twoFA.php");
    }

    public function twoFA(IMail $message): int
    {
        // Generate random code
        $auth = random_int(100000, 999999);
        // Send email
        $message->send($this->email, "Two Factor Authentication", "Your two factor authentication code is: " . $auth);
        // Return code
        return $auth;
    }


}

?>
