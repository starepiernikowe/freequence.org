<?php ob_start(); ?>
<h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
<p>You are logged in.</p>

<?php $content = ob_get_clean(); ?>
<?php include 'layout.php'; ?>