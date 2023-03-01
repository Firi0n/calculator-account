<?php
// Class for account
class Account
{
    // Variables
    private int $user_id;
    private string $username;
    private string $email;
    private string $password;
    private bool $twoFA;
    private IMail $message;
    private $db;
    // Constructor
    public function __construct(IMail $message)
    {
        // Set Mailer type
        require_once __DIR__ . "/IMail.php";

        // Set message
        $this->message = $message;
        $this->twoFA = false;

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
    // Login function
    public function login(string $username, string $password, bool $allTwoFA = false): int
    {
        $query = "SELECT `user_id`, `email`, `twoFA`, `attempts`, `last_attempt` FROM `users` WHERE(`username` = '$username')";

        if ($this->existData("`username`", "users", "`username`='$username'")) {

            $result = $this->db->query($query);
            $result = mysqli_fetch_assoc($result);

            if ($this->existData(
                "`username`, `password` , `attempts`, `last_attempt`",
                "users",
                "`username`='$username' AND `password`='$password' AND 
                                (`attempts` > 0 OR `last_attempt` < (NOW() - INTERVAL 30 MINUTE))"
            )) {
                if ($result == TRUE) {
                    $this->db->query("UPDATE `users` SET `attempts` = 3, `last_attempt` = CURRENT_TIMESTAMP WHERE (`username` = '$username')");
                    $this->user_id = $result["user_id"];
                    $this->username = $username;
                    $this->email = $result["email"];
                    $this->password = $password;
                    $this->twoFA = $result["twoFA"];
                    session_start();
                    $_SESSION["account"] = serialize($this);
                    if($this->twoFA || $allTwoFA){
                        header("Location: twoFA.php");
                    }else{
                        $_SESSION["id"] = session_id();
                        header("Location: ../");
                    }
                    return 0;
                }
            } else {
                if($result["attempts"] > 0){
                    $this->db->query("UPDATE `users` SET `attempts` = (`attempts`-1), `last_attempt` = CURRENT_TIMESTAMP WHERE (`username` = '$username')");
                }
                return 1;
            }
        }
        return 2;
    }
    // Register function
    public function register(string $username, string $email, string $password): int
    {
        $query = "INSERT INTO `users`(`username`, `email`, `password`) VALUES('$username', '$email', '$password');";

        if ($this->existData("`username`", "users", "`username`='$username'")) {
            return 1;
        } else if ($this->existData("`email`", "users", "`email`='$email'")) {
            return 2;
        } else if ($this->db->query($query) === TRUE) {
            $this->login($username, $password, true);
            return 0;
        }
        return 3;
    }

    private function existData(string $data, string $table, string $condition)
    {
        $query = "SELECT EXISTS(SELECT " . $data . " FROM `" . $table . "` WHERE (" . $condition . ")) AS `existence`;";
        return mysqli_fetch_assoc($this->db->query($query))['existence'];
    }
    // Send two factor authentication code
    public function sendTwoFACode(): int
    {
        // Generate random code
        $auth = random_int(100000, 999999);
        // Send email
        $this->message->send($this->email, "Two Factor Authentication", "Your two factor authentication code is: " . $auth);
        // Return code
        return $auth;
    }
    // Logout function
    public static function logout()
    {
        // Destroy session
        session_start();
        session_destroy();
        header("Location: ../");
    }
    // Change data function
    public function changeData(string $username, string $email, string $password, string $twoFA): int
    {
        $this->db = mysqli_connect("localhost", "root", database: "account");
        $data = [
            "username" => $username,
            "email" => $email,
            "password" => $password,
            "twoFA" => $twoFA
        ];
        $i = 0;
        foreach($data as $key => $value){
            $i++;
            $query = "UPDATE `users` SET `$key` = '$value' WHERE (`user_id` = '$this->user_id' AND NOT `$key` = '$value');";
            if($this->db->query($query) === FALSE){
                return $i;
            }
        }
        $this->login($username, $password, true);
        return 0;
    }
    // Delete account function
    public function deleteAccount(): bool
    {
        $this->db = mysqli_connect("localhost", "root", database: "account");
        $query = "DELETE FROM `users` WHERE (`user_id` = '" . $this->user_id . "');";
        $result = $this->db->query($query);
        if ($result === TRUE) {
            Account::logout();
            return true;
        }else{
            return false;
        }
    }
    // Get data function
    public function getData(): array
    {
        // Return array with data
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
