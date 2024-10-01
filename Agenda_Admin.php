<?php
// Laad de bestaande gegevens uit het roosterbestand
$schedule = [];
if (file_exists('schedule.json')) {
    $schedule = json_decode(file_get_contents('schedule.json'), true);
}

// Voeg een nieuwe medewerker toe
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        $schedule = $_POST['schedule'];
        file_put_contents('schedule.json', json_encode($schedule));
    } elseif (isset($_POST['add'])) {
        $schedule[] = [
            'name' => '',
            'monday' => '', 'custom_monday' => '',
            'tuesday' => '', 'custom_tuesday' => '',
            'wednesday' => '', 'custom_wednesday' => '',
            'thursday' => '', 'custom_thursday' => '',
            'friday' => '', 'custom_friday' => '',
            'start' => '', 'end' => ''
        ];
    } elseif (isset($_POST['remove'])) {
        if (isset($_POST['remove_index']) && isset($schedule[$_POST['remove_index']])) {
            $removed_row = $schedule[$_POST['remove_index']];
            file_put_contents('removed.json', json_encode($removed_row));
            unset($schedule[$_POST['remove_index']]);
        }
    } elseif (isset($_POST['undo'])) {
        if (file_exists('removed.json')) {
            $removed_row = json_decode(file_get_contents('removed.json'), true);
            $schedule[] = $removed_row;
            unlink('removed.json');
        }
    } elseif (isset($_POST['reset'])) {
        if (file_exists('reset.json')) {
            $schedule = json_decode(file_get_contents('reset.json'), true);
            file_put_contents('schedule.json', json_encode($schedule));
        }
    }
    file_put_contents('schedule.json', json_encode(array_values($schedule)));
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Rooster Bewerken</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .inline-input {
            display: inline-block;
            width: 50%;
        }
        .hidden-input {
            display: none;
        }
    </style>
    <script>
        // JavaScript functie om een custom input te tonen of te verbergen
        function toggleCustomInput(selectElement, customInputId, selectId) {
            var customInput = document.getElementById(customInputId);
            var selectInput = document.getElementById(selectId);

            if (selectElement.value === 'Eigen invoer') {
                customInput.classList.remove('hidden-input');
                customInput.removeAttribute('readonly');
                selectInput.classList.add('hidden-select');
            } else {
                customInput.classList.add('hidden-input');
                customInput.value = '';
                customInput.setAttribute('readonly', true);
                selectInput.classList.remove('hidden-select');
            }
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center display-4 mb-4">Rooster Bewerken</h1>
        <form action="Agenda_Admin.php" method="post">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Medewerker</th>
                            <th>Maandag</th>
                            <th>Dinsdag</th>
                            <th>Woensdag</th>
                            <th>Donderdag</th>
                            <th>Vrijdag</th>
                            <th>Starttijd</th>
                            <th>Eindtijd</th>
                            <th>Verwijder</th>
                        </tr>
                    </thead>
                    <tbody id="schedule-rows">
                        <?php foreach ($schedule as $index => $row): ?>
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="schedule[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($row['name']); ?>" placeholder="Naam medewerker">
                            </td>
                            <td>
                                <select id="select_monday_<?php echo $index; ?>" class="form-select select-inline" name="schedule[<?php echo $index; ?>][monday]" onchange="toggleCustomInput(this, 'custom_monday_<?php echo $index; ?>', 'select_monday_<?php echo $index; ?>')">
                                    <option value=""> </option>
                                    <option value="Halve dag" <?php echo $row['monday'] == 'Halve dag' ? 'selected' : ''; ?>>Halve dag</option>
                                    <option value="Vrij" <?php echo $row['monday'] == 'Vrij' ? 'selected' : ''; ?>>Vrij</option>
                                    <option value="Ziek" <?php echo $row['monday'] == 'Ziek' ? 'selected' : ''; ?>>Ziek</option>
                                    <option value="Verlof" <?php echo $row['monday'] == 'Verlof' ? 'selected' : ''; ?>>Verlof</option>
                                    <option value="School" <?php echo $row['monday'] == 'School' ? 'selected' : ''; ?>>School</option>
				    <option value="Thuis werken" <?php echo $row['monday'] == 'Thuis werken' ? 'selected' : ''; ?>>Thuis werken</option>
                                    <option value="Eigen invoer" <?php echo $row['monday'] == 'Eigen invoer' ? 'selected' : ''; ?>>Eigen invoer</option>
                                </select>
                                <input type="text" class="form-control inline-input <?php echo $row['monday'] == 'Eigen invoer' ? '' : 'hidden-input'; ?>" name="schedule[<?php echo $index; ?>][custom_monday]" id="custom_monday_<?php echo $index; ?>" placeholder="Eigen invoer" value="<?php echo htmlspecialchars($row['custom_monday'] ?? ''); ?>" <?php echo $row['monday'] == 'Eigen invoer' ? '' : 'readonly'; ?>>
                            </td>
                            <td>
                                <select id="select_tuesday_<?php echo $index; ?>" class="form-select select-inline" name="schedule[<?php echo $index; ?>][tuesday]" onchange="toggleCustomInput(this, 'custom_tuesday_<?php echo $index; ?>', 'select_tuesday_<?php echo $index; ?>')">
                                    <option value=""> </option>
                                    <option value="Halve dag" <?php echo $row['tuesday'] == 'Halve dag' ? 'selected' : ''; ?>>Halve dag</option>
                                    <option value="Vrij" <?php echo $row['tuesday'] == 'Vrij' ? 'selected' : ''; ?>>Vrij</option>
                                    <option value="Ziek" <?php echo $row['tuesday'] == 'Ziek' ? 'selected' : ''; ?>>Ziek</option>
                                    <option value="Verlof" <?php echo $row['tuesday'] == 'Verlof' ? 'selected' : ''; ?>>Verlof</option>
                                    <option value="School" <?php echo $row['tuesday'] == 'School' ? 'selected' : ''; ?>>School</option>
				    <option value="Thuis werken" <?php echo $row['tuesday'] == 'Thuis werken' ? 'selected' : ''; ?>>Thuis werken</option>
                                    <option value="Eigen invoer" <?php echo $row['tuesday'] == 'Eigen invoer' ? 'selected' : ''; ?>>Eigen invoer</option>
                                </select>
                                <input type="text" class="form-control inline-input <?php echo $row['tuesday'] == 'Eigen invoer' ? '' : 'hidden-input'; ?>" name="schedule[<?php echo $index; ?>][custom_tuesday]" id="custom_tuesday_<?php echo $index; ?>" placeholder="Eigen invoer" value="<?php echo htmlspecialchars($row['custom_tuesday'] ?? ''); ?>" <?php echo $row['tuesday'] == 'Eigen invoer' ? '' : 'readonly'; ?>>
                            </td>
                            <td>
                                <select id="select_wednesday_<?php echo $index; ?>" class="form-select select-inline" name="schedule[<?php echo $index; ?>][wednesday]" onchange="toggleCustomInput(this, 'custom_wednesday_<?php echo $index; ?>', 'select_wednesday_<?php echo $index; ?>')">
                                    <option value=""> </option>
                                    <option value="Halve dag" <?php echo $row['wednesday'] == 'Halve dag' ? 'selected' : ''; ?>>Halve dag</option>
                                    <option value="Vrij" <?php echo $row['wednesday'] == 'Vrij' ? 'selected' : ''; ?>>Vrij</option>
                                    <option value="Ziek" <?php echo $row['wednesday'] == 'Ziek' ? 'selected' : ''; ?>>Ziek</option>
                                    <option value="Verlof" <?php echo $row['wednesday'] == 'Verlof' ? 'selected' : ''; ?>>Verlof</option>
                                    <option value="School" <?php echo $row['wednesday'] == 'School' ? 'selected' : ''; ?>>School</option>
				    <option value="Thuis werken" <?php echo $row['wednesday'] == 'Thuis werken' ? 'selected' : ''; ?>>Thuis werken</option>
                                    <option value="Eigen invoer" <?php echo $row['wednesday'] == 'Eigen invoer' ? 'selected' : ''; ?>>Eigen invoer</option>
                                </select>
                                <input type="text" class="form-control inline-input <?php echo $row['wednesday'] == 'Eigen invoer' ? '' : 'hidden-input'; ?>" name="schedule[<?php echo $index; ?>][custom_wednesday]" id="custom_wednesday_<?php echo $index; ?>" placeholder="Eigen invoer" value="<?php echo htmlspecialchars($row['custom_wednesday'] ?? ''); ?>" <?php echo $row['wednesday'] == 'Eigen invoer' ? '' : 'readonly'; ?>>
                            </td>
                            <td>
                                <select id="select_thursday_<?php echo $index; ?>" class="form-select select-inline" name="schedule[<?php echo $index; ?>][thursday]" onchange="toggleCustomInput(this, 'custom_thursday_<?php echo $index; ?>', 'select_thursday_<?php echo $index; ?>')">
                                    <option value=""> </option>
                                    <option value="Halve dag" <?php echo $row['thursday'] == 'Halve dag' ? 'selected' : ''; ?>>Halve dag</option>
                                    <option value="Vrij" <?php echo $row['thursday'] == 'Vrij' ? 'selected' : ''; ?>>Vrij</option>
                                    <option value="Ziek" <?php echo $row['thursday'] == 'Ziek' ? 'selected' : ''; ?>>Ziek</option>
                                    <option value="Verlof" <?php echo $row['thursday'] == 'Verlof' ? 'selected' : ''; ?>>Verlof</option>
                                    <option value="School" <?php echo $row['thursday'] == 'School' ? 'selected' : ''; ?>>School</option>
				    <option value="Thuis werken" <?php echo $row['thursday'] == 'Thuis werken' ? 'selected' : ''; ?>>Thuis werken</option>
                                    <option value="Eigen invoer" <?php echo $row['thursday'] == 'Eigen invoer' ? 'selected' : ''; ?>>Eigen invoer</option>
                                </select>
                                <input type="text" class="form-control inline-input <?php echo $row['thursday'] == 'Eigen invoer' ? '' : 'hidden-input'; ?>" name="schedule[<?php echo $index; ?>][custom_thursday]" id="custom_thursday_<?php echo $index; ?>" placeholder="Eigen invoer" value="<?php echo htmlspecialchars($row['custom_thursday'] ?? ''); ?>" <?php echo $row['thursday'] == 'Eigen invoer' ? '' : 'readonly'; ?>>
                            </td>
                            <td>
                                <select id="select_friday_<?php echo $index; ?>" class="form-select select-inline" name="schedule[<?php echo $index; ?>][friday]" onchange="toggleCustomInput(this, 'custom_friday_<?php echo $index; ?>', 'select_friday_<?php echo $index; ?>')">
                                    <option value=""> </option>
                                    <option value="Halve dag" <?php echo $row['friday'] == 'Halve dag' ? 'selected' : ''; ?>>Halve dag</option>
                                    <option value="Vrij" <?php echo $row['friday'] == 'Vrij' ? 'selected' : ''; ?>>Vrij</option>
                                    <option value="Ziek" <?php echo $row['friday'] == 'Ziek' ? 'selected' : ''; ?>>Ziek</option>
                                    <option value="Verlof" <?php echo $row['friday'] == 'Verlof' ? 'selected' : ''; ?>>Verlof</option>
                                    <option value="School" <?php echo $row['friday'] == 'School' ? 'selected' : ''; ?>>School</option>
				    <option value="Thuis werken" <?php echo $row['friday'] == 'Thuis werken' ? 'selected' : ''; ?>>Thuis werken</option>
                                    <option value="Eigen invoer" <?php echo $row['friday'] == 'Eigen invoer' ? 'selected' : ''; ?>>Eigen invoer</option>
                                </select>
                                <input type="text" class="form-control inline-input <?php echo $row['friday'] == 'Eigen invoer' ? '' : 'hidden-input'; ?>" name="schedule[<?php echo $index; ?>][custom_friday]" id="custom_friday_<?php echo $index; ?>" placeholder="Eigen invoer" value="<?php echo htmlspecialchars($row['custom_friday'] ?? ''); ?>" <?php echo $row['friday'] == 'Eigen invoer' ? '' : 'readonly'; ?>>
                            </td>
                            <td>
                                <input type="time" class="form-control" name="schedule[<?php echo $index; ?>][start]" value="<?php echo htmlspecialchars($row['start']); ?>">
                            </td>
                            <td>
                                <input type="time" class="form-control" name="schedule[<?php echo $index; ?>][end]" value="<?php echo htmlspecialchars($row['end']); ?>">
                            </td>
                            <td>
                                <button type="submit" name="remove" class="btn btn-danger" value="<?php echo $index; ?>">Verwijder</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                <button type="submit" name="save" class="btn btn-success">Opslaan</button>
                <button type="submit" name="add" class="btn btn-primary">Medewerker Toevoegen</button>
                <button type="submit" name="reset" class="btn btn-warning">Reset</button>
                <button type="submit" name="undo" class="btn btn-secondary">Undo</button>
            </div>
        </form>
    </div>
<script src="agenda.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
