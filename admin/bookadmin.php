<?php
require '../admin/backend/BookController.php';
require '../admin/backend/transactionControll.php';
require '../auth.php';
session_start();

$database = new Database();
$auth = new auth($database);


if (isset($_POST['logout'])) {
    $auth->logout();
    header('Location: ../login.php');
    exit;
}

// if wala kay account dika ka login
if (!$auth->isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// CREATE
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
        $_SESSION['message'] = "Book added successfully!";
    } else {
        $_SESSION['message'] = "Failed to add book.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
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

// DELETE
if (isset($_POST['delete_id'])) {
    if ($database->deleteBook($_POST['delete_id'])) {
        $_SESSION['message'] = "Book deleted successfully!";
    } else {
        $_SESSION['message'] = "Failed to delete book.";
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

$bookss = $database->tableBooks($limit, $offset);

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'all';

$books = $database->getFilteredBooks($category, $search);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <h2 class="text-light mb-0 text-3xl">Book Management</h2>
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

            <!-- Controls -->
            <div class="p-4 w-100">
                <div class="flex flex-col md:flex-row mb-4">
                    <div>
                        <?php if ($message): ?>
                            <div class="text-green-600 mb-4"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="w-full lg:w-[25%] mb-3 md:mb-0 md:px-2">
                        <button
                            type="button"
                            class="bg-blue-500 text-white flex items-center justify-center w-full md:w-auto px-8 py-2 rounded hover:bg-blue-600"
                            data-bs-toggle="modal"
                            data-bs-target="#addBookModal">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Book
                        </button>
                    </div>
                    <div class="w-full md:w-25 lg:w-20">
                        <form method="GET" action="<?= $_SERVER['PHP_SELF'] ?>" class="flex">
                            <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
                            <input
                                type="text"
                                name="search"
                                id="searchInput"
                                value="<?= htmlspecialchars($search) ?>"
                                placeholder="Search by Title, ID"
                                class="flex-1 px-3 py-2 rounded-l border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r hover:bg-blue-600">
                                Search
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive" style="border-radius: 10px;">
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
                            <?php if (!empty($bookss)): ?>
                                <?php foreach ($bookss as $book): ?>
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
                                                <!-- Delete -->
                                                <form method="POST" style="display:inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this book?');">
                                                    <input type="hidden" name="delete_id" value="<?= $book['id'] ?>">
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
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-red-500">No books found.</td>
                                </tr>
                            <?php endif; ?>
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
                <?php if (file_exists('Frontend/book-modal.php')) include 'Frontend/book-modal.php'; ?>
                <!-- Sidebar -->

            </div>
            <!-- Bootstrap JS for mobile sidebar toggle -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="../admin//script.js"></script>
            <script src="../admin//active.js"></script>


</body>

</html>