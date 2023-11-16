<!-- navbar.php -->

<style>
    .navbar-nav {
        display: flex;
        list-style-type: none;
        margin: 0;
        padding: 0;
        background-color: #1E1E1E;
    }

    .nav-item {
        margin-right: 10px;
    }

    .nav-item.register-login {
        padding-right: 10px;
        margin-right: 0; /* Reset the margin to override the previous style */
    }

    .nav-link {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }

    .nav-link:hover:not(.active) {
        background-color: #111;
    }

    .active {
        background-color: #04AA6D;
    }

    .no-border {
        border-right: none;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#" style="font-size: 1.5em;">Electrik</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>" href="index.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'aboutus.php') echo 'active'; ?>" href="aboutus.php">ABOUT US</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'register.php') echo 'active'; ?>" href="register.php">REGISTER</a>
                </li>
                <li class="nav-item register-login">
                    <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'login.php') echo 'active no-border'; ?>" href="login.php">LOGIN</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


