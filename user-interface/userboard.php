<?php
require '../database.php';
require '../auth.php';

$db = (new database())->getConnection();
$auth = new auth($db);


?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/svg+xml" href="/vite.svg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../src/input.css" />
  <link rel="stylesheet" href="../src/output.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <title>Home Library</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            'sans': ['Inter', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>
</head>

<body class="bg-gray-900 text-white font-sans">
  <!-- Background -->
  <div class="min-h-screen relative bg-cover bg-center bg-no-repeat" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.pexels.com/photos/481516/pexels-photo-481516.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop');">

    <!-- Header -->
    <header class="relative z-10 px-6 py-4">
      <nav class="flex items-center md:justify-between max-w-7xl mx-auto">
        <!-- Logo -->
        <div class="text-xl font-bold flex-1 pr-10 lg-flex-0">
          <span class="text-white">HOME</span><span class="text-gray-300">LIBRARY</span>
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="md:hidden text-white hover:text-gray-300 transition-colors mx-2">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>

        <!-- Navigation Links -->
        <div class="hidden md:flex items-start space-x-4 sm:flex-1">
          <a href="#" class="flex items-center space-x-1 text-white hover:text-gray-300 transition-colors">
            <div class="w-4 h-4 border border-white"></div>
            <span>Home</span>
          </a>
          <a href="#" class="flex items-center space-x-1 text-gray-300 hover:text-white transition-colors">
            <div class="w-4 h-4 grid grid-cols-2 gap-px">
              <div class="bg-gray-300 w-full h-full"></div>
              <div class="bg-gray-300 w-full h-full"></div>
              <div class="bg-gray-300 w-full h-full"></div>
              <div class="bg-gray-300 w-full h-full"></div>
            </div>
            <span>Category</span>
          </a>
          <a href="#" class="flex items-center space-x-1 text-gray-300 hover:text-white transition-colors">
            <div class="w-4 h-4 border border-gray-300 relative">
              <div class="absolute inset-1 bg-gray-300"></div>
            </div>
            <span>Books</span>
          </a>
          <a href="#" class="flex items-center space-x-1 text-gray-300 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Suggest</span>
          </a>
        </div>

        <!-- Desktop Search and Profile -->
        <div class="hidden md:flex items-center space-x-4">
          <!-- Search -->
          <div class="relative">
            <input type="text" placeholder="Search title, Author, Isbn" class="bg-gray-800 bg-opacity-50 text-white placeholder-gray-400 px-4 py-2 pr-10 rounded-lg border border-gray-600 focus:border-gray-400 focus:outline-none w-64">
            <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>

          <!-- Profile -->
          <div class="flex items-center space-x-3">
            <div class="text-right">
              <div class="text-sm font-medium">Rolando Luayon</div>
              <div class="text-xs text-gray-400">Member</div>
            </div>
            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 flex items-center justify-center">
              <img src="../image/willan.jpg" alt="profile" class="w-full h-full object-cover rounded-full">
            </div>
          </div>
        </div>

        <!-- Mobile Profile (visible on mobile) -->
        <div class="md:hidden flex items-center">
          <div class="w-8 h-8 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 flex items-center justify-center">
            <img src="../image/willan.jpg" alt="profile" class="w-full h-full object-cover rounded-full">
          </div>
        </div>
      </nav>

      <!-- Mobile Menu Overlay -->
      <div id="mobileMenu" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="fixed top-0 right-0 h-full w-80 bg-gray-900 shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out" id="mobileMenuPanel">
          <!-- Mobile Menu Header -->
          <div class="flex items-center justify-between p-6 border-b border-gray-800">
            <div class="text-lg font-bold">
              <span class="text-white">HOME</span><span class="text-gray-300">LIBRARY</span>
            </div>
            <button id="closeMobileMenu" class="text-white hover:text-gray-300 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Mobile Menu Content -->
          <div class="p-6">
            <!-- Profile Section -->
            <div class="flex items-center space-x-3 mb-8 pb-6 border-b border-gray-800">
              <div class="w-12 h-12 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 flex items-center justify-center">
                <img src="../image/willan.jpg" alt="profile" class="w-full h-full object-cover rounded-full">
              </div>
              <div>
                <div class="text-white font-medium">Rolando Luayon</div>
                <div class="text-gray-400 text-sm">Member</div>
              </div>
            </div>

            <!-- Search -->
            <div class="mb-6">
              <div class="relative">
                <input type="text" placeholder="Search title, Author, Isbn" class="w-full bg-gray-800 bg-opacity-50 text-white placeholder-gray-400 px-4 py-3 pr-10 rounded-lg border border-gray-600 focus:border-gray-400 focus:outline-none">
                <svg class="absolute right-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-4">
              <a href="#" class="flex items-center space-x-3 text-white hover:text-gray-300 transition-colors py-3">
                <div class="w-5 h-5 border border-white"></div>
                <span class="text-lg">Home</span>
              </a>
              <a href="#" class="flex items-center space-x-3 text-gray-300 hover:text-white transition-colors py-3">
                <div class="w-5 h-5 grid grid-cols-2 gap-px">
                  <div class="bg-gray-300 w-full h-full"></div>
                  <div class="bg-gray-300 w-full h-full"></div>
                  <div class="bg-gray-300 w-full h-full"></div>
                  <div class="bg-gray-300 w-full h-full"></div>
                </div>
                <span class="text-lg">Category</span>
              </a>
              <a href="books.html" class="flex items-center space-x-3 text-gray-300 hover:text-white transition-colors py-3">
                <div class="w-5 h-5 border border-gray-300 relative">
                  <div class="absolute inset-1 bg-gray-300"></div>
                </div>
                <span class="text-lg">Books</span>
              </a>
              <a href="#" class="flex items-center space-x-3 text-gray-300 hover:text-white transition-colors py-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-lg">Suggest</span>
              </a>
            </nav>
          </div>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <main class="relative z-0 flex-1 flex items-center justify-center px-6" style="background-image: url(../image/wew.png); background-size: cover; background-position: center;">
      <div class="text-center max-w-4xl mx-auto mt-16 mb-24">
        <h1 class="text-6xl md:text-7xl lg:text-8xl font-light mb-6 leading-tight">
          <div class="text-gray-300 mb-2">Welcome to</div>
          <div class="font-normal">
            <span class="text-white">Home</span><span class="text-gray-300">Library</span>
          </div>
        </h1>

        <p class="text-gray-300 text-lg md:text-xl mb-8 max-w-2xl mx-auto leading-relaxed">
          Discover thousands of books, explore new genres, and embark on endless literary adventures. Your next great read awaits.
        </p>

        <button class="bg-white text-gray-900 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors inline-flex items-center space-x-2 group">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
          <span>Book Collection</span>
          <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
          </svg>
        </button>
      </div>
    </main>

    <!-- Statistics -->
    <footer class="relative z-0 px-6 py-12">
      <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Books Available -->
          <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-teal-600 bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
            </div>
            <div class="text-4xl font-light text-white mb-2">100+</div>
            <div class="text-gray-400">Books Available</div>
          </div>

          <!-- Active Members -->
          <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-blue-600 bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="text-4xl font-light text-white mb-2">100+</div>
            <div class="text-gray-400">Active Members</div>
          </div>

          <!-- New Arrivals -->
          <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-amber-600 bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
              </svg>
            </div>
            <div class="text-4xl font-light text-white mb-2">100+</div>
            <div class="text-gray-400">New Arrival Available</div>
          </div>
        </div>
      </div>
    </footer>
  </div>

  <!-- Featured Books Section -->
  <section class="bg-gray-900 py-16 px-6">
    <div class="max-w-7xl mx-auto">
      <!-- Section Header -->
      <div class="flex items-center justify-between mb-12">
        <div>
          <h2 class="text-3xl font-light text-white mb-2">Featured Books</h2>
          <p class="text-gray-400">Hand-picked selections from our curated collection</p>
        </div>
      </div>

      <!-- 🔹 First Row Slider -->
      <div class="swiper mySwiper1">
        <div class="swiper-wrapper ">
          <!-- Book 1 -->
          <div class="swiper-slide">
            <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
              <div class="mb-4">
                <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden"> <img src="https://images.pexels.com/photos/1029141/pexels-photo-1029141.jpeg?auto=compress&cs=tinysrgb&w=200&h=300&fit=crop" alt="The Art of Conscious Living" class="w-full h-full object-cover rounded-lg"> </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-white font-medium text-lg leading-tight">The Art of Conscious Living</h3>
                <p class="text-gray-400 text-sm">by Dr. Elena Martinez</p>
                <div class="flex items-center space-x-2">
                  <div class="flex items-center space-x-1"> <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg> <span class="text-yellow-400 text-sm font-medium">4.8</span> </div> <span class="text-gray-500 text-sm">2024</span>
                </div> <button class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4"> Borrow Books </button>
              </div>
            </div>
          </div>

          <!-- Book 2 -->
          <div class="swiper-slide">
            <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
              <div class="mb-4">
                <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden"> <img src="https://images.pexels.com/photos/1029141/pexels-photo-1029141.jpeg?auto=compress&cs=tinysrgb&w=200&h=300&fit=crop" alt="The Art of Conscious Living" class="w-full h-full object-cover rounded-lg"> </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-white font-medium text-lg leading-tight">The Art of Conscious Living</h3>
                <p class="text-gray-400 text-sm">by Dr. Elena Martinez</p>
                <div class="flex items-center space-x-2">
                  <div class="flex items-center space-x-1"> <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg> <span class="text-yellow-400 text-sm font-medium">4.8</span> </div> <span class="text-gray-500 text-sm">2024</span>
                </div> <button class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4"> Borrow Books </button>
              </div>
            </div>
          </div>
          <!-- Book 3 -->
          <div class="swiper-slide">
            <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
              <div class="mb-4">
                <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden"> <img src="https://images.pexels.com/photos/1029141/pexels-photo-1029141.jpeg?auto=compress&cs=tinysrgb&w=200&h=300&fit=crop" alt="The Art of Conscious Living" class="w-full h-full object-cover rounded-lg"> </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-white font-medium text-lg leading-tight">The Art of Conscious Living</h3>
                <p class="text-gray-400 text-sm">by Dr. Elena Martinez</p>
                <div class="flex items-center space-x-2">
                  <div class="flex items-center space-x-1"> <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg> <span class="text-yellow-400 text-sm font-medium">4.8</span> </div> <span class="text-gray-500 text-sm">2024</span>
                </div> <button class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4"> Borrow Books </button>
              </div>
            </div>
          </div>
          <!-- Book 4 -->
          <div class="swiper-slide">
            <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
              <div class="mb-4">
                <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden"> <img src="https://images.pexels.com/photos/1029141/pexels-photo-1029141.jpeg?auto=compress&cs=tinysrgb&w=200&h=300&fit=crop" alt="The Art of Conscious Living" class="w-full h-full object-cover rounded-lg"> </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-white font-medium text-lg leading-tight">The Art of Conscious Living</h3>
                <p class="text-gray-400 text-sm">by Dr. Elena Martinez</p>
                <div class="flex items-center space-x-2">
                  <div class="flex items-center space-x-1"> <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg> <span class="text-yellow-400 text-sm font-medium">4.8</span> </div> <span class="text-gray-500 text-sm">2024</span>
                </div> <button class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4"> Borrow Books </button>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-10">
          <div class="swiper-pagination"></div>
        </div>
      </div>

      <!-- 🔹 Second Row Slider -->
      <div class="swiper mySwiper2">
        <div class="swiper-wrapper">
          <!-- Book 5 -->
          <div class="swiper-slide">
            <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
              <div class="mb-4">
                <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden"> <img src="https://images.pexels.com/photos/1029141/pexels-photo-1029141.jpeg?auto=compress&cs=tinysrgb&w=200&h=300&fit=crop" alt="The Art of Conscious Living" class="w-full h-full object-cover rounded-lg"> </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-white font-medium text-lg leading-tight">The Art of Conscious Living</h3>
                <p class="text-gray-400 text-sm">by Dr. Elena Martinez</p>
                <div class="flex items-center space-x-2">
                  <div class="flex items-center space-x-1"> <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg> <span class="text-yellow-400 text-sm font-medium">4.8</span> </div> <span class="text-gray-500 text-sm">2024</span>
                </div> <button class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4"> Borrow Books </button>
              </div>
            </div>
          </div>
          <!-- Book 6 -->
          <div class="swiper-slide">
            <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
              <div class="mb-4">
                <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden"> <img src="https://images.pexels.com/photos/1029141/pexels-photo-1029141.jpeg?auto=compress&cs=tinysrgb&w=200&h=300&fit=crop" alt="The Art of Conscious Living" class="w-full h-full object-cover rounded-lg"> </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-white font-medium text-lg leading-tight">The Art of Conscious Living</h3>
                <p class="text-gray-400 text-sm">by Dr. Elena Martinez</p>
                <div class="flex items-center space-x-2">
                  <div class="flex items-center space-x-1"> <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg> <span class="text-yellow-400 text-sm font-medium">4.8</span> </div> <span class="text-gray-500 text-sm">2024</span>
                </div> <button class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4"> Borrow Books </button>
              </div>
            </div>
          </div>
          <!-- Book 8 -->
          <div class="swiper-slide">
            <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
              <div class="mb-4">
                <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden"> <img src="https://images.pexels.com/photos/1029141/pexels-photo-1029141.jpeg?auto=compress&cs=tinysrgb&w=200&h=300&fit=crop" alt="The Art of Conscious Living" class="w-full h-full object-cover rounded-lg"> </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-white font-medium text-lg leading-tight">The Art of Conscious Living</h3>
                <p class="text-gray-400 text-sm">by Dr. Elena Martinez</p>
                <div class="flex items-center space-x-2">
                  <div class="flex items-center space-x-1"> <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg> <span class="text-yellow-400 text-sm font-medium">4.8</span> </div> <span class="text-gray-500 text-sm">2024</span>
                </div> <button class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4"> Borrow Books </button>
              </div>
            </div>
          </div>
          <!-- Book 8 -->
          <div class="swiper-slide">
            <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
              <div class="mb-4">
                <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden"> <img src="https://images.pexels.com/photos/1029141/pexels-photo-1029141.jpeg?auto=compress&cs=tinysrgb&w=200&h=300&fit=crop" alt="The Art of Conscious Living" class="w-full h-full object-cover rounded-lg"> </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-white font-medium text-lg leading-tight">The Art of Conscious Living</h3>
                <p class="text-gray-400 text-sm">by Dr. Elena Martinez</p>
                <div class="flex items-center space-x-2">
                  <div class="flex items-center space-x-1"> <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg> <span class="text-yellow-400 text-sm font-medium">4.8</span> </div> <span class="text-gray-500 text-sm">2024</span>
                </div> <button class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4"> Borrow Books </button>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-10">
          <div class="swiper-pagination"></div>
        </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 border-t border-gray-800 py-12 px-6">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
        <!-- Company Info -->
        <div class="space-y-4">
          <div class="text-xl font-bold">
            <span class="text-white">HOME</span><span class="text-gray-300">LIBRARY</span>
          </div>
          <p class="text-gray-400 text-sm leading-relaxed">
            Your digital gateway to thousands of books. Discover, explore, and embark on endless literary adventures from the comfort of your home.
          </p>
          <div class="flex space-x-4">
            <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
              <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
              </svg>
            </a>
            <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
              <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
              </svg>
            </a>
            <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
              <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
              </svg>
            </a>
            <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
              <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="space-y-4">
          <h3 class="text-white font-semibold text-lg">Quick Links</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Home</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Book Collection</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Categories</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">New Arrivals</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Popular Books</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">My Account</a></li>
          </ul>
        </div>

        <!-- Categories -->
        <div class="space-y-4">
          <h3 class="text-white font-semibold text-lg">Categories</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Fiction</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Science & Technology</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">History & Biography</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Business & Economics</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Philosophy & Psychology</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Arts & Literature</a></li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div class="space-y-4">
          <h3 class="text-white font-semibold text-lg">Contact</h3>
          <div class="space-y-3">
            <div class="flex items-start space-x-3">
              <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <div>
                <p class="text-gray-400 text-sm">123 Library Street</p>
                <p class="text-gray-400 text-sm">Reading City, RC 12345</p>
              </div>
            </div>
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <p class="text-gray-400 text-sm">info@homelibrary.com</p>
            </div>
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <p class="text-gray-400 text-sm">+1 (555) 123-4567</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Newsletter Signup -->
      <div class="border-t border-gray-800 pt-8 mb-8">
        <div class="max-w-md mx-auto text-center">
          <h3 class="text-white font-semibold text-lg mb-2">Stay Updated</h3>
          <p class="text-gray-400 text-sm mb-4">Subscribe to our newsletter for new book arrivals and library updates</p>
          <div class="flex space-x-2">
            <input type="email" placeholder="Enter your email" class="flex-1 bg-gray-800 text-white placeholder-gray-400 px-4 py-2 rounded-lg border border-gray-700 focus:border-gray-500 focus:outline-none text-sm">
            <button class="bg-white text-gray-900 px-6 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors text-sm">
              Subscribe
            </button>
          </div>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
        <p class="text-gray-400 text-sm">© 2024 HomeLibrary. All rights reserved.</p>
        <div class="flex space-x-6">
          <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Privacy Policy</a>
          <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Terms of Service</a>
          <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Cookie Policy</a>
        </div>
      </div>
    </div>
  </footer>
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
    // Mobile menu functionality
    const mobileMenuBtn = document.getElementById("mobileMenuBtn");
    const mobileMenu = document.getElementById("mobileMenu");
    const mobileMenuPanel = document.getElementById("mobileMenuPanel");
    const closeMobileMenu = document.getElementById("closeMobileMenu");

    function openMobileMenu() {
      mobileMenu.classList.remove("hidden");
      setTimeout(() => {
        mobileMenuPanel.classList.remove("translate-x-full");
      }, 10);
    }

    function closeMobileMenuFunc() {
      mobileMenuPanel.classList.add("translate-x-full");
      setTimeout(() => {
        mobileMenu.classList.add("hidden");
      }, 300);
    }

    mobileMenuBtn.addEventListener("click", openMobileMenu);
    closeMobileMenu.addEventListener("click", closeMobileMenuFunc);

    // Close menu when clicking outside
    mobileMenu.addEventListener("click", (e) => {
      if (e.target === mobileMenu) {
        closeMobileMenuFunc();
      }
    });

    // Swiper ni
    // First row
    var swiper1 = new Swiper(".mySwiper1", {
      slidesPerView: 1.2, // small preview of next card
      spaceBetween: 20,
      pagination: {
        el: ".mySwiper1 .swiper-pagination",
        clickable: true,
      },
      freeMode: true,
      grabCursor: true,
      breakpoints: {
        640: {
          slidesPerView: 2.5,
        },
        1024: {
          slidesPerView: 4,
        },
      },
    });

    // Second row
    var swiper2 = new Swiper(".mySwiper2", {
      slidesPerView: 1.2,
      spaceBetween: 20,
      pagination: {
        el: ".mySwiper2 .swiper-pagination",
        clickable: true,
      },
      freeMode: true,
      grabCursor: true,
      breakpoints: {
        640: {
          slidesPerView: 2.5,
        },
        1024: {
          slidesPerView: 4,
        },
      },
    });
  </script>
</body>

</html>