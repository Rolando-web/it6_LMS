<?php
class auth
{

  private $conn;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function register($first_name, $last_name, $contact, $email, $password)
  {
    try {
      //raw password
      $raw_password = $password;
      // Hash the password securely
      $hash_password = password_hash($password, PASSWORD_DEFAULT);

      // Prepare the SQL query
      $query = "INSERT INTO users (first_name, last_name, contact, email, password, plain_password, roles)
          VALUES (:first_name, :last_name, :contact, :email, :password, :plain_password, :roles)";


      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute with bound parameters
      return $stmt->execute([
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'contact'    => $contact,
        'email'      => $email,
        'password'   => $hash_password,
        'plain_password' => $raw_password,
        'roles'      => 'Users'
      ]);
    } catch (PDOException $e) {
      // Optional: log error or handle it gracefully
      echo "Registration failed: " . $e->getMessage();
      return false;
    }
  }

  public function Modalregister($first_name, $last_name, $contact, $email, $password, $roles)
  {
    try {
      //raw password
      $raw_password = $password;
      // Hash the password securely
      $hash_password = password_hash($password, PASSWORD_DEFAULT);

      // Prepare the SQL query
      $query = "INSERT INTO users (first_name, last_name, contact, email, password, plain_password, roles)
          VALUES (:first_name, :last_name, :contact, :email, :password, :plain_password, :roles)";


      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute with bound parameters
      return $stmt->execute([
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'contact'    => $contact,
        'email'      => $email,
        'password'   => $hash_password,
        'plain_password' => $raw_password,
        'roles'      => $roles
      ]);
    } catch (PDOException $e) {
      // Optional: log error or handle it gracefully
      echo "Registration failed: " . $e->getMessage();
      return false;
    }
  }

  public function login($email, $password)
  {
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_first_name'] = $user['first_name'];
      $_SESSION['user_last_name'] = $user['last_name'];
      $_SESSION['user_role'] = $user['roles'];
      return $user;
    }
    return false;
  }

  //Read All
  public function getUsers()
  {
    $sql = "SELECT * FROM users ORDER BY roles DESC";
    $stmt = $this->conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  //pagination function

  public function totaleusers($limit = 6, $offset = 0)
  {
    $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getTotalusers()
  {
    $stmt = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE roles = 'Users'");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
  }

  public function getTotaladmin()
  {
    $stmt = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE roles = 'Admin'");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
  }



  //DELETE USERS

  public function deleteuser($id)
  {
    try {
      $sql = "DELETE FROM users WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      return $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
      error_log("Delete failed: " . $e->getMessage());
      return false;
    }
  }


  public function isLoggedIn()
  {
    return isset($_SESSION['user_id']);
  }

  public function user()
  {
    return [
      'first_name' => $_SESSION['user_first_name'] ?? 'Guest',
      'last_name'  => $_SESSION['user_last_name']  ?? ''
    ];
  }


  public function logout()
  {
    session_destroy();
  }
}
