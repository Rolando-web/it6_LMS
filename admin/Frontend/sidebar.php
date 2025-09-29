        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <div class="sidebar bg-dark text-light p-0" id="sidebar">
          <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h4 class="mb-0 fw-bold text-light">
                ADMIN<span class="font-light">CONTROL</span>
              </h4>
              <button class="btn btn-sm d-md-none text-light" id="closeSidebar">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>

            <nav class="nav flex-column">
              <a class="nav-link text-light py-3 px-3" href="#" style="font-size: 16px;">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
              </a>
              <a class="nav-link text-light py-3 px-3" href="#" style="font-size: 16px;">
                <i class="bi bi-collection me-2"></i>
                Category
              </a>
              <a class="nav-link text-light py-3 px-3" href="../admin/bookadmin.php" style="font-size: 16px;">
                <i class="bi bi-book me-2"></i>
                Books
              </a>
              <a class="nav-link text-light py-3 px-3" href="../admin/transaction.php" style="font-size: 16px;">
                <i class="bi bi-book me-2"></i>
                Transaction
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

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
          <!-- Header -->
          <div class="d-flex justify-content-between align-items-center p-4 header-border">
            <div class="d-flex align-items-center">
              <button class="btn btn-sm text-light d-md-none me-3" id="openSidebar">
                <i class="bi bi-list fs-4"></i>
              </button>
              <h2 class="text-light mb-0">Book Management</h2>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <!-- Right: Profile Info -->
              <div class="d-flex align-items-center">
                <!-- Desktop View -->
                <div class="d-none d-sm-block text-end me-3">
                  <div class="text-light">
                    <?php
                    $user = $auth->user();
                    if ($user && isset($user['first_name'], $user['last_name'])) {
                      echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
                    } else {
                      echo 'Guest';
                    }
                    ?>
                  </div>
                  <small class="text-white opacity-50">Admin</small>
                </div>

                <!-- Mobile Dropdown -->
                <div class="dropdown d-sm-none">
                  <a
                    href="#"
                    class="d-flex align-items-center"
                    id="profileDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img
                      src="../image/willan.jpg"
                      alt="Profile"
                      class="rounded-circle"
                      width="40"
                      height="40" />
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><span class="dropdown-item-text fw-bold">
                        <?php
                        $user = $auth->user();
                        if ($user && isset($user['first_name'], $user['last_name'])) {
                          echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
                        } else {
                          echo 'Guest';
                        }
                        ?>
                      </span></li>
                    <li><span class="dropdown-item-text text-muted">Admin</span></li>
                  </ul>
                </div>

                <!-- Desktop Profile Image -->
                <img
                  src="../image/willan.jpg"
                  alt="Profile"
                  class="rounded-circle d-none d-sm-block"
                  width="40"
                  height="40" />
              </div>
            </div>
          </div>