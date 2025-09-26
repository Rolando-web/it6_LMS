
session_start();
require '../auth.php';
require '../admin/BookController.php';
require '../admin/transactionControll.php';


$database = new Database();
$books = $database->getBooks();
$library = new Library($database->pdo);

$auth = new auth($database);

if (isset($_POST['logout'])) {
  $auth->logout();
  header('Location: ../login.php');
  exit;
}



// Handle borrow request
if (isset($_POST['borrow'])) {
  $user_id = $_POST['user_id'];
  $book_id = $_POST['book_id'];
  $duration = $_POST['duration'];

  if (!$user_id) {
    die("Error: user_id is missing. Did you login?");
  }

  $message = $library->borrowBook($user_id, $book_id, $duration);

  header('Content-Type: application/json');
  echo json_encode(["status" => "ok", "message" => $message]);
  exit;
}

