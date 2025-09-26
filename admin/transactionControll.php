<?php
class Library
{
  private $conn;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  // 📌 Borrow a Book
  public function borrowBook($user_id, $book_id, $duration = 14)
  {
    try {
      // check available copies
      $stmt = $this->conn->prepare("SELECT copies FROM books WHERE id = :book_id");
      $stmt->execute([':book_id' => $book_id]);
      $book = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$book || $book['copies'] <= 0) {
        return "This book is not available.";
      }

      // insert into transactions
      $borrow_date = date("Y-m-d");
      $due_date = date("Y-m-d", strtotime("+$duration days"));

      $stmt = $this->conn->prepare("
            INSERT INTO transactions (user_id, book_id, borrow_date, due_date)
            VALUES (:user_id, :book_id, :borrow_date, :due_date)
        ");
      $stmt->execute([
        ':user_id' => $user_id,
        ':book_id' => $book_id,
        ':borrow_date' => $borrow_date,
        ':due_date' => $due_date
      ]);

      // decrement book copies
      $stmt = $this->conn->prepare("UPDATE books SET copies = copies - 1 WHERE id = :book_id");
      $stmt->execute([':book_id' => $book_id]);

      return "Book borrowed successfully!";
    } catch (PDOException $e) {
      return "Error: " . $e->getMessage();
    }
  }

  // 📌 Return a Book
  public function returnBook($transaction_id)
  {
    try {
      $this->conn->beginTransaction();

      // Get transaction details
      $stmt = $this->conn->prepare("SELECT book_id, due_date FROM transactions WHERE transaction_id = :transaction_id FOR UPDATE");
      $stmt->execute([':transaction_id' => $transaction_id]);
      $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$transaction) {
        throw new Exception("Transaction not found.");
      }

      $book_id = $transaction['book_id'];
      $due_date = $transaction['due_date'];

      // Calculate overdue & fee
      $stmt = $this->conn->prepare("
                UPDATE transactions 
                SET return_date = CURDATE(),
                    overdue_days = GREATEST(DATEDIFF(CURDATE(), :due_date), 0),
                    fee = GREATEST(DATEDIFF(CURDATE(), :due_date), 0) * 50
                WHERE transaction_id = :transaction_id
            ");
      $stmt->execute([
        ':due_date' => $due_date,
        ':transaction_id' => $transaction_id
      ]);

      // Increase book copies
      $stmt = $this->conn->prepare("UPDATE books SET copies = copies + 1 WHERE id = :book_id");
      $stmt->execute([':book_id' => $book_id]);

      $this->conn->commit();
      return "Book returned successfully!";
    } catch (Exception $e) {
      $this->conn->rollBack();
      return "Error: " . $e->getMessage();
    }
  }

  // Count total transactions
  public function countTransactions()
  {
    $stmt = $this->conn->query("SELECT COUNT(*) as total FROM transactions");
    return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
  }

  // Fetch transactions with pagination
  public function getTransactions($limit, $offset)
  {
    $stmt = $this->conn->prepare("
        SELECT 
            t.transaction_id, 
            t.user_id,
            t.book_id,
            t.borrow_date, 
            t.due_date, 
            t.return_date, 
            t.overdue_days, 
            t.fee
        FROM transactions t
        ORDER BY t.borrow_date DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // � Get Active Borrowings for a User
  public function getActiveBorrowingsWithUser($user_id)
  {
    try {
      $stmt = $this->conn->prepare("
            SELECT 
                t.transaction_id,
                b.id AS book_id,
                b.title,
                b.author,
                t.borrow_date,
                t.due_date,
                CONCAT(u.first_name, ' ', u.last_name) AS user_name,
                CASE 
                    WHEN CURDATE() > t.due_date THEN 'Overdue'
                    WHEN DATEDIFF(t.due_date, CURDATE()) < 3 THEN CONCAT('Day ', DATEDIFF(t.due_date, CURDATE()), ' - Good')
                    ELSE CONCAT('Day ', DATEDIFF(t.due_date, CURDATE()), ' - Excellent')
                END AS status,
                GREATEST(DATEDIFF(CURDATE(), t.due_date), 0) AS overdue_days,
                GREATEST(DATEDIFF(CURDATE(), t.due_date), 0) * 50 AS fee
            FROM transactions t
            JOIN books b ON t.book_id = b.id
            JOIN users u ON t.user_id = u.id
            WHERE t.user_id = :user_id AND t.return_date IS NULL
            ORDER BY t.borrow_date DESC
        ");
      $stmt->execute([':user_id' => $user_id]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }
}
