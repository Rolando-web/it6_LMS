<?php 
require 'database.php';
require 'auth.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./src/input.css" />
  <link rel="stylesheet" href="./src/output.css" />
  <title>Document</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Active link styling */
    .active {
      background-color: #e0f2fe;
      color: #0284c7;
    }
  </style>
</head>
<body>
  <div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 md:w-64 bg-white shadow-md flex flex-col flex-shrink-0">
      <!-- Hamburger menu for mobile -->
      <button class="md:hidden p-4 text-gray-600 focus:outline-none">
        <i class="fas fa-bars text-2xl"></i>
      </button>
      
      <!-- Logo -->
      <div class="p-4 md:p-6">
        <h1 class="text-xl font-bold text-indigo-600">Admin</h1>
      </div>
      
      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto">
        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 active">
          <i class="fas fa-tachometer-alt mr-3 text-indigo-500"></i>
          <span class="md:block hidden">Dashboard</span>
        </a>
        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-book mr-3 text-indigo-500"></i>
          <span class="md:block hidden">Catalog</span>
        </a>
        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-book-open mr-3 text-indigo-500"></i>
          <span class="md:block hidden">Books</span>
        </a>
        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-users mr-3 text-indigo-500"></i>
          <span class="md:block hidden">Users</span>
        </a>
      </nav>
  </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <header class="bg-white shadow-sm p-4 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Dashboard</h2>
        <a href="login.php">
          <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition" href="login.php">
          <i class="fas fa-sign-out-alt mr-2"></i>
          Logout
        </button>
        </a>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 p-6 overflow-y-auto">
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-medium mb-4">Welcome to the Admin Dashboard</h3>
          <p class="text-gray-600">Manage your library's catalog, books, and users from this interface.</p>
        </div>
      </main>
    </div>
  </div>
</body>
</html>