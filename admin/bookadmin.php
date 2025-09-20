<?php
require_once 'BookController.php';
session_start();

$database = new Database();
$books = $database->getBooks();

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

/* ---------------- CREATE ---------------- */
if (isset($_POST['addBook'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $isbn = $_POST['isbn'];
    $publish_date = $_POST['publish_date'];
    $copies = $_POST['copies'];

    // Handle Image Upload
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    if ($database->createBook($title, $author, $category, $isbn, $publish_date, $copies, $imagePath)) {
        $_SESSION['message'] = "✅ Book added successfully!";
    } else {
        $_SESSION['message'] = "❌ Failed to add book.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* ---------------- UPDATE ---------------- */
if (isset($_POST['updateBook'])) {
    $id = $_POST['edit_id'];
    $title = $_POST['edit_title'];
    $author = $_POST['edit_author'];
    $category = $_POST['edit_category'];
    $isbn = $_POST['edit_isbn'];
    $publish_date = $_POST['edit_publish_date'];
    $copies = $_POST['edit_copies'];

    $imagePath = $_POST['edit_current_image']; // keep old image
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
        $_SESSION['message'] = "✏️ Book updated successfully!";
    } else {
        $_SESSION['message'] = "❌ Failed to update book.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* ---------------- DELETE ---------------- */
if (isset($_POST['delete_id'])) {
    if ($database->deleteBook($_POST['delete_id'])) {
        $_SESSION['message'] = "🗑️ Book deleted successfully!";
    } else {
        $_SESSION['message'] = "❌ Failed to delete book.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Pagination
$limit = 6; // rows per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

$totalBooks = $database->getTotalBooks();
$totalPages = ceil($totalBooks / $limit);

$books = $database->tableBooks($limit, $offset);

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management System - Admin Control</title>
    <meta name="description" content="Admin dashboard for book management system with dark theme interface">
    <link rel="stylesheet" href="../admin/style.css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <a class="nav-link text-light py-3 px-3 active" href="#" style="font-size: 16px;">
                        <i class="bi bi-book me-2"></i>
                        Books
                    </a>
                    <a class="nav-link text-light py-3 px-3" href="../admin/useradmin.php" style="font-size: 16px;">
                        <i class="bi bi-people me-2"></i>
                        Users
                    </a>
                </nav>
            </div>

            <div class="position-absolute bottom-0 w-100 p-4">
                <a href="../login.php" class="text-decoration-none">
                    <button class="btn text-light d-flex align-items-center" style="font-size: 16px;">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Log Out
                    </button></a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center p-4 header-border">
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm text-light d-md-none me-3" id="openSidebar">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <h2 class="text-light mb-0">Book Management</h2>
                </div>
                <div class="d-flex align-items-center">
                    <div class="d-none d-sm-block text-end me-3">
                        <div class="text-light">Pocholo Basuge</div>
                        <small class="text-white opacity-50">Admin</small>
                    </div>
                    <img
                        src="../image/willan.jpg"
                        alt="Profile"
                        class="rounded-circle"
                        width="40"
                        height="40" />
                </div>
            </div>

            <!-- Controls -->
            <div class="p-4 w-100">
                <div class="row mb-4">
                    <div>
                        <?php if ($message): ?>
                            <div style="color: green; padding-bottom: 18px;"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-4 col-md-4 mb-3 mb-md-0">
                        <button type="button" class="btn btn-info d-flex align-items-center w-20 w-md-auto justify-content-center"
                            data-bs-toggle="modal" data-bs-target="#addBookModal">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add Book
                        </button>
                    </div>
                    <!-- input -->
                    <div class="col-12 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Search by ID or Name" />
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead class="text-white font-bold">
                            <tr>
                                <th scope="col">Image</th>
                                <th scope="col">ID</th>
                                <th scope="col">Title</th>
                                <th scope="col" class="d-none d-sm-table-cell">Author</th>
                                <th scope="col">Category</th>
                                <th scope="col" class="d-none d-lg-table-cell">ISBN</th>
                                <th scope="col" class="d-none d-md-table-cell">Publish_date</th>
                                <th scope="col" class="d-none d-lg-table-cell">Copies</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td>
                                        <img src="<?= $book['image'] ?: '../image/default.jpg' ?>"
                                            alt="<?= htmlspecialchars($book['title']) ?>"
                                            class="rounded"
                                            width="80" height="80" />
                                    </td>
                                    <td class="align-middle"><?= $book['id'] ?></td>
                                    <td class="align-middle">
                                        <div>
                                            <div class="fw-bold"><?= htmlspecialchars($book['title']) ?></div>
                                            <small class="opacity-50"><?= htmlspecialchars($book['author']) ?></small>
                                        </div>
                                    </td>
                                    <td class="align-middle d-none d-sm-table-cell"><?= htmlspecialchars($book['author']) ?></td>
                                    <td class="align-middle d-none d-sm-table-cell"><?= htmlspecialchars($book['category']) ?></td>
                                    <td class="align-middle d-none d-lg-table-cell"><?= htmlspecialchars($book['isbn']) ?></td>
                                    <td class="align-middle d-none d-md-table-cell"><?= htmlspecialchars($book['publish_date']) ?></td>
                                    <td class="align-middle d-none d-lg-table-cell"><?= htmlspecialchars($book['copies']) ?></td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-1">
                                            <!-- Edit button -->
                                            <button class="btn btn-sm btn-outline-warning editBtn"
                                                data-id="<?= $book['id'] ?>"
                                                data-title="<?= htmlspecialchars($book['title']) ?>"
                                                data-author="<?= htmlspecialchars($book['author']) ?>"
                                                data-isbn="<?= htmlspecialchars($book['isbn']) ?>"
                                                data-publish_date="<?= htmlspecialchars($book['publish_date']) ?>"
                                                data-copies="<?= htmlspecialchars($book['copies']) ?>"
                                                data-image="<?= $book['image'] ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <!-- Delete -->
                                            <form method="POST" style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this book?');">
                                                <input type="hidden" name="delete_id" value="<?= $book['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                            <!-- View -->
                                            <button class="btn btn-sm btn-outline-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </button>
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


                <!-- Modal -->
                <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="addBookModalLabel">Add New Book</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form method="POST" enctype="multipart/form-data" id="addBookForm">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="author" class="form-label">Author</label>
                                        <input type="text" class="form-control" id="author" name="author" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Roles</label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="Fiction">Fiction</option>
                                            <option value="Science & Technology">Science & Technology</option>
                                            <option value="History & Biography">History & Biography</option>
                                            <option value="Business & Economics">Business & Economics</option>
                                            <option value="Philosophy & Psychology">Philosophy & Psychology</option>
                                            <option value="Arts & Literature">Arts & Literature</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="isbn" class="form-label">ISBN</label>
                                        <input type="text" class="form-control" id="isbn" name="isbn" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="publish_date" class="form-label">Publish Date</label>
                                        <input type="date" class="form-control" id="publish_date" name="publish_date" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="copies" class="form-label">Copies</label>
                                        <input type="number" class="form-control" id="copies" name="copies" min="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Book Image</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    </div>
                                </form>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" form="addBookForm" name="addBook" class="btn btn-primary">Save Book</button>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editBookModal" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" enctype="multipart/form-data" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Book</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="edit_id" name="edit_id">
                                <input type="hidden" id="edit_current_image" name="edit_current_image">

                                <input type="text" id="edit_title" name="edit_title" class="form-control mb-2" required>
                                <input type="text" id="edit_author" name="edit_author" class="form-control mb-2" required>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="edit_category" name="edit_category" required>
                                        <option value="Fiction">Fiction</option>
                                        <option value="Science & Technology">Science & Technology</option>
                                        <option value="History & Biography">History & Biography</option>
                                        <option value="Business & Economics">Business & Economics</option>
                                        <option value="Philosophy & Psychology">Philosophy & Psychology</option>
                                        <option value="Arts & Literature">Arts & Literature</option>
                                    </select>
                                </div>
                                <input type="text" id="edit_isbn" name="edit_isbn" class="form-control mb-2" required>
                                <input type="date" id="edit_publish_date" name="edit_publish_date" class="form-control mb-2" required>
                                <input type="number" id="edit_copies" name="edit_copies" class="form-control mb-2" required>

                                <!-- 📌 Image Preview -->
                                <div class="mb-2">
                                    <img id="edit_preview" src=""
                                        alt="Book Image"
                                        class="img-fluid rounded mb-2"
                                        style="max-height: 150px;">
                                </div>

                                <input type="file" id="edit_image" name="edit_image" class="form-control mb-2">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="updateBook" class="btn btn-success">Update Book</button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Bootstrap JS for mobile sidebar toggle -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                <script src="../admin/script.js"></script>
</body>

</html>