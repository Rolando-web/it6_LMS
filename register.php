<?php
require 'database.php';
require 'auth.php';

$db = (new database())->getConnection();
$auth = new auth($db);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first_name = $_POST['first_name'] ?? '';
  $last_name  = $_POST['last_name'] ?? '';
  $contact    = $_POST['contact'] ?? '';
  $email      = $_POST['email'] ?? '';
  $password   = $_POST['password'] ?? '';

  // Basic validation (optional)
  if ($first_name && $last_name && $contact && $email && $password) {
    if ($auth->register($first_name, $last_name, $contact, $email, $password)) {
      header('Location: login.php');
      exit;
    } else {
      $message = 'Registration failed. Please try again.';
    }
  } else {
    $message = 'All fields are required.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="./image/willan.jpg" type="image/jpeg">
  <link rel="stylesheet" href="./src/input.css" />
  <link rel="stylesheet" href="./src/output.css" />
  <title>Register</title>
</head>

<body>
  <div class="flex min-h-screen items-center justify-center bg-[#1A1A1A] py-12 px-4">
    <div class="flex flex-col md:flex-row-reverse w-full max-w-4xl rounded-lg shadow-lg overflow-hidden md:h-[600px]">

      <!-- Right Section (Image & Title) -->
      <div class="md:w-1/2 w-full bg-[#252525] p-8 flex flex-col items-center justify-center text-center text-white">
        <img src="./image/willan.jpg" alt="William Shakespeare" class="w-20 h-20 md:w-32 md:h-32 rounded-full mb-4">
        <h2 class="text-3xl md:text-4xl font-bold mb-2">Library Management System</h2>
        <p class="my-4 md:my-8 text-[18px]">Your premier digital library for borrowing and reading books.</p>
      </div>

      <!-- Left Section (Registration Form) -->
      <div class="md:w-1/2 w-full p-8 flex flex-col justify-center items-center text-center bg-[#1E1E1E] text-white">
        <div class="w-full max-w-md">
          <h2 class="text-2xl md:text-4xl font-semibold mb-6">REGISTER</h2>
          <p class="mb-4">Create your account to get started.</p>
          <?php if ($message): ?>
            <div style="color: red;"><?php echo htmlspecialchars($message); ?></div>
          <?php endif; ?>
          <form class="space-y-4" method="POST">
            <!-- First Name & Last Name -->
            <div class="flex flex-col md:flex-row gap-4">
              <div class="w-full">
                <label for="firstName" class="block text-sm font-medium text-left">First Name</label>
                <input type="text" id="firstName" name="first_name" placeholder="First Name" class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div class="w-full">
                <label for="lastName" class="block text-sm font-medium text-left">Last Name</label>
                <input type="text" id="lastName" name="last_name" placeholder="Last Name" class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
            </div>

            <!-- Contact -->
            <div>
              <label for="contact" class="block text-sm font-medium text-left">Contact</label>
              <input type="text" id="contact" name="contact" placeholder="Contact Number" class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-left">Email</label>
              <input type="email" id="email" name="email" placeholder="Email" class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-left">Password</label>
              <input type="password" id="password" name="password" placeholder="Password" class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full cursor-pointer bg-white border text-black py-2 rounded-md hover:bg-[#1A2C2F] hover:border-[#1ED1E9] hover:text-white transition">Register</button>

            <!-- Login Redirect -->
            <p class="mt-6">
              Already have an account?
              <a href="login.php" class="text-blue-600 hover:underline font-medium">Log in</a>
            </p>
          </form>
        </div>
      </div>

    </div>
  </div>

</body>

</html>