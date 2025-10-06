<?php
session_start();
require '../auth.php';
require '../admin/BookController.php';


$database = new Database();
$auth = new auth($database->pdo);
$database = new Database();
$books = $database->getBooks();
$totalbooks = $database->getTotalBooks();
$totalusers = $auth->getTotalusers();
$totaladmin = $auth->getTotaladmin();

if (isset($_POST['logout'])) {
  $auth->logout();
  header('Location: ../login.php');
  exit;
}


if (!$auth->isLoggedIn()) { // Redirect if NOT logged in
  header('Location: ../login.php');
  exit;
}


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
  <!-- Bootstrap 5 CSS -->
  <title>Home Library</title>
</head>

<body class="bg-gray-900 text-white font-sans w-full">
  <!-- Background -->
  <div class="min-h-screen relative bg-cover bg-center bg-no-repeat" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.pexels.com/photos/481516/pexels-photo-481516.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop');">
    <!-- Header -->
    <header class="relative z-10 px-4 py-4">
      <nav class="flex items-center md:justify-between mx-auto">
        <!-- Logo -->
        <div class="text-xl font-bold flex-1 lg:text-center">
          <span class="text-white">HOME</span><span class="text-gray-300">LIBRARY</span>
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="md:hidden text-white hover:text-gray-300 transition-colors mx-2">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>

        <!-- Navigation Links -->
        <div class="hidden md:flex items-start space-x-6 sm:flex-1 justify-center">
          <a href="./userboard.php" class="flex items-center space-x-1 text-white hover:text-gray-300 transition-colors">
            <div class="w-4 h-4 border border-white"></div>
            <span>Home</span>
          </a>
          <a href="#" class="flex items-center space-x-1 text-gray-300 hover:text-white transition-colors">
            <div class="w-4 h-4 grid grid-cols-2 gap-px">
              <div class="bg-gray-300 w-full h-full"></div>
              <div class="bg-gray-300 w-full h-full"></div>
              <div class="bg-gray-300 w-full h-full"></div>
              <div class="bg-gray-300 w-full h-full"></div>
            </div>
            <span>Category</span>
          </a>
          <a href="./user-borrow.php" class="flex items-center space-x-1 text-gray-300 hover:text-white transition-colors">
            <div class="w-4 h-4 border border-gray-300 relative">
              <div class="absolute inset-1 bg-gray-300"></div>
            </div>
            <span>Books</span>
          </a>
          <a href="../user-interface/user-transaction.php" class="flex items-center space-x-1 text-gray-300 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Transaction</span>
          </a>
        </div>

        <div class="hidden md:flex items-center flex-1 md:justify-end lg:justify-center">
          <!-- Profile with Dropdown -->
          <div class="relative flex items-center space-x-2 md:space-x-2">
            <div class="text-right">
              <div class="text-sm font-medium hidden lg:block">
                <?php
                $user = $auth->user();
                if ($user && isset($user['first_name'], $user['last_name'])) {
                  echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
                } else {
                  echo 'Guest';
                }
                ?>
              </div>

              <div class="text-xs text-gray-400 hidden lg:block">Member</div>
            </div>
            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 flex items-center ">
              <img src="../image/willan.jpg" alt="profile" class="w-full h-full object-cover rounded-full">
            </div>

            <!-- Dropdown Toggle -->
            <button id="dropdownButton" class="ml-2 p-1 rounded-full hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-white">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="dropdownMenu" class="absolute w-20 lg:w-40 rounded-lg shadow-lg bg-gray-800 ring-1 ring-black ring-opacity-5 hidden" style="margin-top: 90px;">
              <div role="menu" aria-orientation="vertical" aria-labelledby="dropdownButton">
                <!-- Logout Link -->
                <form method="POST">
                  <button type="submit" name="logout" class="text-start block px-4 w-20 lg:w-40 py-2 rounded-lg text-sm text-white hover:bg-gray-700">
                    Logout
                  </button>
                </form>
              </div>
            </div>
          </div>

        </div>

        <!-- Mobile Profile (visible on mobile) -->
        <div class="md:hidden flex items-center">
          <div class="w-8 h-8 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 flex items-center justify-center">
            <img src="../image/willan.jpg" alt="profile" class="w-full h-full object-cover rounded-full">
          </div>
        </div>
      </nav>

      <!-- Mobile Menu Overlay -->
      <div id="mobileMenu" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="fixed top-0 right-0 h-full w-80 bg-gray-900 shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out" id="mobileMenuPanel">
          <!-- Mobile Menu Header -->
          <div class="flex items-center justify-between p-6 border-b border-gray-800">
            <div class="text-lg font-bold">
              <span class="text-white">HOME</span><span class="text-gray-300">LIBRARY</span>
            </div>
            <button id="closeMobileMenu" class="text-white hover:text-gray-300 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Mobile Menu Content -->
          <div class="p-6">
            <!-- Profile Section -->
            <div class="flex items-center space-x-3 mb-4 pb-6 border-b border-gray-800">
              <div class="w-12 h-12 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 flex items-center justify-center">
                <img src="../image/willan.jpg" alt="profile" class="w-full h-full object-cover rounded-full">
              </div>
              <div>
                <div class="text-white font-medium">
                  <?php
                  $user = $auth->user();
                  if ($user && isset($user['first_name'], $user['last_name'])) {
                    echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
                  } else {
                    echo 'Guest';
                  }
                  ?></div>
                <div class="text-gray-400 text-sm">Member</div>
              </div>
            </div>
            <div class="p-4">
              <a href="../login.php" class="text-decoration-none">
                <button class="btn text-light d-flex align-items-center" style="font-size: 16px;">
                  <i class="bi bi-box-arrow-right me-2"></i>
                  Log Out
                </button></a>
            </div>

            <!-- Search -->
            <div class="mb-6">
              <div class="relative">
                <input type="text" placeholder="Search title, Author, Isbn" class="w-full bg-gray-800 bg-opacity-50 text-white placeholder-gray-400 px-4 py-3 pr-10 rounded-lg border border-gray-600 focus:border-gray-400 focus:outline-none">
                <svg class="absolute right-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-">
              <a href="./userboard.php" class="flex items-center space-x-3 text-white hover:text-gray-300 transition-colors py-3">
                <div class="w-5 h-5 border border-white"></div>
                <span class="text-lg">Home</span>
              </a>
              <a href="#" class="flex items-center space-x-3 text-gray-300 hover:text-white transition-colors py-3">
                <div class="w-5 h-5 grid grid-cols-2 gap-px">
                  <div class="bg-gray-300 w-full h-full"></div>
                  <div class="bg-gray-300 w-full h-full"></div>
                  <div class="bg-gray-300 w-full h-full"></div>
                  <div class="bg-gray-300 w-full h-full"></div>
                </div>
                <span class="text-lg">Category</span>
              </a>
              <a href="./user-borrow.php" class="flex items-center space-x-3 text-gray-300 hover:text-white transition-colors py-3">
                <div class="w-5 h-5 border border-gray-300 relative">
                  <div class="absolute inset-1 bg-gray-300"></div>
                </div>
                <span class="text-lg">Books</span>
              </a>
              <a href="../user-interface/user-transaction.php" class="flex items-center space-x-3 text-gray-300 hover:text-white transition-colors py-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-lg">Transaction</span>
              </a>
            </nav>
          </div>
        </div>
      </div>
    </header>

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

        <button class="bg-white text-gray-900 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors inline-flex items-center space-x-2 group">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
          <span>Book Collection</span>
          <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
          </svg>
        </button>
      </div>
    </main>

    <!-- Statistics -->
    <div class="relative z-0 px-6 py-12">
      <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Books Available -->
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
  <!-- category file -->
  <?php if (file_exists('Frontend/category.php')) include 'Frontend/category.php'; ?>
  <!-- Footer file -->
  <?php if (file_exists('Frontend/footer.php')) include 'Frontend/footer.php'; ?>
  <!-- PARA DI GUBOT -->
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="../user-interface//user.js"></script>
</body>

</html>