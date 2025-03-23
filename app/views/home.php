<?php ob_start(); ?>
<h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
<p>You are logged in.</p>
<h2>Your Entries</h2>
<a href="/add_entry">Add Entry</a>

<?php if (!empty($view_data['entries'])): ?>
    <ul>
    <?php foreach ($view_data['entries'] as $entry): ?>
        <li>
            <?php echo htmlspecialchars($entry['entry_name']); ?>
            (Created: <?php echo htmlspecialchars($entry['creation_date']); ?>)
            - <a href="/entry/view?id=<?php echo $entry['id']; ?>">View</a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>You have no entries yet.</p>
<?php endif; ?>

<a href="/logout">Logout</a>

<?php $content = ob_get_clean(); ?>
<?php include BASE_PATH . 'app/views/layout.php'; ?>