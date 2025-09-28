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

      $stmt = $this->conn->prepare("UPDATE books SET copies = copies - 1 WHERE id = :book_id");
      $stmt->execute([':book_id' => $book_id]);

      return "Book borrowed successfully!";
    } catch (PDOException $e) {
      return "Error: " . $e->getMessage();
    }
  }

  // Return a Book
  public function returnBook($transaction_id)
  {
    try {
      $this->conn->beginTransaction();

      // Get transaction details
      $stmt = $this->conn->prepare("SELECT book_id, due_date, return_date FROM transactions where transaction_id = :transaction_id FOR UPDATE");
      $stmt->execute([':transaction_id' => $transaction_id]);
      $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$transaction) {
        throw new Exception("Transaction not found.");
      }

      // Check if already returned
      if ($transaction['return_date'] !== null) {
        throw new Exception("Book already returned.");
      }

      $book_id = $transaction['book_id'];
      $due_date = $transaction['due_date'];

      // Update transaction with return date and calculate fee
      $stmt = $this->conn->prepare("
            UPDATE transactions 
            SET return_date = CURDATE(),
                overdue_days = GREATEST(DATEDIFF(CURDATE(), :due_date), 0),
                fee = GREATEST(DATEDIFF(cURDATE(), :due_date), 0) * 50
            where transaction_id = :transaction_id
        ");
      $stmt->execute([
        ':due_date' => $due_date,
        ':transaction_id' => $transaction_id
      ]);

      // Increase book copies
      $stmt = $this->conn->prepare("UPDATE books set copies = copies + 1 WHERE id = :book_id");
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

  public function getActiveBorrowingsWithUser($user_id)
  {
    try {
      $stmt = $this->conn->prepare("
            SELECT 
                t.transaction_id,
                b.id AS book_id,
                b.title,
                b.author,
                b.image,
                b.publish_date,
                t.borrow_date,
                t.due_date
            FROM transactions t
            JOIN books b ON t.book_id = b.id
            WHERE t.user_id = :user_id AND t.return_date IS NULL
            ORDER BY t.borrow_date DESC
        ");
      $stmt->execute([':user_id' => $user_id]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }


  public function getTotalActiveBorrowed($user_id)
  {
    try {
      $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM transactions t
            WHERE t.user_id = :user_id 
              AND t.return_date IS NULL
        ");
      $stmt->execute([':user_id' => $user_id]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'] ?? 0;
    } catch (PDOException $e) {
      return 0;
    }
  }

  public function getTotalTransactions($user_id)
  {
    $stmt = $this->conn->prepare("
        SELECT COUNT(*) AS total 
        FROM transactions 
        WHERE user_id = :user_id");

    $stmt->execute([':user_id' => $user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
  }

  public function getUserTransactions($user_id)
  {
    $stmt = $this->conn->prepare("
        SELECT 
            t.transaction_id, 
            t.book_id,
            t.borrow_date, 
            t.due_date, 
            t.return_date, 
            t.overdue_days,
            t.fee,
            b.title,
            b.author
        FROM transactions t
        INNER JOIN books b ON t.book_id = b.id
        WHERE t.user_id = :user_id
        ORDER BY t.borrow_date DESC");

    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
