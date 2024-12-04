<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-label-secondary"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="ri-menu-fill ri-24px"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="ri-search-line ri-22px me-2"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                    aria-label="Search..." />
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
    <!-- User Profile -->
    <li class="nav-item">
        <a href="javascript:void(0);" class="nav-link p-0 d-flex align-items-center">
            <div class="avatar avatar-online me-2">
                <!-- Use the profile image dynamically -->
                <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="User Avatar"
                     class="w-px-40 h-auto rounded-circle" />
            </div>
            <div class="d-none d-sm-block">
                <!-- Use the user’s full name dynamically -->
                <span class="fw-semibold"><?php echo htmlspecialchars($fullName); ?></span>
            </div>
        </a>
    </li>

  

    <!-- Logout link -->
   
</ul>



    </div>
</nav>