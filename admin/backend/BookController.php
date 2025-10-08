<?php
class Database
{
    private $host = "localhost";
    private $db   = "it6_LMS";
    private $user = "root";
    private $pass = "";
    public $pdo;

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8";
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    // CREATE
    public function createBook($title, $author, $category, $isbn, $publish_date, $copies, $imagePath)
    {
        try {
            $sql = "CALL  AddBook(:title, :author,:category, :isbn, :publish_date, :copies, :image)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':author' => $author,
                ':category' => $category,
                ':isbn' => $isbn,
                ':publish_date' => $publish_date,
                ':copies' => $copies,
                ':image' => $imagePath
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
            return false;
        }
    }

    // READ all
    public function getBooks()
    {
        $sql = "SELECT * FROM books ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ one
    public function getBookById($id)
    {
        $sql = "SELECT * FROM books WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function updateBook($id, $title, $author, $category, $isbn, $publish_date, $copies, $imagePath)
    {
        try {
            $sql = "UPDATE books 
                SET title = :title, author = :author, category = :category ,isbn = :isbn, publish_date = :publish_date, copies = :copies, image = :image
                WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':title' => $title,
                ':author' => $author,
                ':category' => $category,
                ':isbn' => $isbn,
                ':publish_date' => $publish_date,
                ':copies' => $copies,
                ':image' => $imagePath,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Update failed: " . $e->getMessage());
            return false;
        }
    }

    // DELETE
    public function deleteBook($id)
    {
        try {
            $sql = "DELETE FROM books WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Delete failed: " . $e->getMessage());
            return false;
        }
    }

    // pagination function

    public function tableBooks($limit = 6, $offset = 0)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM books ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalBooks()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM books");
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getRecentlyAddedBooks($days = 3)
    {
        $stmt = $this->pdo->prepare("
        SELECT * FROM books 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
        ORDER BY created_at DESC
        LIMIT 5
    ");
        $stmt->execute([$days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilteredBooks($category = 'all', $search = '', $sort = 'title', $group_by = null, $having = null)
    {
        $sql = "SELECT * FROM books WHERE 1=1";
        $params = [];

        if ($category !== 'all') {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }

        if (!empty($search)) {
            $sql .= " AND (id LIKE :search OR title LIKE :search OR category LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $allowed_sorts = ['title', 'author', 'publish_year'];
        $sort = in_array($sort, $allowed_sorts) ? $sort : 'title';
        $sql .= " ORDER BY $sort";

        if ($group_by) {
            $sql = str_replace("SELECT *", "SELECT $group_by, COUNT(*) AS count", $sql);
            $sql .= " GROUP BY $group_by";
            if ($having) {
                $sql .= " HAVING $having";
            }
        }

        // Add LIMIT 6 only when search is empty
        if (empty($search)) {
            $sql .= " LIMIT 6";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countBooksByCategory($category)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM books WHERE category = :category";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':category' => $category]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Count failed: " . $e->getMessage());
            return 0;
        }
    }

    public function getAllCategoriesWithCount()
    {
        try {
            $sql = "SELECT category, COUNT(*) as total 
                FROM books 
                GROUP BY category 
                ORDER BY category ASC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Count failed: " . $e->getMessage());
            return [];
        }
    }
}
