<style>
    .navbar-nav {
        display: flex;
        list-style-type: none;
        margin: 0;
        padding: 0;
        background-color: #333;
    }

    .nav-item {
        margin-right: 10px;
    }

    .nav-item.register-login {
        border-right: 1px solid #bbb;
        padding-right: 10px;
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
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#" style="font-size: 1.5em;">Electrik</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'aboutus.php') echo 'active'; ?>" href="aboutus.php">About Us</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item register-login">
                    <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'registerAcct.php') echo 'active'; ?>" href="registerAcct.php">Register</a>
                </li>
                <li class="nav-item register-login">
                    <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'login.php') echo 'active'; ?>" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


