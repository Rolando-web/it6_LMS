  <!-- Featured Books Section -->
  <section class="bg-gray-900 py-8 px-6">
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
        <div class="swiper-wrapper">
          <!-- Book 1 -->
          <?php foreach ($books as $book): ?>
            <div class="swiper-slide">
              <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
                <div class="mb-4">
                  <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center mb-4 justify-center relative overflow-hidden">
                    <img src="../admin/<?= htmlspecialchars($book['image'] ?? 'uploads/default.jpg') ?>"
                      alt="<?= htmlspecialchars($book['title']) ?>"
                      class="w-full h-full object-cover rounded-lg">
                  </div>
                </div>
                <div class="space-y-2">
                  <h3 class="text-white font-medium text-lg leading-tight"><?= htmlspecialchars($book['title']) ?></h3>
                  <p class="text-gray-400 text-sm"><?= htmlspecialchars($book['author']) ?></p>
                  <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-1"> <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg> <span class="text-yellow-400 text-sm font-medium">4.8</span> </div> <span class="text-gray-500 text-sm"><?= htmlspecialchars($book['publish_date']) ?></span>
                  </div>
                  <a href="./user-borrow.php"> <button class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4 cursor-pointer"> Borrow Book</button></a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="mt-10">
          <div class="swiper-pagination"></div>
        </div>
      </div>


      <!-- 🔹 Second Row Slider -->
      <div class="swiper mySwiper2">
        <div class="swiper-wrapper">
          <!-- Book 2 -->
          <?php foreach (array_reverse($books) as $book): ?>
            <div class="swiper-slide">
              <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-750 transition-colors group">
                <div class="mb-4">
                  <div class="w-full h-48 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden">
                    <img src="../admin/<?= htmlspecialchars($book['image'] ?? 'uploads/default.jpg') ?>"
                      alt="<?= htmlspecialchars($book['title']) ?>"
                      class="w-full h-full object-cover rounded-lg">
                  </div>
                </div>
                <div class="space-y-2">
                  <h3 class="text-white font-medium text-lg leading-tight"><?= htmlspecialchars($book['title']) ?></h3>
                  <p class="text-gray-400 text-sm"><?= htmlspecialchars($book['author']) ?></p>
                  <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-1">
                      <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <span class="text-yellow-400 text-sm font-medium">4.8</span>
                    </div>
                    <span class="text-gray-500 text-sm"><?= htmlspecialchars($book['publish_date']) ?></span>
                  </div>
                  <a href="./user-borrow.php">
                    <button class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors mt-4 cursor-pointer">
                      Borrow Book
                    </button>
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="mt-10">
          <div class="swiper-pagination"></div>
        </div>
      </div>

  </section>