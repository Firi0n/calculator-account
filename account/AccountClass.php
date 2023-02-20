<?php
require_once "DatabaseInterface.php";
// Class for account
class Account
{
    // Variables
    private string $username;
    private string $email;
    private string $password;
    private bool $twoFA;
    // Database interface for database types
    private DatabaseInterface $database;
    // Constructor
    public function __construct()
    {
        // Set database type
        $this->database = new JsonDatabase("database.json");
    }
    // Login function
    public function login(string $username, string $password) : array
    {
        // Get username from database
        $data = $this->database->get($username);
        // Check if username and password are correct
        $exist = [
            // If username is correct, set username to true, else false
            "username" => $data != [] ? true : false,
            // If password is correct, set password to true, else false
            "password" => $data["password"] == $password ? true : false
        ];
        // If username and password are correct, set variables and session
        if ($exist["username"] && $exist["password"]) {
            // Set variables
            $this->username = $username;
            $this->password = $password;
            $this->email = $data["email"];
            $this->twoFA = $data["twoFA"];
            // Set session
            $_SESSION["id"] = session_id();
            $_SESSION["account"] = $this;
        }
        // Return array with username and password exist
        return $exist;
    }
    // Register function
    public function register(string $username, string $email, string $password) : array
    {
        // Get username from database
        $data = $this->database->get($username);
        // Get all emails from database
        $emails = array_column($this->database->get(null, ["email"]), "email");
        // Check if username and email are already in use
        $exist = [
            // If username is already in use, set username to true, else false
            "username" => $data != [] ? true : false,
            // If email is already in use, set email to true, else false
            "email" => in_array($email, $emails)
        ];
        // If username and email are not in use, add account to database and login
        if (!$exist["username"] && !$exist["email"]) {
            // Add account to database
            $this->database->add([
                "username" => $username,
                "email" => $email,
                "password" => $password,
                "twoFA" => false
            ]);
            // Login
            $this->login($username, $password);
        }
        // Return array with username and email exist
        return $exist;
    }
    // Logout function
    public function logout()
    {
        // Destroy session
        session_destroy();
    }
    // Change data function
    public function changeData(string $username, string $email, string $password, string $twoFA) : bool
    {
        // Update data in database
        $return = $this->database->update($this->username, [
            "username" => $username,
            "email" => $email,
            "password" => $password,
            "twoFA" => $twoFA
        ]);
        // If data is updated, login
        if ($return) {
            $this->login($username, $password);
        }
        // Return if data is updated
        return $return;
    }
    // Delete account function
    public function deleteAccount() : bool
    {
        // Delete account from database
        $return = $this->database->delete($this->username);
        // If account is deleted, logout
        if ($return) {
            $this->logout();
        }
        // Return if account is deleted
        return $return;
    }
    // Get data function
    public function getData() : array
    {
        // Return array with data
        return [
            "username" => $this->username,
            "email" => $this->email,
            "password" => $this->password,
            "twoFA" => $this->twoFA
        ];
    }
}
