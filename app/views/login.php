<?php ob_start(); ?>
<div class="form-container">
    <h1>Login</h1>

    <?php if (!empty($view_data['errors'])): ?>
        <div class="error">
            <ul>
                <?php foreach ($view_data['errors'] as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="do_login" method="post">
        <div>
            <label for="username">Username or Email:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</div>
<?php $content = ob_get_clean(); ?>
<?php include 'layout.php'; ?>