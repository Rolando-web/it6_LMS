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