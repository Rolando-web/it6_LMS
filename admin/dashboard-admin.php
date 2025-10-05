<?php
session_start();
require '../admin/backend/BookController.php';
require '../admin/backend/transactionControll.php';
require '../auth.php';

$database = new Database();
$library = new Library($database->pdo);
$auth = new auth($database->pdo);
$books = $database->getBooks();

$totalcategory = $library->getCountcategory();
$totalunique = count($totalcategory);

$totalPossibleCategories = 50;
$percentageCategories = ($totalunique / $totalPossibleCategories) * 100;

$totallogs = $library->countActivityLogs();
$totallogstarget = 100;
$percentagelogs = ($totallogs / $totallogstarget) * 100;


$totalusers = $auth->getTotalusers();
$targetusers = 100;
$percentageUsers = ($totalusers / $targetusers) * 100;


if (isset($_POST['logout'])) {
  $auth->logout();
  header('Location: ../login.php');
  exit;
}
if (!$auth->isLoggedIn()) {
  header('Location: ../login.php');
  exit;
}

// UPDATE 
if (isset($_POST['updateBook'])) {
  $id = $_POST['edit_id'];
  $title = $_POST['edit_title'];
  $author = $_POST['edit_author'];
  $category = $_POST['edit_category'];
  $isbn = $_POST['edit_isbn'];
  $publish_date = $_POST['edit_publish_date'];
  $copies = $_POST['edit_copies'];

  $imagePath = $_POST['edit_current_image'];
  if (!empty($_FILES['edit_image']['name'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $fileName = time() . "_" . basename($_FILES["edit_image"]["name"]);
    $targetFile = $targetDir . $fileName;
    if (move_uploaded_file($_FILES["edit_image"]["tmp_name"], $targetFile)) {
      $imagePath = $targetFile;
    }
  }

  if ($database->updateBook($id, $title, $author, $category, $isbn, $publish_date, $copies, $imagePath)) {
    $_SESSION['message'] = "Book updated successfully!";
  } else {
    $_SESSION['message'] = "Failed to update book.";
  }
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}



// Pagination
$limit = 3; // rows per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

$totalBooks = $database->getTotalBooks();
$targetbooks = 100;
$percentagebooks = ($totalBooks / $targetbooks) * 100;
$totalPages = ceil($totalBooks / $limit);

$books = $database->tableBooks($limit, $offset);

// Recently added books
$recentBooks = $database->getRecentlyAddedBooks();


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Management System - Admin Control</title>
  <meta name="description" content="Admin dashboard for book management system with dark theme interface">
  <link rel="stylesheet" href="../admin/style.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="../image/willan.jpg" type="image/jpeg">
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
          <h2 class="text-light mb-0 text-3xl">Dashboard</h2>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <!-- Right: Profile Info -->
          <div class="d-flex align-items-center">
            <!-- Desktop View -->
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

      <!-- Main Content -->
      <main class="flex-1 overflow-y-auto">
        <!-- Dashboard Content -->
        <div class="px-8 py-6">
          <!-- Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                  </svg>
                </div>
                <span class="badge px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full"><?php echo number_format($percentagebooks, 2) . "%"; ?></span>
              </div>
              <p class="text-2xl font-bold text-gray-800 mb-1"><?php echo $totalBooks ?></p>
              <p class="text-sm text-gray-500 font-medium">Total Books</p>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                  </svg>
                </div>
                <span class="badge px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full"><?php echo number_format($percentageCategories, 2) . "%"; ?></span>
              </div>
              <p class="text-2xl font-bold text-gray-800 mb-1"><?php echo $totalunique ?></p>
              <p class="text-sm text-gray-500 font-medium">Categories</p>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                  </svg>
                </div>
                <span class="badge px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full"><?php echo number_format($percentageUsers, 2) . "%"; ?></span>
              </div>
              <p class="text-2xl font-bold text-gray-800 mb-1"><?php echo $totalusers ?></p>
              <p class="text-sm text-gray-500 font-medium">Active Users</p>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                  </svg>
                </div>
                <span class="badge px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full"><?php echo number_format($percentagelogs, 2) . "%"; ?></span>
              </div>
              <p class="text-2xl font-bold text-gray-800 mb-1"><?php echo $totallogs ?></p>
              <p class="text-sm text-gray-500 font-medium">Daily log Activities</p>
            </div>
          </div>

          <!-- Books Table -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Recently Books Added</h3>

              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Book</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Copies</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Creted At</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <?php foreach ($books as $book): ?>
                    <tr class="table-row">
                      <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                          <div class="w-12 h-16  rounded flex-shrink-0 flex items-center justify-center text-white text-xs font-bold">
                            <img src="<?= $book['image'] ?: '../image/default.jpg' ?>"
                              alt="<?= htmlspecialchars($book['title']) ?>"
                              class="rounded"
                              width="80" height="80" />
                          </div>
                          <div>
                            <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($book['title']) ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($book['isbn']) ?></p>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full"><?= htmlspecialchars($book['category']) ?></span>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($book['author']) ?></td>
                      <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($book['copies']) ?></td>
                      <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($book['created_at']) ?></td>
                      <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                          <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg editBtn" title="Edit"
                            data-id="<?= $book['id'] ?>"
                            data-title="<?= htmlspecialchars($book['title']) ?>"
                            data-author="<?= htmlspecialchars($book['author']) ?>"
                            data-isbn="<?= htmlspecialchars($book['isbn']) ?>"
                            data-publish_date="<?= htmlspecialchars($book['publish_date']) ?>"
                            data-copies="<?= htmlspecialchars($book['copies']) ?>"
                            data-image="<?= $book['image'] ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center my-4">
              <nav aria-label="Book pagination">
                <ul class="pagination">
                  <!-- Prev Button -->
                  <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                      &laquo;
                    </a>
                  </li>

                  <!-- Page Numbers -->
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                      <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                  <?php endfor; ?>

                  <!-- Next Button -->
                  <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                      &raquo;
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </main>

      <!-- Sidebar -->
      <?php if (file_exists('Frontend/book-modal.php')) include 'Frontend/book-modal.php'; ?>
      <!-- Sidebar -->

      <!-- Bootstrap JS Bundle -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

      <script src="../admin/script.js"></script>
      <script src="../admin//active.js"></script>
</body>

</html>