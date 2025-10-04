<?php
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

// Handle Category
$category = $_GET['category'] ?? 'all';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'title';

$filter = $library->getFilteredBooks($category, $search, $sort);



?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/svg+xml" href="/vite.svg" />
  <link rel="icon" href="../image/willan.jpg" type="image/jpeg">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Book Collection - Home Library</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            'sans': ['Inter', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>
</head>

<body class="bg-gray-900 text-white font-sans">
  <!-- Visit Header.php -->
  <?php if (file_exists('Frontend/header.php')) include 'Frontend/header.php'; ?>
  <!-- Header -->

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-4xl font-light text-white mb-2">Book Collection</h1>
      <p class="text-gray-400 text-lg">Discover and borrow from our extensive collection of books</p>
    </div>

    <!-- Filters and Controls -->
    <div class="bg-gray-800 rounded-xl p-6 mb-8">
      <form id="filterForm" method="GET" action="<?= $_SERVER['PHP_SELF'] ?>">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
          <!-- Category Filter -->
          <div class="flex flex-wrap gap-2">
            <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-white text-gray-900" data-category="all" onclick="submitForm('all')">
              All Books
            </button>
            <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="fiction" onclick="submitForm('fiction')">Fiction</button>
            <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="technology" onclick="submitForm('technology')">Technology</button>
            <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="history" onclick="submitForm('history')">History</button>
            <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="business" onclick="submitForm('business')">Business</button>
            <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="philosophy" onclick="submitForm('philosophy')">Philosophy</button>
            <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="arts" onclick="submitForm('arts')">Arts</button>
          </div>

          <div>
            <input type="text" name="search" id="searchInput" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Search by Title ..." class="bg-gray-700 text-white px-3 py-2 rounded-lg text-sm border border-gray-600 focus:border-gray-400 focus:outline-none">
          </div>
          <input type="hidden" name="category" id="categoryInput" value="<?= htmlspecialchars($_GET['category'] ?? 'all') ?>">
          <!-- Sort Options -->
          <div class="flex items-center space-x-4">
            <label class="text-gray-400 text-sm">Sort by:</label>
            <select name="sort" id="sortSelect" class="bg-gray-700 text-white px-3 py-2 rounded-lg text-sm border border-gray-600 focus:border-gray-400 focus:outline-none"
              onchange="submitForm()">
              <option value="title" <?= $_GET['sort'] ?? 'title' === 'title' ? 'selected' : '' ?>>Title A-Z</option>
              <option value="author" <?= $_GET['sort'] ?? 'title' === 'author' ? 'selected' : '' ?>>Author A-Z</option>
              <option value="year" <?= $_GET['sort'] ?? 'title' === 'publish_year' ? 'selected' : '' ?>>Publication Year</option>
            </select>
          </div>
        </div>
      </form>
    </div>

    <!-- Books Grid -->
    <div id="booksGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">

      <?php foreach ($filter as $book): ?>
        <div class="bg-gray-800 rounded-xl p-2 hover:bg-gray-750 transition-colors group">


          <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
            <div class="mb-4">
              <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden">
                <img src="../admin/<?= $book['image'] ?: 'uploads/default.jpg' ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="w-full h-full object-cover rounded-lg">
              </div>
            </div>

            <div class="space-y-2">
              <h3 class="text-white font-medium text-lg leading-tight"><?= htmlspecialchars($book['title']) ?></h3>
              <p class="text-gray-400 text-sm"><?= htmlspecialchars($book['author']) ?></p>
              <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-1">
                  <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                  <span class="text-yellow-400 text-sm font-medium">4.8</span>
                </div>
                <span class="text-gray-500 text-sm"><?= htmlspecialchars($book['publish_date']) ?></span>
              </div>
              <form method="POST">
                <input type="hidden" name="user_id" value="<?= isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : '' ?>">
                <input type="hidden" name="book_id" value="<?= (int)$book['id'] ?>">
                <button type="button" class="openBorrowModal w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4" data-book-id="<?= $book['id'] ?>" data-book-title="<?= htmlspecialchars($book['title']) ?>" data-book-author="<?= htmlspecialchars($book['author']) ?>">
                  Borrow Book
                </button>
              </form>
            </div>
          </div>

        </div>
      <?php endforeach; ?>
    </div>

    <!-- Load More Button -->
    <div class="text-center">
      <button id="loadMoreBtn" class="bg-white text-gray-900 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors inline-flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Load More Books</span>
      </button>
    </div>
  </main>


  <!-- MODAL -->
  <?php if (file_exists('Frontend/borrow-modal.php')) include 'Frontend/borrow-modal.php'; ?>
  <!-- MODAL -->'


  <script src="../user-interface/user.js"></script>
  <script src="../user-interface/borrow.js"></script>

  <script>
    // For Filter
    function submitForm(category = null) {
      const form = document.getElementById("filterForm");
      const categoryInput = document.getElementById("categoryInput");
      const buttons = document.querySelectorAll(".filter-btn");

      if (category) {
        categoryInput.value = category;
        buttons.forEach((btn) => btn.classList.remove("active"));
        document
          .querySelector(`.filter-btn[data-category="${category}"]`)
          .classList.add("active");
      }

      form.submit();
    }

    // handle duration → returnDate
    document.getElementById("confirmBorrow").addEventListener("click", () => {
      const bookId = document.getElementById("confirmBorrow").dataset.bookId;
      const userId = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
      const duration = document.getElementById("borrowDuration").value;
      const returnDate = document.getElementById("returnDate").value;

      fetch("", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: `borrow=1&book_id=${bookId}&user_id=${userId}&duration=${duration}&return_date=${returnDate}`
        })
        .then(res => res.json())
        .then(data => {

          document.getElementById("borrowModal").classList.add("hidden");
          document.getElementById("successModal").classList.remove("hidden");


          if (data.message) {
            document.querySelector("#successModal h3").textContent = data.message;
          }
        })
        .catch(err => {
          console.error("Borrow failed:", err);
          alert("Something went wrong.");
        });
    });
  </script>
</body>

</html>