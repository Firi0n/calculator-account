<?php
// Class for account
class Account
{
    // Variables
    private string $username;
    private string $email;
    private string $password;
    private bool $twoFA;
    private IMail $message;
    // Constructor
    public function __construct($home, IMail $message)
    {
        // Set database type
        require_once $home."class/IMail.php";
        // Set message
        $this->message = $message;
    }
    // Login function
    public function login(string $username, string $password) : array
    {
        return [
            "username" => true,
            "password" => true
        ];
    }
    // Register function
    public function register(string $username, string $email, string $password) : array
    {
        return [
            "username" => true,
            "email" => true,
            "password" => true
        ];
    }
    // Send two factor authentication code
    public function sendTwoFACode() : string
    {
        // Generate random code
        $auth = random_int(100000, 999999);
        // Send email
        $this->message->send($this->email, "Two Factor Authentication", "Your two factor authentication code is: ". $auth);
        // Return code
        return $auth;
    }
    // Logout function
    public static function logout()
    {
        // Destroy session
        session_destroy();
    }
    // Change data function
    public function changeData(string $username, string $email, string $password, string $twoFA) : bool
    {
        return true;
    }
    // Delete account function
    public function deleteAccount() : bool
    {
        return true;
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
