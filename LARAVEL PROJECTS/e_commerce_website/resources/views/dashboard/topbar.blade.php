<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top" style="margin-left:250px;">
    <div class="container-fluid">

        <button class="btn btn-outline-secondary d-lg-none me-2" id="toggleSidebar">
            <i class="fa fa-bars"></i>
        </button>

        <h4 class="mt-1 fw-bold">Admin Panel</h4>

        <ul class="navbar-nav ms-auto align-items-center">

            <!-- Notifications -->
            <li class="nav-item me-4">
                <a href="#" class="nav-link position-relative">
                    <i class="fa fa-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle"></span>
                </a>
            </li>

            <!-- Profile Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown"
                   role="button" data-bs-toggle="dropdown" aria-expanded="false">

                    <img src="https://ui-avatars.com/api/?name=Admin" class="rounded-circle me-2" width="35" height="35">
                    <span>Admin</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fa fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa fa-cog me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </li>
        </ul>

    </div>
</nav>
