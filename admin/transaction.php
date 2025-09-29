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
$limit = 15;
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

    <!-- Sidebar -->
    <?php if (file_exists('Frontend/sidebar.php')) include 'Frontend/sidebar.php'; ?>
    <!-- Sidebar -->


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
    <script src="../admin//active.js"></script>
</body>

</html>