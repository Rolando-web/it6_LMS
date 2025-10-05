<?php
session_start();
require_once '../database.php';
require_once '../auth.php';


$db = new database();
$conn = $db->getConnection();

$auth = new auth($conn);

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
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

$totalusers = $auth->getTotalusers();
$totalPages = ceil($totalusers / $limit);

$users = $auth->getusers($limit, $offset);

if (isset($_POST['delete_id'])) {
  if ($auth->deleteuser($_POST['delete_id'])) {
    $_SESSION['message'] = "User deleted successfully!";
  } else {
    $_SESSION['message'] = "Failed to delete user.";
  }
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// Add users manually 
if (isset($_POST['addUser'])) {
  $auth->Modalregister(
    $_POST['first_name'],
    $_POST['last_name'],
    $_POST['contact'],
    $_POST['email'],
    $_POST['password'],
    $_POST['roles']
  );
}



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
          <h2 class="text-light mb-0 text-3xl">Users Management</h2>
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

      <!-- Controls -->
      <div>
        <div class="row p-4">
          <!-- <div>
              <?php if ($message): ?>
                <div style="color: green; padding-bottom: 18px;"><?php echo htmlspecialchars($message); ?>

              <?php endif; ?>
            </div> -->
          <button
            type="button"
            class="bg-blue-500 text-white flex items-center ml-2 justify-center w-full md:w-auto px-8 py-2 rounded hover:bg-blue-600"
            data-bs-toggle="modal" data-bs-target="#addUserModal">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Book
          </button>
        </div>



        <!-- Table -->
        <div class="table-responsive px-4" style="border-radius: 10px;">
          <table class="table table-dark table-hover align-middle">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Contact</th>
                <th scope="col">Email</th>
                <th scope="col">Password</th>
                <th scope="col">Roles</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($auth->getUsers() as $user): ?>
                <tr>
                  <td><?= htmlspecialchars($user['id']) ?></td>
                  <td><?= htmlspecialchars($user['first_name']) ?></td>
                  <td><?= htmlspecialchars($user['last_name']) ?></td>
                  <td><?= htmlspecialchars($user['contact']) ?></td>
                  <td><?= htmlspecialchars($user['email']) ?></td>
                  <td><?= htmlspecialchars($user['password']) ?></td>
                  <td><?= htmlspecialchars($user['roles']) ?></td>
                  <td>
                    <div class="d-flex flex-wrap gap-2">
                      <form method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="delete_id" value="<?= $user['id'] ?>">
                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>


        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
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

        <!-- Sidebar -->
        <?php if (file_exists('Frontend/user-modal.php')) include 'Frontend/user-modal.php'; ?>
        <!-- Sidebar -->

      </div>
    </div>
  </div>
  <!-- Bootstrap JS for mobile sidebar toggle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../admin/script.js"></script>
  <script src="../admin//active.js"></script>
</body>

</html>