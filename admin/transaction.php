<?php
session_start();
require '../admin/BookController.php';
require '../admin/transactionControll.php';
require '../auth.php';

$db = new Database();
$library = new Library($db->pdo);
$auth = new auth($db);

if (isset($_POST['logout'])) {
  $auth->logout();
  header('Location: ../login.php');
  exit;
}
if (!$auth->isLoggedIn()) { // Redirect if NOT logged in
  header('Location: ../login.php');
  exit;
}

// Pagination
$limit = 10;
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
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <div class="d-flex min-vh-100">
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar bg-dark text-light p-0" id="sidebar">
      <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0 fw-bold text-light">
            ADMIN<span class="font-light">CONTROL</span>
          </h4>
          <button class="btn btn-sm d-md-none text-light" id="closeSidebar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <nav class="nav flex-column">
          <a class="nav-link text-light py-3 px-3" href="#" style="font-size: 16px;">
            <i class="bi bi-speedometer2 me-2"></i>
            Dashboard
          </a>
          <a class="nav-link text-light py-3 px-3" href="#" style="font-size: 16px;">
            <i class="bi bi-collection me-2"></i>
            Category
          </a>
          <a class="nav-link text-light py-3 px-3" href="../admin/bookadmin.php" style="font-size: 16px;">
            <i class="bi bi-book me-2"></i>
            Books
          </a>
          <a class="nav-link text-light py-3 px-3 active" href="../admin/transaction.php" style="font-size: 16px;">
            <i class="bi bi-book me-2"></i>
            Transaction
          </a>
          <a class="nav-link text-light py-3 px-3" href="../admin/useradmin.php" style="font-size: 16px;">
            <i class="bi bi-people me-2"></i>
            Users
          </a>
        </nav>
      </div>

      <form method="POST">
        <div class="position-absolute bottom-0 w-100 p-4">
          <a href="../login.php" class="text-decoration-none">
            <button class="btn text-light d-flex align-items-center" name="logout" style="font-size: 16px;">
              <i class="bi bi-box-arrow-right me-2"></i>
              Log Out
            </button></a>
        </div>
      </form>
    </div>

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
      <!-- Header -->
      <div class="d-flex justify-content-between md-justify-content-around align-items-center p-4 header-border">
        <div class="d-flex align-items-center">
          <button class="btn btn-sm text-light d-md-none me-3" id="openSidebar">
            <i class="bi bi-list fs-4"></i>
          </button>
          <h2 class="text-light mb-0 text-sm">Transaction Management</h2>
        </div>
        <div class="d-flex align-items-center">
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
      </div>


      <!-- Table -->
      <div class="table-responsive m-4" style="border-radius: 10px;">
        <table class="table table-dark table-hover align-middle">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">User_id</th>
              <th scope="col">Book_id</th>
              <th scope="col">Borrow_date</th>
              <th scope="col">Due_date</th>
              <th scope="col">Return_date</th>
              <th scope="col">Overdue_date</th>
              <th scope="col">Fee</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($library->getTransactions($limit, $offset) as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['transaction_id']) ?></td>
                <td><?= htmlspecialchars($row['user_id']) ?></td>
                <td><?= htmlspecialchars($row['book_id']) ?></td>
                <td><?= htmlspecialchars($row['borrow_date']) ?></td>
                <td><?= htmlspecialchars($row['due_date'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['return_date'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['overdue_days']) ?></td>
                <td><?= htmlspecialchars($row['fee']) ?></td>
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


      <!-- Modal -->
      <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
              <form method="POST" id="addUserForm">
                <div class="mb-3">
                  <label for="first_name" class="form-label">First Name</label>
                  <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="mb-3">
                  <label for="last_name" class="form-label">Last Name</label>
                  <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="mb-3">
                  <label for="contact" class="form-label">Contact</label>
                  <input type="text" class="form-control" id="contact" name="contact" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                  <label for="roles" class="form-label">Roles</label>
                  <select class="form-select" id="roles" name="roles" required>
                    <option value="Users">Users</option>
                    <option value="Admin">Admin</option>
                  </select>
                </div>
              </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" form="addUserForm" name="addUser" class="btn btn-primary">Register User</button>
            </div>

          </div>
        </div>
      </div>



      <!-- Bootstrap JS for mobile sidebar toggle -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

      <script src="../admin/script.js"></script>
</body>

</html>