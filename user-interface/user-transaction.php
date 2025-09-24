<?php session_start();
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

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Transaction</title>
  <link rel="stylesheet" href="../src/input.css" />
  <link rel="stylesheet" href="../src/output.css" />
  <link rel="icon" href="../image/willan.jpg" type="image/jpeg">
  <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="min-h-screen bg-[#101929]">
  <div class="container mx-auto">
    <!-- Header -->
    <header class="relative z-10 px-4 py-4 mb-10 ">
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
        <div class="hidden md:flex items-start space-x-4 sm:flex-1 justify-center">
          <a href="../user-interface/user-home.php" class="flex items-center space-x-1 text-white hover:text-gray-300 transition-colors">
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
          <a href="./user-transaction.php" class="flex items-center space-x-1 text-gray-300 hover:text-white transition-colors">
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
              <a href="../user-interface/user-home.php" class="flex items-center space-x-3 text-white hover:text-gray-300 transition-colors py-3">
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
              <a href="#" class="flex items-center space-x-3 text-gray-300 hover:text-white transition-colors py-3">
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

    <!-- Stats Overview -->
    <div class="mb-8">
      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <!-- Active Borrowings -->
        <div class="bg-[#fffefe] rounded-lg border p-6">
          <div class="flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="text-sm font-medium">Active Borrowings</h3>
            <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          <div class="flex items-center justify-between">
            <div class="text-2xl font-bold">3</div>
          </div>
          <p class="text-xs text-muted-foreground mt-1">Currently borrowed books</p>
        </div>

        <!-- Overdue Books -->
        <div class="bg-[#fffefe] rounded-lg border p-6">
          <div class="flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="text-sm font-medium">Overdue Books</h3>
            <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <div class="flex items-center justify-between">
            <div class="text-2xl font-bold">1</div>
            <span class="badge-danger ml-2">Alert</span>
          </div>
          <p class="text-xs text-muted-foreground mt-1">Books past due date</p>
        </div>

        <!-- Outstanding Fees -->
        <div class="bg-[#fffefe] rounded-lg border p-6">
          <div class="flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="text-sm font-medium">Outstanding Fees</h3>
            <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
          </div>
          <div class="flex items-center justify-between">
            <div class="text-2xl font-bold">$5.00</div>
            <span class="badge-warning ml-2">Good</span>
          </div>
          <p class="text-xs text-muted-foreground mt-1">Fees for active borrowings</p>
        </div>

        <!-- Total Transactions -->
        <div class="bg-[#fffefe] rounded-lg border p-6">
          <div class="flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="text-sm font-medium">Total Transactions</h3>
            <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div class="flex items-center justify-between">
            <div class="text-2xl font-bold">7</div>
          </div>
          <p class="text-xs text-muted-foreground mt-1">All-time borrowings</p>
        </div>
      </div>
    </div>

    <!-- Transaction Tables -->
    <div class="space-y-6">
      <!-- Active Transactions -->
      <div class="bg-[#fffefe] rounded-lg border">
        <div class="p-6 border-b">
          <h3 class="text-lg font-semibold flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Active Borrowings (3)
          </h3>
        </div>
        <div class="p-6">
          <div class="rounded-md border overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="bg-table-header hover:bg-table-header border-b">
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Transaction ID</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Book ID</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Title</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Author</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Borrowed Date</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Due Date</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Status</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Fee</th>
                </tr>
              </thead>
              <tbody>
                <tr class="bg-table-row-even hover:bg-table-row-hover transition-colors border-b">
                  <td class="p-4 font-mono text-sm">TXN-001</td>
                  <td class="p-4 font-mono text-sm">BK-2024-001</td>
                  <td class="p-4 font-medium">The Great Gatsby</td>
                  <td class="p-4 text-muted-foreground">F. Scott Fitzgerald</td>
                  <td class="p-4">Dec 18, 2024</td>
                  <td class="p-4 font-medium">Dec 21, 2024</td>
                  <td class="p-4">
                    <span class="badge-excellent">Day 1 - Excellent</span>
                  </td>
                  <td class="p-4">
                    <div class="flex items-center gap-1">
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                      </svg>
                      <span class="text-muted-foreground">0.00</span>
                    </div>
                  </td>
                </tr>
                <tr class="bg-[#fffefe] hover:bg-table-row-hover transition-colors border-b">
                  <td class="p-4 font-mono text-sm">TXN-002</td>
                  <td class="p-4 font-mono text-sm">BK-2024-002</td>
                  <td class="p-4 font-medium">To Kill a Mockingbird</td>
                  <td class="p-4 text-muted-foreground">Harper Lee</td>
                  <td class="p-4">Dec 17, 2024</td>
                  <td class="p-4 font-medium">Dec 20, 2024</td>
                  <td class="p-4">
                    <span class="badge-good">Day 2 - Good</span>
                  </td>
                  <td class="p-4">
                    <div class="flex items-center gap-1">
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                      </svg>
                      <span class="text-muted-foreground">0.00</span>
                    </div>
                  </td>
                </tr>
                <tr class="bg-table-row-even hover:bg-table-row-hover transition-colors border-b">
                  <td class="p-4 font-mono text-sm">TXN-003</td>
                  <td class="p-4 font-mono text-sm">BK-2024-003</td>
                  <td class="p-4 font-medium">1984</td>
                  <td class="p-4 text-muted-foreground">George Orwell</td>
                  <td class="p-4">Dec 15, 2024</td>
                  <td class="p-4 font-medium">Dec 18, 2024</td>
                  <td class="p-4">
                    <span class="badge-danger">Overdue</span>
                  </td>
                  <td class="p-4">
                    <div class="flex items-center gap-1">
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                      </svg>
                      <span class="text-status-danger font-semibold">5.00</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Transaction History -->
      <div class="bg-[#fffefe] rounded-lg border">
        <div class="p-6 border-b">
          <h3 class="text-lg font-semibold flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Transaction History (4)
          </h3>
        </div>
        <div class="p-6">
          <div class="rounded-md border overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="bg-table-header hover:bg-table-header border-b">
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Transaction ID</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Book ID</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Title</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Author</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Borrowed Date</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Return Date</th>
                  <th class="text-left p-4 text-table-header-foreground font-semibold">Final Fee</th>
                </tr>
              </thead>
              <tbody>
                <tr class="bg-table-row-even hover:bg-table-row-hover transition-colors border-b">
                  <td class="p-4 font-mono text-sm">TXN-004</td>
                  <td class="p-4 font-mono text-sm">BK-2024-004</td>
                  <td class="p-4 font-medium">Pride and Prejudice</td>
                  <td class="p-4 text-muted-foreground">Jane Austen</td>
                  <td class="p-4">Dec 10, 2024</td>
                  <td class="p-4 font-medium">Dec 13, 2024</td>
                  <td class="p-4">
                    <div class="flex items-center gap-1">
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                      </svg>
                      <span class="text-muted-foreground">0.00</span>
                    </div>
                  </td>
                </tr>
                <tr class="bg-[#fffefe] hover:bg-table-row-hover transition-colors border-b">
                  <td class="p-4 font-mono text-sm">TXN-005</td>
                  <td class="p-4 font-mono text-sm">BK-2024-005</td>
                  <td class="p-4 font-medium">The Catcher in the Rye</td>
                  <td class="p-4 text-muted-foreground">J.D. Salinger</td>
                  <td class="p-4">Dec 5, 2024</td>
                  <td class="p-4 font-medium">Dec 9, 2024</td>
                  <td class="p-4">
                    <div class="flex items-center gap-1">
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                      </svg>
                      <span class="text-status-danger font-semibold">2.50</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../user-interface//user.js"></script>
</body>

</html>