<?php
session_start();
require '../auth.php';
require '../admin/backend/BookController.php';
require '../admin/backend/transactionControll.php';


$database = new Database();
$books = $database->getBooks();
$library = new Library($database->pdo);

$auth = new auth($database);

if (isset($_POST['logout'])) {
  $auth->logout();
  header('Location: ../login.php');
  exit;
}

if (!$auth->isLoggedIn() || $_SESSION['user_role'] !== 'Users') {
  header('Location: ../login.php');
  exit;
}



if (isset($_POST['return_book'])) {
  $transaction_id = $_POST['transaction_id'];
  $result = $library->returnBook($transaction_id);
}



// Fetch active borrowings for the user
$activeBorrowings = $library->getActiveBorrowingsWithUser($_SESSION['user_id']);
$transactionMap = [];
foreach ($activeBorrowings as $borrowing) {
  $transactionMap[$borrowing['book_id']] = $borrowing['transaction_id'];
}

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
    <div class="mb-8">
      <h1 class="text-4xl font-light text-white mb-2">Borrowed Collection</h1>
      <p class="text-gray-400 text-lg">Kindly return borrowed books on or before the due date to prevent extra charges for overdue days.</p>
    </div>

    <!-- Filters and Controls -->
    <div class="bg-gray-800 rounded-xl p-6 mb-8">
      <form id="filterForm" method="GET" action="<?= $_SERVER['PHP_SELF'] ?>">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
          <div class="flex flex-wrap gap-2">
            <button class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-white text-gray-900" data-category="all">
              All Books You Borrowed
            </button>
          </div>
        </div>
      </form>
    </div>

    <?php if (isset($_GET['returned'])): ?>
      <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
        ✅ Book returned successfully!
      </div>
    <?php endif; ?>


    <!-- Books Grid -->
    <div id="booksGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
      <?php foreach ($activeBorrowings as $borrowing): ?>
        <div class="bg-gray-800 rounded-xl p-2 hover:bg-gray-750 transition-colors group">
          <div class="swiper-slide">
            <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
              <div class="mb-4">
                <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden">
                  <img src="../admin/<?= $borrowing['image'] ?: 'uploads/default.jpg' ?>"
                    alt="<?= htmlspecialchars($borrowing['title']) ?>"
                    class="w-full h-full object-cover rounded-lg">
                </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-white font-medium text-lg leading-tight"><?= htmlspecialchars($borrowing['title']) ?></h3>
                <p class="text-gray-400 text-sm"><?= htmlspecialchars($borrowing['author']) ?></p>
                <div class="flex items-center space-x-2">
                  <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 01.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 01-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 01-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 01-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 01.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-yellow-400 text-sm font-medium">4.8</span>
                  </div>
                  <span class="text-gray-500 text-sm"><?= htmlspecialchars($borrowing['publish_date']) ?></span>
                </div>
                <form method="POST" id="returnForm" action="user-return.php">
                  <input type="hidden" name="transaction_id" value="<?= $borrowing['transaction_id'] ?>">
                  <button type="button" class="openReturnModal w-full bg-red-500 hover:bg-red-400 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4" data-transaction-id="<?= $borrowing['transaction_id'] ?>">
                    Return Book
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- MODAL FOR Return-->
    <?php if (file_exists('Frontend/return-modal.php')) include 'Frontend/return-modal.php'; ?>
    <!-- MODAL -->'

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

  <script src="../user-interface/user.js"></script>

  <script>
    // Open Return Modal
    document.querySelectorAll(".openReturnModal").forEach(btn => {
      btn.addEventListener("click", () => {
        const transactionId = btn.dataset.transactionId;
        document.getElementById("returnTransactionId").value = transactionId;
        document.getElementById("openReturnModal").classList.remove("hidden");
      });
    });

    // Close Return Modal
    document.querySelectorAll(".closeReturnModal").forEach(btn => {
      btn.addEventListener("click", () => {
        document.getElementById("openReturnModal").classList.add("hidden");
      });
    });

    // LOADMORE SECTION
    document.addEventListener("DOMContentLoaded", () => {
      const books = document.querySelectorAll("#booksGrid > div"); // Select direct children of #booksGrid
      const totalBooks = books.length;
      const loadMoreBtn = document.getElementById("loadMoreBtn");
      let visibleCount = 8; // Show 6 books initially

      // Hide all books beyond the first 8
      books.forEach((book, index) => {
        if (index >= visibleCount) {
          book.style.display = "none"; // Hide extra books
        }
      });

      // Load more books on button click
      loadMoreBtn.addEventListener("click", () => {
        const nextBatch = Array.from(books).slice(visibleCount, visibleCount + 8);
        nextBatch.forEach((book) => (book.style.display = "")); // Show hidden books
        visibleCount += 8;

        // Hide the "Load More" button if all books are shown
        if (visibleCount >= totalBooks) {
          loadMoreBtn.style.display = "none";
        }
      });
    });
    // LOADMORE SECTION

    // Function to update return date based on selected duration
    function updateReturnDate() {
      const duration = parseInt(document.getElementById("borrowDuration").value);
      const borrowDate = new Date(); // Use current date
      const returnDate = new Date(borrowDate);
      returnDate.setDate(borrowDate.getDate() + duration);
      document.getElementById("returnDate").value = returnDate.toISOString().split("T")[0];
    }
  </script>
</body>

</html>