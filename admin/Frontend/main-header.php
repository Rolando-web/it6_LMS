 <div class="d-flex justify-content-between align-items-center p-4 header-border">
   <div class="d-flex align-items-center">
     <button class="btn btn-sm text-light d-md-none me-3" id="openSidebar">
       <i class="bi bi-list fs-4"></i>
     </button>
     <h2 class="text-light mb-0">Transaction Management</h2>
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