<?php ob_start(); ?>
<h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
<p>You are logged in.</p>
<h2>Your Entries</h2>
    <ul>
      <?php
        require_once BASE_PATH . 'app/models/Entry.php';
        $entryModel = new Entry();
        $entries = $entryModel->getEntriesByUserId($_SESSION['user_id']);
        if(!empty($entries)):
            foreach($entries as $entry): ?>
                <li> <?php echo htmlspecialchars($entry['entry_name']); ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No entries found yet</li>
        <?php endif; ?>
    </ul>
<?php $content = ob_get_clean(); ?>
<?php include BASE_PATH . 'app/views/layout.php'; ?>