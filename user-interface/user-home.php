<?php
session_start();
require '../auth.php';
require '../admin/backend/BookController.php';
require '../admin/backend/transactionControll.php';



$database = new Database();
$auth = new auth($database->pdo);
$library = new Library($database->pdo);

$books = $database->getBooks();
$totalbooks = $database->getTotalBooks();
$totalusers = $auth->getTotalusers();
$totaladmin = $auth->getTotaladmin();

if (!$auth->isLoggedIn() || $_SESSION['user_role'] !== 'Users') {
  header('Location: ../login.php');
  exit;
}



if (!$auth->isLoggedIn()) {
  header('Location: ../login.php');
  exit;
}

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

$category = $_GET['category'] ?? 'all';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'title';
$categories = ['all', 'fiction', 'technology', 'history', 'business', 'philosophy', 'arts', 'science', 'biology'];

$filter = $library->getFilteredBooks($category, $search, $sort);


?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/svg+xml" href="/vite.svg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../image/willan.jpg" type="image/jpeg">
  <link rel="stylesheet" href="../src/input.css" />
  <link rel="stylesheet" href="../src/output.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <title>Home Library</title>
</head>
<style>

</style>

<body class="bg-gray-900 text-white font-sans w-full">
  <div class="min-h-screen relative bg-cover bg-center bg-no-repeat" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.pexels.com/photos/481516/pexels-photo-481516.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop');">

    <!-- Header -->
    <?php if (file_exists('Frontend/header.php')) include 'Frontend/header.php'; ?>
    <!-- Header -->

    <!-- Hero Section -->
    <main class="relative z-0 flex-1 flex items-center justify-center px-6" style="background-image: url(../image/wew.png); background-size: cover; background-position: center;">
      <div class="text-center max-w-3xl mx-auto mt-16 mb-24">
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-light mb-6 leading-tight">
          <div class="text-gray-300 mb-2">Welcome to</div>
          <div class="font-normal">
            <span class="text-white">Home</span><span class="text-gray-300">Library</span>
          </div>
        </h1>

        <p class="text-gray-300 text-sm md:text-xl mb-8 max-w-2xl mx-auto leading-relaxed">
          Discover thousands of books, explore new genres, and embark on endless literary adventures. Your next great read awaits.
        </p>

        <a href="./user-borrow.php">
          <button class="bg-white text-gray-900 px-8 py-3 rounded-lg font-medium hover:bg-black hover:text-white hover:shadow-white hover:shadow-2xl transition-colors inline-flex items-center space-x-2 group cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <span>Book Collection</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
          </button>
        </a>
      </div>
    </main>

    <!-- Statistics -->
    <div class="relative z-0 px-6 py-12">
      <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-teal-600 bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
            </div>
            <div class="text-4xl font-light text-white mb-2">
              <?php echo $totalbooks ?>
            </div>
            <div class="text-gray-400">Books Available</div>
          </div>

          <!-- Active Members -->
          <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-blue-600 bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="text-4xl font-light text-white mb-2">
              <?php echo $totalusers ?>
            </div>
            <div class="text-gray-400">Active Members</div>
          </div>

          <!-- New Arrivals -->
          <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-amber-600 bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 2l7 4v5c0 5.25-3.5 9.75-7 11-3.5-1.25-7-5.75-7-11V6l7-4z" />
              </svg>
            </div>
            <div class="text-4xl font-light text-white mb-2">
              <?php echo $totaladmin ?></div>
            <div class="text-gray-400">Admin Available</div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- PARA DI GUBOT -->
  <!-- Books Collection -->
  <?php if (file_exists('Frontend/Bcollection.php')) include 'Frontend/Bcollection.php'; ?>
  <!-- borrow-modal -->
  <?php if (file_exists('Frontend/borrow-modal.php')) include 'Frontend/borrow-modal.php'; ?>
  <!-- category file -->
  <?php if (file_exists('Frontend/category.php')) include 'Frontend/category.php'; ?>
  <!-- Footer file -->
  <?php if (file_exists('Frontend/footer.php')) include 'Frontend/footer.php'; ?>
  <!-- PARA DI GUBOT -->
  <!-- Scripts -->


  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="../user-interface//user.js"></script>
  <script>
    // Open Borrow Modal
    document.querySelectorAll(".openBorrowModal").forEach((btn) => {
      btn.addEventListener("click", () => {
        const bookId = btn.dataset.bookId;
        const title = btn.dataset.bookTitle;
        const author = btn.dataset.bookAuthor;

        document.getElementById("borrowBookTitle").textContent = title;
        document.getElementById("borrowBookAuthor").textContent = author;
        document.getElementById("confirmBorrow").dataset.bookId = bookId;

        document.getElementById("borrowModal").classList.remove("hidden");

        updateReturnDate();

        document.getElementById("borrowDuration").addEventListener("change", () => {
          updateReturnDate();
        });


      });
    });


    document.querySelectorAll(".openBorrowModal").forEach((btn) => {
      btn.addEventListener("click", () => {
        const bookId = btn.dataset.bookId;
        const title = btn.dataset.bookTitle;
        const author = btn.dataset.bookAuthor;

        document.getElementById("borrowBookTitle").textContent = title;
        document.getElementById("borrowBookAuthor").textContent = author;

        // store bookId in confirm button
        document.getElementById("confirmBorrow").dataset.bookId = bookId;

        document.getElementById("borrowModal").classList.remove("hidden");

        // Close Success Modal
        if (closeSuccess) {
          closeSuccess.addEventListener("click", () => {
            successModal.classList.add("hidden");
          });
        }
        // Extra: close modal if you click outside of it
        [borrowModal, successModal].forEach((modal) => {
          if (modal) {
            modal.addEventListener("click", (e) => {
              if (e.target === modal) {
                modal.classList.add("hidden");
              }
            });
          }
        });

        // Cancel Borrow → Close Borrow Modal
        if (cancelBorrow) {
          cancelBorrow.addEventListener("click", () => {
            borrowModal.classList.add("hidden");
          });
        }
      });
    });
  </script>
</body>

</html>