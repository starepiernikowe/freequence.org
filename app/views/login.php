<?php ob_start(); ?>

<div class="form-container">
    <h1>Login</h1>

    <?php if (isset($view_data['error'])): ?>
        <div class="error">
            <?php echo $view_data['error']; ?>
        </div>
    <?php endif; ?>

    <form action="do_login" method="post">
        <div>
            <label for="username">Username or Email:</label>
            <input type="text" id="username" name="username" required autocomplete="username">
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>
<?php include BASE_PATH . 'app/views/layout.php'; ?>