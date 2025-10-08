        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <div class="sidebar bg-dark text-light p-0" id="sidebar">
          <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h4 class="mb-0 fw-bold text-light text-2xl">
                ADMIN<span class="font-light">CONTROL</span>
              </h4>
              <button class="btn btn-sm d-md-none text-light" id="closeSidebar">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>

            <nav class="nav flex-column">
              <a class="nav-link text-light py-3 px-3" href="../admin/dashboard-admin.php" style="font-size: 16px;">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
              </a>
              <a class="nav-link text-light py-3 px-3" href="../admin/category-admin.php" style="font-size: 16px;">
                <i class="bi bi-collection me-2"></i>
                Category
              </a>
              <a class="nav-link text-light py-3 px-3" href="../admin/bookadmin.php" style="font-size: 16px;">
                <i class="bi bi-book me-2"></i>
                Books
              </a>
              <a class="nav-link text-light py-3 px-3" href="../admin/transaction.php" style="font-size: 16px;">
                <i class="bi bi-cash-coin me-2"></i>
                Transaction
              </a>
              <a class="nav-link text-light py-3 px-3" href="../admin/activity-log.php" style="font-size: 16px;">
                <i class="bi bi-journal-text me-2"></i>
                Activity Log
              </a>
              <a class="nav-link text-light py-3 px-3" href="../admin/useradmin.php" style="font-size: 16px;">
                <i class="bi bi-people me-2"></i>
                Users
              </a>
            </nav>
          </div>
          <form method="POST">
            <div class="position-absolute bottom-0 w-100 p-4">
              <a href="../login.php" class="text-decoration-none">
                <button class="btn text-light d-flex align-items-center" name="logout" style="font-size: 16px;">
                  <i class="bi bi-box-arrow-right me-2"></i>
                  Log Out
                </button></a>
            </div>
          </form>
        </div>