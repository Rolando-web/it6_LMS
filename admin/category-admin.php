<?php
session_start();
require '../admin/backend/BookController.php';
require '../admin/backend/transactionControll.php';
require '../auth.php';

$db = new Database();
$library = new Library($db->pdo);
$auth = new auth($db);

if (isset($_POST['logout'])) {
  $auth->logout();
  header('Location: ../login.php');
  exit;
}
if (!$auth->isLoggedIn()) {
  header('Location: ../login.php');
  exit;
}

// Pagination
$limit = 14;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

// ✅ Count total transactions
$totalusers = $library->countTransactions();
$totalPages = ceil($totalusers / $limit);

// ✅ Fetch only current page transactions
$users = $library->getTransactions($limit, $offset);



?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Management System - Admin Control</title>
  <meta name="description" content="Admin dashboard for book management system with dark theme interface">
  <link rel="stylesheet" href="../admin/style.css" />
  <link rel="icon" href="../image/willan.jpg" type="image/jpeg">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            background: 'hsl(35, 25%, 97%)',
            foreground: 'hsl(25, 15%, 15%)',
            card: 'hsl(0, 0%, 100%)',
            border: 'hsl(35, 25%, 88%)',
            muted: 'hsl(35, 15%, 65%)',
          }
        }
      }
    }
  </script>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <div class="d-flex min-vh-100">

    <!-- Sidebar -->
    <?php if (file_exists('Frontend/sidebar.php')) include 'Frontend/sidebar.php'; ?>
    <!-- Sidebar -->

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center p-4 header-border">
        <div class="d-flex align-items-center">
          <button class="btn btn-sm text-light d-md-none me-3" id="openSidebar">
            <i class="bi bi-list fs-4"></i>
          </button>
          <h2 class="font-semibold mb-0 text-3xl text-white">List of Category</h2>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <div class="d-none d-sm-block text-end me-3">
              <div class="text-light">
                <?php
                $user = $auth->user();
                if ($user && isset($user['first_name'], $user['last_name'])) {
                  echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
                } else {
                  echo 'Guest';
                }
                ?>
              </div>
              <small class="text-white opacity-50">Admin</small>
            </div>

            <!-- Mobile Dropdown -->
            <div class="dropdown d-sm-none">
              <a
                href="#"
                class="d-flex align-items-center"
                id="profileDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <img
                  src="../image/willan.jpg"
                  alt="Profile"
                  class="rounded-circle"
                  width="40"
                  height="40" />
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><span class="dropdown-item-text fw-bold">
                    <?php
                    $user = $auth->user();
                    if ($user && isset($user['first_name'], $user['last_name'])) {
                      echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
                    } else {
                      echo 'Guest';
                    }
                    ?>
                  </span></li>
                <li><span class="dropdown-item-text text-muted">Admin</span></li>
              </ul>
            </div>

            <!-- Desktop Profile Image -->
            <img
              src="../image/willan.jpg"
              alt="Profile"
              class="rounded-circle d-none d-sm-block"
              width="40"
              height="40" />
          </div>
        </div>
      </div>

      <!-- Category -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 m-8">
        <!-- Fiction Card -->
        <div class="group relative overflow-hidden cursor-pointer rounded-lg border border-border bg-card transition-all duration-300 hover:scale-[1.02] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.15)]">
          <div class="aspect-[3/4] overflow-hidden">
            <img src="../image/category/fiction.jpg" alt="Fiction category" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
            <h3 class="text-2xl font-bold text-white mb-1">Fiction</h3>
            <p class="text-sm text-gray-300">1234 books</p>
          </div>
        </div>

        <!-- philosophy Card -->
        <div class="group relative overflow-hidden cursor-pointer rounded-lg border border-border bg-card transition-all duration-300 hover:scale-[1.02] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.15)]">
          <div class="aspect-[3/4] overflow-hidden">
            <img src="../image/category/philosophy.jpg" alt="philosophy category" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
            <h3 class="text-2xl font-bold text-white mb-1">Philosophy</h3>
            <p class="text-sm text-gray-300">1234 books</p>
          </div>
        </div>

        <!-- technology Card -->
        <div class="group relative overflow-hidden cursor-pointer rounded-lg border border-border bg-card transition-all duration-300 hover:scale-[1.02] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.15)]">
          <div class="aspect-[3/4] overflow-hidden">
            <img src="../image/category/technology.jpg" alt="technology category" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
            <h3 class="text-2xl font-bold text-white mb-1">Technology</h3>
            <p class="text-sm text-gray-300">1234 books</p>
          </div>
        </div>

        <!-- arts Card -->
        <div class="group relative overflow-hidden cursor-pointer rounded-lg border border-border bg-card transition-all duration-300 hover:scale-[1.02] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.15)]">
          <div class="aspect-[3/4] overflow-hidden">
            <img src="../image/category/arts.jpg" alt="arts category" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
            <h3 class="text-2xl font-bold text-white mb-1">Arts</h3>
            <p class="text-sm text-gray-300">1234 books</p>
          </div>
        </div>

        <!-- history Card -->
        <div class="group relative overflow-hidden cursor-pointer rounded-lg border border-border bg-card transition-all duration-300 hover:scale-[1.02] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.15)]">
          <div class="aspect-[3/4] overflow-hidden">
            <img src="../image/category/history.jpg" alt="history category" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
            <h3 class="text-2xl font-bold text-white mb-1">History</h3>
            <p class="text-sm text-gray-300">1234 books</p>
          </div>
        </div>

        <!-- business Card -->
        <div class="group relative overflow-hidden cursor-pointer rounded-lg border border-border bg-card transition-all duration-300 hover:scale-[1.02] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.15)]">
          <div class="aspect-[3/4] overflow-hidden">
            <img src="../image/category/business.png" alt="business category" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
            <h3 class="text-2xl font-bold text-white mb-1">Business</h3>
            <p class="text-sm text-gray-300">1234 books</p>
          </div>
        </div>

        <!-- Fiction Card -->
        <div class="group relative overflow-hidden cursor-pointer rounded-lg border border-border bg-card transition-all duration-300 hover:scale-[1.02] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.15)]">
          <div class="aspect-[3/4] overflow-hidden">
            <img src="../image/category/fiction.jpg" alt="Fiction category" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
            <h3 class="text-2xl font-bold text-white mb-1">Biology</h3>
            <p class="text-sm text-gray-300">1234 books</p>
          </div>
        </div>

        <!-- Fiction Card -->
        <div class="group relative overflow-hidden cursor-pointer rounded-lg border border-border bg-card transition-all duration-300 hover:scale-[1.02] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.15)]">
          <div class="aspect-[3/4] overflow-hidden">
            <img src="../image/category/fiction.jpg" alt="Fiction category" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
            <h3 class="text-2xl font-bold text-white mb-1">Science</h3>
            <p class="text-sm text-gray-300">1234 books</p>
          </div>
        </div>


      </div>



      <!-- Bootstrap JS for mobile sidebar toggle -->
      <!-- Bootstrap JS Bundle -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

      <script src="../admin/script.js"></script>
      <script src="../admin//active.js"></script>
</body>

</html>