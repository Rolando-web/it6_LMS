<?php
require_once 'Database.php';
$db = new Database();

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'all';
$books = $db->getFilteredBooks($category, $search);

if (!empty($books)) {
  foreach ($books as $book) {
    echo '<tr>
                <td><img src="' . htmlspecialchars($book['image'] ?: '../image/default.jpg') . '" alt="' . htmlspecialchars($book['title']) . '" width="80" class="rounded"></td>
                <td>' . htmlspecialchars($book['id']) . '</td>
                <td><div class="fw-bold">' . htmlspecialchars($book['title']) . '</div><small class="opacity-50">' . htmlspecialchars($book['author']) . '</small></td>
                <td>' . htmlspecialchars($book['author']) . '</td>
                <td>' . htmlspecialchars($book['category']) . '</td>
                <td>' . htmlspecialchars($book['isbn']) . '</td>
                <td>' . htmlspecialchars($book['publish_date']) . '</td>
                <td>' . htmlspecialchars($book['copies']) . '</td>
                <td>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary editBtn" data-id="' . htmlspecialchars($book['id']) . '">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" onsubmit="return confirm(\'Are you sure?\')">
                            <input type="hidden" name="delete_id" value="' . htmlspecialchars($book['id']) . '">
                            <input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>';
  }
} else {
  echo '<tr><td colspan="9" class="text-center text-danger">No books found.</td></tr>';
}
