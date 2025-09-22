<?php
session_start();
require '../auth.php';
require '../admin/BookController.php';
require '../admin/transactionControll.php';


$database = new Database();
$books = $database->getBooks();
$library = new Library($database->pdo);

$auth = new auth($database);

if (!$auth->isLoggedIn()) { // Redirect if NOT logged in
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
  <!-- Header -->
  <header class="bg-gray-900 border-b border-gray-800 px-4 py-3">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-4">

      <!-- Logo -->
      <div class="flex-shrink-0 text-xl font-bold">
        <a href="./userboard.php" class="hover:text-gray-300 transition-colors">
          <span class="text-white">HOME</span><span class="text-gray-300">LIBRARY</span>
        </a>
      </div>

      <!-- Navigation Links (≥769px) -->
      <nav class="hidden md:flex flex-grow justify-center gap-8">
        <a href="./user-home.php" class="text-gray-300 hover:text-white">Home</a>
        <a href="#" class="text-gray-300 hover:text-white">Category</a>
        <a href="./user-borrow.php" class="text-white hover:text-gray-300">Books</a>
        <a href="#" class="text-gray-300 hover:text-white">Suggest</a>
      </nav>

      <!-- Search + Profile -->
      <div class="flex items-center gap-6">
        <!-- Search -->
        <div class="relative hidden sm:block">
          <input type="text" placeholder="Search title, Author, ISBN"
            class="bg-gray-800 text-white placeholder-gray-400 px-4 py-2 pr-10 rounded-lg border border-gray-600 focus:outline-none w-64">
          <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>

        <!-- Profile -->
        <div class="flex items-center gap-3">
          <div class="text-right">
            <div class="text-sm font-medium text-white">Rolando Luayon</div>
            <div class="text-xs text-gray-400">Member</div>
          </div>
          <div class="w-10 h-10 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 flex items-center justify-center">
            <span class="text-white font-semibold text-sm">RL</span>
          </div>
        </div>
      </div>

      <!-- Mobile Menu Toggle (≤768px) -->
      <button class="md:hidden text-gray-300 hover:text-white ml-auto" id="mobileToggle">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden hidden flex flex-col space-y-2 px-4 pt-4 pb-2 bg-gray-800 text-gray-300">
      <a href="./user-home.php" class="hover:text-white">Home</a>
      <a href="#" class="hover:text-white">Category</a>
      <a href="books.html" class="hover:text-white">Books</a>
      <a href="#" class="hover:text-white">Suggest</a>
      <div class="pt-2 border-t border-gray-700">
        <div class="text-sm font-medium">Rolando Luayon</div>
        <div class="text-xs text-gray-400">Member</div>
      </div>
    </div>
  </header>



  <!-- Main Content -->
  <main class="max-w-7xl mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-4xl font-light text-white mb-2">Book Collection</h1>
      <p class="text-gray-400 text-lg">Discover and borrow from our extensive collection of books</p>
    </div>

    <!-- Filters and Controls -->
    <div class="bg-gray-800 rounded-xl p-6 mb-8">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
        <!-- Category Filter -->
        <div class="flex flex-wrap gap-2">
          <button class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-white text-gray-900" data-category="all">
            All Books
          </button>
          <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="fiction">
            Fiction
          </button>
          <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="science">
            Science & Technology
          </button>
          <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="history">
            History & Biography
          </button>
          <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="business">
            Business & Economics
          </button>
          <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="philosophy">
            Philosophy & Psychology
          </button>
          <button class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-700 text-gray-300 hover:bg-gray-600" data-category="arts">
            Arts & Literature
          </button>
        </div>

        <!-- Sort Options -->
        <div class="flex items-center space-x-4">
          <label class="text-gray-400 text-sm">Sort by:</label>
          <select id="sortSelect" class="bg-gray-700 text-white px-3 py-2 rounded-lg text-sm border border-gray-600 focus:border-gray-400 focus:outline-none">
            <option value="title">Title A-Z</option>
            <option value="author">Author A-Z</option>
            <option value="year">Publication Year</option>
            <option value="rating">Rating</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Books Grid -->
    <div id="booksGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
      <!-- PHP PHP PHP PHP LOOP -->
      <?php foreach ($books as $book): ?>
        <div class="bg-gray-800 rounded-xl p-2 hover:bg-gray-750 transition-colors group">
          <div class="swiper-slide">
            <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
              <div class="mb-4">
                <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden">
                  <img src="../admin/<?= $book['image'] ?: 'uploads/default.jpg' ?>"
                    alt="<?= htmlspecialchars($book['title']) ?>" class="w-full h-full object-cover rounded-lg">
                </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-white font-medium text-lg leading-tight"><?= htmlspecialchars($book['title']) ?></h3>
                <p class="text-gray-400 text-sm"><?= htmlspecialchars($book['author']) ?></p>
                <div class="flex items-center space-x-2">
                  <div class="flex items-center space-x-1"> <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg> <span class="text-yellow-400 text-sm font-medium">4.8</span> </div> <span class="text-gray-500 text-sm"><?= htmlspecialchars($book['publish_date']) ?></span>
                </div>
                <form method="POST">
                  <input type="hidden" name="user_id" value="<?= isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : '' ?>">
                  <input type="hidden" name="book_id" value="<?= (int)$book['id'] ?>">

                  <button type="button"
                    class="openBorrowModal w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4"
                    data-book-id="<?= $book['id'] ?>"
                    data-book-title="<?= htmlspecialchars($book['title']) ?>"
                    data-book-author="<?= htmlspecialchars($book['author']) ?>">
                    Borrow Book
                  </button>
                </form>

              </div>
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

  <!-- Borrow Modal -->
  <div id="borrowModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-xl p-8 max-w-md w-full mx-4">
      <div class="text-center mb-6">
        <div class="w-16 h-16 bg-green-600 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">Borrow Book</h3>
        <p class="text-gray-400 mb-4">You're about to borrow:</p>
        <p id="borrowBookTitle" class="text-white font-medium text-lg mb-2"></p>
        <p id="borrowBookAuthor" class="text-gray-400 mb-6"></p>
      </div>

      <div class="space-y-4 mb-6">
        <div>
          <label class="block text-gray-400 text-sm mb-2">Borrow Duration</label>
          <select id="borrowDuration" class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:border-gray-400 focus:outline-none">
            <option value="7">7 days</option>
            <option value="14" selected>14 days</option>
            <option value="21">21 days</option>
            <option value="30">30 days</option>
          </select>
        </div>
        <div>
          <label class="block text-gray-400 text-sm mb-2">Return Date</label>
          <input type="date" id="returnDate" class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:border-gray-400 focus:outline-none" readonly>
        </div>
      </div>

      <div class="flex space-x-4">
        <button id="cancelBorrow" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 px-4 rounded-lg font-medium transition-colors">
          Cancel
        </button>
        <button id="confirmBorrow" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
          Confirm Borrow
        </button>
      </div>
    </div>
  </div>

  <!-- Success Modal -->
  <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-5">
    <div class="bg-gray-800 rounded-xl p-8 max-w-md w-full mx-4">
      <div class="text-center">
        <div class="w-16 h-16 bg-green-600 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">Book Borrowed Successfully!</h3>
        <p class="text-gray-400 mb-6">You can find your borrowed books in your account dashboard.</p>
        <button onclick="" id="closeSuccess" class="bg-white text-gray-900 px-6 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
          Close
        </button>
      </div>
    </div>
  </div>
  <script src="../user-interface/user.js"></script>
  <script>
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
          // Hide borrow modal
          document.getElementById("borrowModal").classList.add("hidden");

          // Show success modal
          document.getElementById("successModal").classList.remove("hidden");

          // Optionally insert message
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