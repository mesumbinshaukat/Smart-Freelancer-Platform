<nav class="navbar navbar-expand-lg fixed-top bg-dark" data-bs-theme="dark">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="index.html">
            <img src="assets/images/logo.svg" alt="" width="165" />
        </a>

        <!-- Navbar toggler button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <div class="navbar-toggler-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <div class="navbar-content-inner ms-lg-auto d-flex flex-column flex-lg-row align-lg-center gap-4 gap-lg-10 p-2 p-lg-0">
                <ul class="navbar-nav gap-lg-2 gap-xl-5">
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false" aria-current="page">
                            Home
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.html">Home one</a></li>
                            <li><a class="dropdown-item" href="index-lite.html">Home one lite</a></li>
                            <li><a class="dropdown-item" href="index-2.html">Home two</a></li>
                            <li><a class="dropdown-item" href="index-2-lite.html">Home two lite</a></li>
                        </ul>
                    </li> -->
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Pages
                        </a>
                        <ul class="dropdown-menu megamenu megamenu-cols-2">
                            <li><a class="dropdown-item " href="about.html">About</a></li>
                            <li><a class="dropdown-item " href="about-lite.html">About lite</a></li>
                            <li><a class="dropdown-item " href="contact.html">Contact</a></li>
                            <li><a class="dropdown-item " href="contact-lite.html">Contact lite</a></li>
                            <li><a class="dropdown-item " href="blog.html">Blog</a></li>
                            <li><a class="dropdown-item " href="blog-lite.html">Blog lite</a></li>
                            <li><a class="dropdown-item " href="article.html">Article</a></li>
                            <li><a class="dropdown-item " href="article-lite.html">Article lite</a></li>
                            <li><a class="dropdown-item " href="use-cases.html">Use cases</a></li>
                            <li><a class="dropdown-item " href="use-cases-lite.html">Use cases lite</a></li>
                            <li><a class="dropdown-item " href="use-cases-details.html">Case details</a></li>
                            <li><a class="dropdown-item " href="use-cases-details-lite.html">Case details lite</a></li>
                            <li><a class="dropdown-item " href="pricing-plan.html">Pricing</a></li>
                            <li><a class="dropdown-item " href="pricing-plan-lite.html">Pricing lite</a></li>
                            <li><a class="dropdown-item" href="login.html">Login</a></li>
                            <li><a class="dropdown-item" href="login-lite.html">Login lite</a></li>
                            <li><a class="dropdown-item" href="register.html">Register</a></li>
                            <li><a class="dropdown-item" href="register-lite.html">Register lite</a></li>
                            <li><a class="dropdown-item" href="forgot-password.html">Forgot password</a></li>
                            <li><a class="dropdown-item" href="forgot-password-lite.html">Forgot password lite</a></li>
                            <li><a class="dropdown-item " href="404.html">404</a></li>
                            <li><a class="dropdown-item " href="404-lite.html">404 lite</a></li>
                        </ul>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link " href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="about.php">About</a>
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