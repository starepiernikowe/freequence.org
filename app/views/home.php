<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - <?php echo SITE_NAME; ?></title>
    <style>
        body { font-family: sans-serif; }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>You are logged in.</p>
    <a href="/logout">Logout</a>
</body>
</html>