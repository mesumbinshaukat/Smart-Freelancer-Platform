<div class="topbar d-flex align-items-center">
    <nav class="navbar navbar-expand gap-3">
        <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
        </div>




        <div class="user-box dropdown px-3">
            <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="assets/images/avatars/user.png" class="user-img" alt="user avatar">
                <div class="user-info">
                    <p class="user-name mb-0"><?php echo $user_details["name"] ?></p>
                    <p class="designattion mb-0"><?php echo $user_details["email"] ?></p>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                            class="bx bx-user fs-5"></i><span>Profile</span></a>
                </li>
                <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                            class="bx bx-cog fs-5"></i><span>Settings</span></a>
                </li>
                <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                            class="bx bx-home-circle fs-5"></i><span>Dashboard</span></a>
                </li>
                <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                            class="bx bx-dollar-circle fs-5"></i><span>Earnings</span></a>
                </li>
                <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                            class="bx bx-download fs-5"></i><span>Downloads</span></a>
                </li>
                <li>
                    <div class="dropdown-divider mb-0"></div>
                </li>
                <li><a class="dropdown-item d-flex align-items-center" href="logout.php"><i
                            class="bx bx-log-out-circle"></i><span>Logout</span></a>
                </li>
            </ul>
        </div>
    </nav>
</div>