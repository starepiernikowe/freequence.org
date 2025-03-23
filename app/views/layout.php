<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($view_data['page_title']) ? $view_data['page_title'] . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="icon" href="/public/favicon.png" type="image/png"> </head>
<body>
    <div class="container">
        <div class="top-bar">
            <span class="site-title">Get on the Right Wavelength!</span>
            <div class="hamburger-menu">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="main-content">
            <?php echo $content; ?>
        </div>
        <div class="bottom-bar">
            </div>
    </div>

    <div class="nav-menu">
        <div class="nav-close-button">X</div>
        <div class="nav-content">
             <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/home">Profile</a>
                <a href="/add_entry">Add Entry</a> <a href="/logout">Logout</a>
            <?php else: ?>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
            <?php endif; ?>

        </div>
    </div>

    <script src="/public/js/script.js"></script>
</body>
</html>