<?php
session_start();
require '../auth.php';
require '../admin/backend/BookController.php';
require '../admin/backend/transactionControll.php';


$database = new Database();
$auth = new auth($database->pdo);
$library = new Library($database->pdo);

$books = $database->getBooks();
$totalbooks = $database->getTotalBooks();
$totalusers = $auth->getTotalusers();
$totaladmin = $auth->getTotaladmin();
$countTransactions = $library->countTransactions();


if (isset($_POST['logout'])) {
  $auth->logout();
  header('Location: ../login.php');
  exit;
}

if (!$auth->isLoggedIn() || $_SESSION['user_role'] !== 'Users') {
  header('Location: ../login.php');
  exit;
}



$user_id = $auth->getUserId();
$activeBorrowings = $library->getActiveBorrowingsWithUser($user_id);
$getTotalActive = $library->getTotalActiveBorrowed($user_id);
$getTotalTransaction = $library->getTotalTransactions($user_id);
$getUserTransactions = $library->getUserTransactions($user_id);
$total_overdue = $library->getOverdueBookCountForUser($user_id);
$overdueFees = $library->getOverdueFeesPerUser($user_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Transaction</title>
  <link rel="stylesheet" href="../src/input.css" />
  <link rel="stylesheet" href="../src/output.css" />
  <link rel="icon" href="../image/willan.jpg" type="image/jpeg">
  <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="min-h-screen bg-[#101929]">
  <!-- Header -->
  <?php if (file_exists('Frontend/header.php')) include 'Frontend/header.php'; ?>
  <!-- Header -->


  <!-- Main Content -->
  <main class="max-w-7xl mx-auto px-6 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-[#1E2939] rounded-xl p-6 text-white">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-medium ">Active Borrowings</h3>
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
        </div>
        <div class="text-3xl font-bold mb-2"><?php echo $getTotalActive ?></div>
        <div class="text-sm opacity-80">Currently borrowed books</div>
      </div>

      <!-- Overdue Books -->
      <div class="bg-[#1E2939] rounded-xl p-6 text-white">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-medium text-white">Overdue Books</h3>
          <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
        </div>
        <div class="flex items-center space-x-2 mb-2">
          <div class="text-3xl font-bold"><?php echo $total_overdue; ?></div>
        </div>
        <div class="text-sm text-white">Books past due date</div>
      </div>

      <!-- Outstanding Fees -->
      <div class="bg-[#1E2939] rounded-xl p-6 text-white">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-medium text-white">Outstanding Fees</h3>
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
          </svg>
        </div>

        <?php
        $overdueFees = $library->getOverdueFeesPerUser($user_id); // Returns a single float/integer
        if (is_numeric($overdueFees) && $overdueFees > 0): ?>
          <div class="flex items-center space-x-2 mb-2">
            <div class="text-3xl font-bold text-red-500">₱<?= number_format($overdueFees, 2) ?></div>
            <span><button class="bg-green-300 p-1 px-4 rounded-3xl text-[12px] ml-4 text-black hover:bg-red-300">Pay fees</button></span>
          </div>
          <div class="text-sm text-white">Fees for active borrowings</div>
          <hr class="my-4 border-gray-200">
        <?php else: ?>
          <div class="text-sm text-white">No overdue fees found.</div>
        <?php endif; ?>
      </div>

      <!-- Total Transactions -->
      <div class="bg-[#1E2939] rounded-xl p-6 text-white">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-medium text-white">Total Transactions</h3>
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <div class="text-3xl font-bold mb-2"><?php echo $getTotalTransaction ?></div>
        <div class="text-sm text-white">All-time borrowings</div>
      </div>
    </div>

    <!-- Active Borrowings Table -->
    <div class="bg-[#1E2939] rounded-xl p-6 mb-8">
      <div class="flex items-center space-x-2 mb-6">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        <h2 class="text-xl font-semibold text-white opacity-70">Active Borrowings <?php echo $getTotalActive ?></h2>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200 border-opacity-20">
              <th class="text-left py-3 px-4 font-medium text-white">Transaction ID</th>
              <th class="text-left py-3 px-4 font-medium text-white">Book ID</th>
              <th class="text-left py-3 px-4 font-medium text-white">Title</th>
              <th class="text-left py-3 px-4 font-medium text-white">Author</th>
              <th class="text-left py-3 px-4 font-medium text-white">Borrowed Date</th>
              <th class="text-left py-3 px-4 font-medium text-white">Due Date</th>
              <th class="text-left py-3 px-4 font-medium text-white">Status</th>
              <th class="text-left py-3 px-4 font-medium text-white">Fee</th>
            </tr>
          </thead>
          <tbody class="text-white">
            <?php foreach ($activeBorrowings as $row): ?>
              <?php
              $dueDate = new DateTime($row['due_date']);
              $today = new DateTime();
              $isOverdue = $today > $dueDate;
              $daysRemaining = $today->diff($dueDate)->days;

              // Status logic
              if ($isOverdue) {
                $statusClass = 'bg-red-100 text-red-800';
                $statusText = "Overdue";
                $fee = $row['fee'] ?? ($daysRemaining * 50);
              } elseif ($daysRemaining < 3) {
                $statusClass = 'bg-yellow-100 text-yellow-800';
                $statusText = "Day $daysRemaining - Good";
                $fee = 0;
              } else {
                $statusClass = 'bg-green-100 text-green-800';
                $statusText = "Day $daysRemaining - Excellent";
                $fee = 0;
              }
              ?>
              <tr class="border-b border-gray-100 hover:bg-[#101929] border-opacity-10">
                <td class="py-4 px-4 font-medium"><?= $row['transaction_id'] ?></td>
                <td class="py-4 px-4"><?= $row['book_id'] ?></td>
                <td class="py-4 px-4 font-medium"><?= htmlspecialchars($row['title']) ?></td>
                <td class="py-4 px-4"><?= htmlspecialchars($row['author']) ?></td>
                <td class="py-4 px-4"><?= date("M j, Y", strtotime($row['borrow_date'])) ?></td>
                <td class="py-4 px-4"><?= date("M j, Y", strtotime($row['due_date'])) ?></td>
                <td class="py-4 px-4">
                  <span class="<?= $statusClass ?> text-xs font-medium px-2 py-1 rounded-full">
                    <?= $statusText ?>
                  </span>
                </td>
                <td class="py-4 px-4 font-medium text-red-400" style="color: <?= $isOverdue ? '#e24545' : 'inherit' ?>;">
                  ₱ <?= number_format($fee, 2) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-[#1E2939] rounded-xl p-6">
      <div class="flex items-center space-x-2 mb-6">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h2 class="text-xl font-semibold text-white opacity-70">Transaction History <?php echo $getTotalTransaction ?></h2>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200 border-opacity-20">
              <th class="text-left py-3 px-4 font-medium text-white">Transaction ID</th>
              <th class="text-left py-3 px-4 font-medium text-white">Book ID</th>
              <th class="text-left py-3 px-4 font-medium text-white">Title</th>
              <th class="text-left py-3 px-4 font-medium text-white">Author</th>
              <th class="text-left py-3 px-4 font-medium text-white">Borrowed Date</th>
              <th class="text-left py-3 px-4 font-medium text-white">Return Date</th>
              <th class="text-left py-3 px-4 font-medium text-white">Final Fee</th>
            </tr>
          </thead>
          <tbody class="text-white">
            <?php foreach ($getUserTransactions as $row): ?>
              <tr class="border-b border-gray-100 hover:bg-[#101929] border-opacity-10">
                <td class="py-4 px-4 font-medium"><?= $row['transaction_id'] ?></td>
                <td class="py-4 px-4"><?= $row['book_id'] ?></td>
                <td class="py-4 px-4 font-medium"><?= htmlspecialchars($row['title']) ?></td>
                <td class="py-4 px-4"><?= htmlspecialchars($row['author']) ?></td>
                <td class="py-4 px-4"><?= date("M j, Y", strtotime($row['borrow_date'])) ?></td>
                <td class="py-4 px-4"><?= htmlspecialchars($row['return_date'] ?? 'N/A') ?></td>
                <td class="py-4 px-4 font-medium text-[#e24545] ">
                  <?= htmlspecialchars($row['fee']) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
  <script src="../user-interface//user.js"></script>
</body>

</html>