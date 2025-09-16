<?php  
require 'database.php';
require 'auth.php';

$db = (new database())->getConnection();
$auth = new auth($db);

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $email = $_POST['email'];
  $password = $_POST['password'];

  $user = $auth->login($email, $password);


  if ($user) {
    $_SESSION['users_id'] = $user['id'];
    $_SESSION['users_role'] = $user['roles'];

    // Role-based redirection
    if ($user['roles'] === 'Admin') {
        header('Location: admindashboard.php');
        echo "Logged in as: " . $user['roles'];
        exit;
    } elseif ($user['roles'] === 'Users') {
        header('Location: userboard.php');
        echo "Logged in as: " . $user['roles'];
        exit;
    } else {
        $message = 'Unknown role. Access denied.';
    }
  } else {
$message = 'Login failed. Please check your credentials.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./src/input.css" />
  <link rel="stylesheet" href="./src/output.css" />
  <title>Document</title>
</head>
<body>
 <div class="flex min-h-screen items-center justify-center bg-[#1A1A1A] py-12 px-4">
  <div class="flex flex-col md:flex-row w-full max-w-4xl rounded-lg shadow-lg overflow-hidden md:h-[495px]">
    
    <!-- Left Section -->
    <div class="md:w-1/2 w-full bg-[#252525] p-8 flex flex-col items-center justify-center text-center text-white">
      <img src="./image/willan.jpg" alt="William Shakespeare" class="w-20 h-20 md:w-32 md:h-32 rounded-full mb-4">
      <h2 class="text-3xl md:text-4xl font-bold mb-2">Library Management System</h2>
      <p class=" my-4 md:my-8 text-[18px]">Your premier digital library for borrowing and reading books.</p>
    </div>

    <!-- Right Section -->
    <div class="md:w-1/2 w-full p-8 flex flex-col justify-center items-center text-center bg-[#1E1E1E] text-white">
      <div class="w-full max-w-md">
        <h2 class="text-2xl md:text-4xl font-semibold  mb-6">LOGIN</h2>
      <?php if($message): ?>
            <div style="color: red; padding-bottom: 18px;"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        <form class="space-y-4" method="POST">
          <div>
            <label for="email" class="block text-sm font-medium  text-left">Email</label>
            <input type="email" placeholder="Email" id="email" name="email" class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          <div>
            <label for="password" class="block text-sm font-medium  text-left">Password</label>
            <input type="password" placeholder="Password" name="password" id="password" class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          <div class="flex justify-between items-center">
            <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
          </div>
          <button type="submit" class="w-full cursor-pointer bg-white text-black py-2 border rounded-md hover:bg-[#1A2C2F] hover:border-[#1ED1E9] hover:text-white transition">Log in</button>
           <p class="mt-6 ">
          Don't have an account?
          <a href="register.php" class="text-blue-600 hover:underline font-medium"> Sign up</a>
        </p>
        </form>
      </div>
    </div>

  </div>
</div>
</body>
</html>