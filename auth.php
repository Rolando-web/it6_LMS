<?php 

session_start();

class auth{

  private $conn;

  public function __construct($db){
    $this->conn = $db;
  } 

public function register($first_name, $last_name, $contact, $email, $password) {
    try {
        // Hash the password securely
        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $query = "INSERT INTO users (first_name, last_name, contact, email, password, roles)
                  VALUES (:first_name, :last_name, :contact, :email, :password, :roles)";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute with bound parameters
        return $stmt->execute([
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'contact'    => $contact,
            'email'      => $email,
            'password'   => $hash_password,
            'roles'      => 'Users'
        ]);

    } catch (PDOException $e) {
        // Optional: log error or handle it gracefully
        echo "Registration failed: " . $e->getMessage();
        return false;
    }
}

   public function login($email, $password) {
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false;
}

    public function isLoggedIn(){
  return isset($_SESSION['user_id']);
}

public function user(){
  return $_SESSION['user_id'] ?? null;
}


    public function logout(){
      session_destroy();
    }
}
?>