<nav class="navbar navbar-expand-lg fixed-top bg-dark" data-bs-theme="dark">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="index.html">
            <img src="logos/Brown_Elegant_Logo_Lawyer_Logo__6_-removebg-preview.png" alt="" width="250px" />
        </a>

        <!-- Navbar toggler button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <div class="navbar-toggler-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <div
                class="navbar-content-inner ms-lg-auto d-flex flex-column flex-lg-row align-lg-center gap-4 gap-lg-10 p-2 p-lg-0">
                <ul class="navbar-nav gap-lg-2 gap-xl-5">


                    <li class="nav-item">
                        <a class="nav-link " href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="about.php">About</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Services
                        </a>
                        <ul class="dropdown-menu megamenu megamenu-cols-2">
                            <li><a class="dropdown-item " href="example.php">Example</a></li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link " href="contact.php">Contact</a>
                    </li>

                    <?php
                    if (isset($_COOKIE['email']) || !empty($_COOKIE['email'])) {
                    ?>
                        <div class="">
                            <a href="./user/index.php" class="btn btn-outline-primary-dark">Dashboard</a>
                        </div>

                    <?php
                    } else {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                </ul>
                <div class="">
                    <a href="register.php" class="btn btn-outline-primary-dark">Register</a>
                </div>

            <?php
                    }
            ?>
            </div>
        </div>
    </div>
</nav>