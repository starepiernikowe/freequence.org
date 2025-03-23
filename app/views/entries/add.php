<?php ob_start(); ?>

<div class="form-container">
    <h1>Add Entry</h1>

    <?php if (!empty($view_data['errors'])): ?>
        <div class="error">
            <ul>
                <?php foreach ($view_data['errors'] as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="/entry/create" method="post">
        <div>
            <label for="entry_name">Entry Name:</label>
            <input type="text" id="entry_name" name="entry_name" required autocomplete="name">
        </div>
        <div>
            <label for="template_id">Template:</label>
            <select id="template_id" name="template_id" required>
                <?php
                $systemTemplateId = null; // Variable to store the ID of the "System" template
                foreach ($view_data['templates'] as $template): ?>
                    <?php
                        $isSystem = is_null($template['user_id']) && $template['name'] === 'System';
                        if ($isSystem) {
                            $systemTemplateId = $template['id']; // Store System template ID
                        }
                    ?>
                    <option value="<?php echo $template['id']; ?>" data-is-system="<?php echo $isSystem ? '1' : '0'; ?>">
                        <?php echo htmlspecialchars($template['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="location_lat">Latitude:</label>
            <input type="text" id="location_lat" name="location_lat" required autocomplete="address-line1">
        </div>
        <div>
            <label for="location_lng">Longitude:</label>
            <input type="text" id="location_lng" name="location_lng" required autocomplete="address-line2">
        </div>
        <div>
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment"></textarea>
        </div>
        <div id="description_container" style="display: none;">
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" autocomplete="off">
        </div>

        <div id="parameters_container"></div>

        <div>
            <label for="expiration_date">Expiration Date:</label>
            <select id="expiration_date" name="expiration_date" required>
                <option value="1">1 Year</option>
                <option value="2">2 Years</option>
            </select>
        </div>
        <div>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="reserved">Reserved</option>
            </select>
        </div>
        <div>
            <button type="submit">Add Entry</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const templateSelect = document.getElementById('template_id');
        const descriptionContainer = document.getElementById('description_container');
        const parametersContainer = document.getElementById('parameters_container');

        function toggleDescription() {
            const selectedOption = templateSelect.options[templateSelect.selectedIndex];
            const isSystem = selectedOption ? selectedOption.dataset.isSystem === '1' : false;
            descriptionContainer.style.display = isSystem ? 'block' : 'none';
        }

       function fetchParameters(templateId) {
            if (!templateId) {
                parametersContainer.innerHTML = '';
                return;
            }

            fetch(`/templates/parameters?template_id=${templateId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Network response was not ok: ${response.status}`);
                    }
                    return response.json();
                })
                .then(parameters => {
                    parametersContainer.innerHTML = '';

                    if (parameters.length === 0) {
                        parametersContainer.innerHTML = '<p>No parameters for this template.</p>';
                        return;
                    }

                    parameters.forEach(parameter => {
                        const div = document.createElement('div');
                        const label = document.createElement('label');
                        label.htmlFor = `param_${parameter.id}`;
                        label.textContent = parameter.name + ':';
                        div.appendChild(label);

                        let input;
                        switch (parameter.data_type) {
                            case 'text':
                                input = document.createElement('input');
                                input.type = 'text';
                                input.classList.add('dynamic-input');
                                input.id = `param_${parameter.id}`;
                                input.name = `parameters[${parameter.id}]`;
                                if (parameter.default_value) {
                                  input.value = parameter.default_value;
                                 }
                                break;
                            case 'integer':
                                input = document.createElement('input');
                                input.type = 'number';
                                 input.classList.add('dynamic-input');
                                input.id = `param_${parameter.id}`;
                                input.name = `parameters[${parameter.id}]`;
                                if (parameter.default_value) {
                                  input.value = parameter.default_value;
                                 }
                                break;
                            case 'float':
                                input = document.createElement('input');
                                input.type = 'number';
                                input.step = '0.00000001';
                                 input.classList.add('dynamic-input');
                                input.id = `param_${parameter.id}`;
                                input.name = `parameters[${parameter.id}]`;
                                 if (parameter.default_value) {
                                  input.value = parameter.default_value;
                                 }
                                break;
                            case 'boolean':
                                input = document.createElement('input');
                                input.type = 'checkbox';
                                input.classList.add('dynamic-input'); // Ensure this class is present
                                input.id = `param_${parameter.id}`;
                                input.name = `parameters[${parameter.id}]`;
                                input.value = '1';
                                if (parameter.default_value === '1') {
                                   input.checked = true;
                                 }
                                break;
                            case 'enum':
                                input = document.createElement('select');
                                 input.classList.add('dynamic-input');
                                input.id = `param_${parameter.id}`;
                                input.name = `parameters[${parameter.id}]`;
                                let options = parameter.options.split(',');
                                options.forEach(option => {
                                    const optionElement = document.createElement('option');
                                    optionElement.value = option.trim();
                                    optionElement.textContent = option.trim();
                                    input.appendChild(optionElement);
                                      if (parameter.default_value === option.trim()) {
                                        optionElement.selected = true;
                                    }
                                });
                                break;
                        }
                        if(input){
                           div.appendChild(input);
                           parametersContainer.appendChild(div);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching parameters:', error);
                    parametersContainer.innerHTML = '<p>Error loading parameters.</p>';
                });
        }


        templateSelect.addEventListener('change', function() {
            toggleDescription();
            fetchParameters(this.value);
        });

        <?php if ($systemTemplateId): ?>
            templateSelect.value = <?php echo $systemTemplateId; ?>;
        <?php endif; ?>
        toggleDescription();
        fetchParameters(templateSelect.value);

    });
</script>

<?php $content = ob_get_clean(); ?>
<?php include BASE_PATH . 'app/views/layout.php'; ?>