<?php
class Database
{
    private $host = "localhost";   // change if needed
    private $db   = "it6_LMS";     // change to your DB name
    private $user = "root";        // your MySQL username
    private $pass = "";            // your MySQL password
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
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
