<?php ob_start(); ?>

<div class="entry-view">
    <h1>View Entry: <?php echo htmlspecialchars($view_data['entry']['entry_name']); ?></h1>

    <div class="entry-details">
        <p><strong>Template:</strong> <?php echo htmlspecialchars($view_data['entry']['template_name']); ?></p>
        <p><strong>Creation Date:</strong> <?php echo htmlspecialchars($view_data['entry']['creation_date']); ?></p>
        <p><strong>Expiration Date:</strong> <?php echo htmlspecialchars($view_data['entry']['expiration_date']); ?></p>
        <p><strong>Latitude:</strong> <?php echo htmlspecialchars($view_data['entry']['location_lat']); ?></p>
        <p><strong>Longitude:</strong> <?php echo htmlspecialchars($view_data['entry']['location_lng']); ?></p>
        <?php if ($view_data['entry']['comment']): ?>
            <p><strong>Comment:</strong> <?php echo htmlspecialchars($view_data['entry']['comment']); ?></p>
        <?php endif; ?>
        <?php if ($view_data['entry']['description']): ?>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($view_data['entry']['description']); ?></p>
        <?php endif; ?>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($view_data['entry']['status']); ?></p>
    </div>

    <h2>Parameters</h2>
    <div class="entry-parameters">
    <?php if (!empty($view_data['entry']['parameters'])): ?>
        <table class="parameter-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($view_data['entry']['parameters'] as $parameter): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($parameter['name']); ?></td>
                        <td>
                            <?php
                            if ($parameter['data_type'] === 'boolean') {
                                echo $parameter['value'] === '1' ? 'Yes' : 'No';
                            } else {
                                echo htmlspecialchars($parameter['value']);
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No parameters for this entry.</p>
    <?php endif; ?>
    </div>
     <a href="/home" class="back-button">Back to Home</a>
</div>

<?php $content = ob_get_clean(); ?>
<?php include BASE_PATH . 'app/views/layout.php'; ?>