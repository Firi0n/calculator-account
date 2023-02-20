<?php
// Class for database on json files
class JsonDatabase implements DatabaseInterface
{
    // Path to database
    private string $path;
    // Constructor
    public function __construct(string $path)
    {
        $this->path = $path;
    }
    // Get specific data from database
    public function get(string $username = null, array $data = null): array
    {
        // Get database
        $database = json_decode(file_get_contents($this->path), true);
        // Result array
        $result = [];
        // Loop through database
        foreach ($database as $tuple) {
            // If username is given, return specific data of that tuple or return the whole tuple
            if ($tuple["username"] == $username || $username == null) {
                // If no data is given, return the whole tuple or whole database
                if ($data == null) {
                    array_push($result, $tuple);
                } else {
                    // Else return specific data of that tuple or all tuples
                    foreach ($data as $key) {
                        $dataOfUser[$key] = $tuple[$key];
                    }
                    array_push($result, $dataOfUser);
                }
            }
        }
        // Return result
        return $result;
    }
    // Rewrite database
    public function set(array $data): bool
    {
        return file_put_contents($this->path, json_encode($data));
    }
    // Add data to database
    public function add(array $data): bool
    {
        // Get database
        $database = $this->get();
        // Add data to database
        array_push($database, $data);
        // Rewrite database
        return $this->set($database);
    }
    // Delete data from database
    public function delete(string $username = null): bool
    {
        // Get database
        $database = $this->get();
        // Loop through database
        foreach ($database as $index => $tuple) {
            // If no key is given, delete whole database
            if ($tuple["username"] == $username) {
                // Delete tuple
                unset($database[$index]);
            }
        }
        // Rewrite database
        return $this->set($database);
    }
    // Update data in database
    public function update(string $username, array $data): bool
    {
        // Get database
        $database = $this->get();
        // Loop through database
        foreach ($database as $index => $tuple) {
            // Update specific tuple
            if ($tuple["username"] == $username) {
                // Loop through data
                foreach ($data as $key => $value) {
                    // Update data
                    $database[$index][$key] = $value;
                }
            }
        }
        // Rewrite database
        return $this->set($database);
    }
}
// Database interface for database types
interface DatabaseInterface
{
    public function get(string $username = null, array $data = null): array;
    public function set(array $data): bool;
    public function add(array $data): bool;
    public function delete(string $username = null): bool;
    public function update(string $username, array $data): bool;
}
?>
