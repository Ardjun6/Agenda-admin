<?php
// Laad de bestaande gegevens uit het roosterbestand
$schedule = [];
if (file_exists('schedule.json')) {
    $schedule = json_decode(file_get_contents('schedule.json'), true);
}

// Voeg een nieuwe medewerker toe of verwijder een medewerker
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
    } 
    file_put_contents('schedule.json', json_encode(array_values($schedule)));
}

// Datum toevoegen voor de huidige weekdagen
setlocale(LC_TIME, 'nl_NL.UTF-8');
$today = new DateTime();
$monday = clone $today;
$monday->modify(('Monday' == $today->format('l')) ? 'this Monday' : 'last Monday');

$weekDates = [];
$daysOfWeek = ['Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag'];
for ($i = 0; $i < 5; $i++) {
    $day = clone $monday;
    $day->modify("+$i days");
    $weekDates[] = $daysOfWeek[$i] . ' ' . $day->format('d-m-y');
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Rooster Bewerken</title>
    <style>
    .inline-input {
        display: block;
        width: 42.5%;
        text-align: center;
        margin: 0 auto;
    }

    input[type="text"] {
        text-align: center;
    }

    select {
        width: 50%;
        margin-bottom: 5px;
    }

    .hidden-input {
        display: none;
    }

    .btn-container {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    </style>
    <script>
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
        <h1 class="text-center display-4 mb-4">Rooster Beheer</h1>
        <form action="Agenda_Admin.php" method="post">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Medewerker</th>
                            <?php foreach ($weekDates as $date): ?>
                                <th><?php echo htmlspecialchars($date); ?></th>
                            <?php endforeach; ?>
                            <th>Starttijd</th>
                            <th>Eindtijd</th>
                            <th>Verwijder</th>
                        </tr>
                    </thead>
                    <tbody id="schedule-rows">
                        <?php foreach ($schedule as $index => $row): ?>
                        <tr>
                            <!-- Naam medewerker -->
                            <td>
                                <input type="text" class="form-control" name="schedule[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($row['name']); ?>" placeholder="Naam medewerker" maxlength="20">
                            </td>
                            
                            <!-- Monday -->
                            <td>
                                <select id="select_monday_<?php echo $index; ?>" class="form-select" name="schedule[<?php echo $index; ?>][monday]" onchange="toggleCustomInput(this, 'custom_monday_<?php echo $index; ?>', 'select_monday_<?php echo $index; ?>')">
                                    <option value="" class="leeg-optie">Leeg</option>
                                    <option value="Halve dag" <?php echo $row['monday'] == 'Halve dag' ? 'selected' : ''; ?>>Halve dag</option>
                                    <option value="Vrij" <?php echo $row['monday'] == 'Vrij' ? 'selected' : ''; ?>>Vrij</option>
                                    <option value="Ziek" <?php echo $row['monday'] == 'Ziek' ? 'selected' : ''; ?>>Ziek</option>
                                    <option value="Verlof" <?php echo $row['monday'] == 'Verlof' ? 'selected' : ''; ?>>Verlof</option>
                                    <option value="School" <?php echo $row['monday'] == 'School' ? 'selected' : ''; ?>>School</option>
                                    <option value="Thuis werken" <?php echo $row['monday'] == 'Thuis werken' ? 'selected' : ''; ?>>Thuis werken</option>
                                    <option value="Eigen invoer" <?php echo $row['monday'] == 'Eigen invoer' ? 'selected' : ''; ?>>Eigen invoer</option>
                                </select>
                                <input type="text" class="form-control inline-input <?php echo $row['monday'] == 'Eigen invoer' ? '' : 'hidden-input'; ?>" name="schedule[<?php echo $index; ?>][custom_monday]" id="custom_monday_<?php echo $index; ?>" placeholder="Eigen invoer" value="<?php echo htmlspecialchars($row['custom_monday'] ?? ''); ?>" maxlength="20" <?php echo $row['monday'] == 'Eigen invoer' ? '' : 'readonly'; ?>>
                            </td>
                            
                            <!-- Tuesday -->
                            <td>
                                <select id="select_tuesday_<?php echo $index; ?>" class="form-select" name="schedule[<?php echo $index; ?>][tuesday]" onchange="toggleCustomInput(this, 'custom_tuesday_<?php echo $index; ?>', 'select_tuesday_<?php echo $index; ?>')">
                                    <option value="" class="leeg-optie">Leeg</option>
                                    <option value="Halve dag" <?php echo $row['tuesday'] == 'Halve dag' ? 'selected' : ''; ?>>Halve dag</option>
                                    <option value="Vrij" <?php echo $row['tuesday'] == 'Vrij' ? 'selected' : ''; ?>>Vrij</option>
                                    <option value="Ziek" <?php echo $row['tuesday'] == 'Ziek' ? 'selected' : ''; ?>>Ziek</option>
                                    <option value="Verlof" <?php echo $row['tuesday'] == 'Verlof' ? 'selected' : ''; ?>>Verlof</option>
                                    <option value="School" <?php echo $row['tuesday'] == 'School' ? 'selected' : ''; ?>>School</option>
                                    <option value="Thuis werken" <?php echo $row['tuesday'] == 'Thuis werken' ? 'selected' : ''; ?>>Thuis werken</option>
                                    <option value="Eigen invoer" <?php echo $row['tuesday'] == 'Eigen invoer' ? 'selected' : ''; ?>>Eigen invoer</option>
                                </select>
                                <input type="text" class="form-control inline-input <?php echo $row['tuesday'] == 'Eigen invoer' ? '' : 'hidden-input'; ?>" name="schedule[<?php echo $index; ?>][custom_tuesday]" id="custom_tuesday_<?php echo $index; ?>" placeholder="Eigen invoer" value="<?php echo htmlspecialchars($row['custom_tuesday'] ?? ''); ?>" maxlength="20" <?php echo $row['tuesday'] == 'Eigen invoer' ? '' : 'readonly'; ?>>
                            </td>
                            
                            <!-- Wednesday -->
                            <td>
                                <select id="select_wednesday_<?php echo $index; ?>" class="form-select" name="schedule[<?php echo $index; ?>][wednesday]" onchange="toggleCustomInput(this, 'custom_wednesday_<?php echo $index; ?>', 'select_wednesday_<?php echo $index; ?>')">
                                    <option value="" class="leeg-optie">Leeg</option>
                                    <option value="Halve dag" <?php echo $row['wednesday'] == 'Halve dag' ? 'selected' : ''; ?>>Halve dag</option>
                                    <option value="Vrij" <?php echo $row['wednesday'] == 'Vrij' ? 'selected' : ''; ?>>Vrij</option>
                                    <option value="Ziek" <?php echo $row['wednesday'] == 'Ziek' ? 'selected' : ''; ?>>Ziek</option>
                                    <option value="Verlof" <?php echo $row['wednesday'] == 'Verlof' ? 'selected' : ''; ?>>Verlof</option>
                                    <option value="School" <?php echo $row['wednesday'] == 'School' ? 'selected' : ''; ?>>School</option>
                                    <option value="Thuis werken" <?php echo $row['wednesday'] == 'Thuis werken' ? 'selected' : ''; ?>>Thuis werken</option>
                                    <option value="Eigen invoer" <?php echo $row['wednesday'] == 'Eigen invoer' ? 'selected' : ''; ?>>Eigen invoer</option>
                                </select>
                                <input type="text" class="form-control inline-input <?php echo $row['wednesday'] == 'Eigen invoer' ? '' : 'hidden-input'; ?>" name="schedule[<?php echo $index; ?>][custom_wednesday]" id="custom_wednesday_<?php echo $index; ?>" placeholder="Eigen invoer" value="<?php echo htmlspecialchars($row['custom_wednesday'] ?? ''); ?>" maxlength="20" <?php echo $row['wednesday'] == 'Eigen invoer' ? '' : 'readonly'; ?>>
                            </td>
                            
                            <!-- Thursday -->
                            <td>
                                <select id="select_thursday_<?php echo $index; ?>" class="form-select" name="schedule[<?php echo $index; ?>][thursday]" onchange="toggleCustomInput(this, 'custom_thursday_<?php echo $index; ?>', 'select_thursday_<?php echo $index; ?>')">
                                    <option value="" class="leeg-optie">Leeg</option>
                                    <option value="Halve dag" <?php echo $row['thursday'] == 'Halve dag' ? 'selected' : ''; ?>>Halve dag</option>
                                    <option value="Vrij" <?php echo $row['thursday'] == 'Vrij' ? 'selected' : ''; ?>>Vrij</option>
                                    <option value="Ziek" <?php echo $row['thursday'] == 'Ziek' ? 'selected' : ''; ?>>Ziek</option>
                                    <option value="Verlof" <?php echo $row['thursday'] == 'Verlof' ? 'selected' : ''; ?>>Verlof</option>
                                    <option value="School" <?php echo $row['thursday'] == 'School' ? 'selected' : ''; ?>>School</option>
                                    <option value="Thuis werken" <?php echo $row['thursday'] == 'Thuis werken' ? 'selected' : ''; ?>>Thuis werken</option>
                                    <option value="Eigen invoer" <?php echo $row['thursday'] == 'Eigen invoer' ? 'selected' : ''; ?>>Eigen invoer</option>
                                </select>
                                <input type="text" class="form-control inline-input <?php echo $row['thursday'] == 'Eigen invoer' ? '' : 'hidden-input'; ?>" name="schedule[<?php echo $index; ?>][custom_thursday]" id="custom_thursday_<?php echo $index; ?>" placeholder="Eigen invoer" value="<?php echo htmlspecialchars($row['custom_thursday'] ?? ''); ?>" maxlength="20" <?php echo $row['thursday'] == 'Eigen invoer' ? '' : 'readonly'; ?>>
                            </td>
                            
                            <!-- Friday -->
                            <td>
                                <select id="select_friday_<?php echo $index; ?>" class="form-select" name="schedule[<?php echo $index; ?>][friday]" onchange="toggleCustomInput(this, 'custom_friday_<?php echo $index; ?>', 'select_friday_<?php echo $index; ?>')">
                                    <option value="" class="leeg-optie">Leeg</option>
                                    <option value="Halve dag" <?php echo $row['friday'] == 'Halve dag' ? 'selected' : ''; ?>>Halve dag</option>
                                    <option value="Vrij" <?php echo $row['friday'] == 'Vrij' ? 'selected' : ''; ?>>Vrij</option>
                                    <option value="Ziek" <?php echo $row['friday'] == 'Ziek' ? 'selected' : ''; ?>>Ziek</option>
                                    <option value="Verlof" <?php echo $row['friday'] == 'Verlof' ? 'selected' : ''; ?>>Verlof</option>
                                    <option value="School" <?php echo $row['friday'] == 'School' ? 'selected' : ''; ?>>School</option>
                                    <option value="Thuis werken" <?php echo $row['friday'] == 'Thuis werken' ? 'selected' : ''; ?>>Thuis werken</option>
                                    <option value="Eigen invoer" <?php echo $row['friday'] == 'Eigen invoer' ? 'selected' : ''; ?>>Eigen invoer</option>
                                </select>
                                <input type="text" class="form-control inline-input <?php echo $row['friday'] == 'Eigen invoer' ? '' : 'hidden-input'; ?>" name="schedule[<?php echo $index; ?>][custom_friday]" id="custom_friday_<?php echo $index; ?>" placeholder="Eigen invoer" value="<?php echo htmlspecialchars($row['custom_friday'] ?? ''); ?>" maxlength="20" <?php echo $row['friday'] == 'Eigen invoer' ? '' : 'readonly'; ?>>
                            </td>

                            <!-- Starttijd -->
                            <td>
                                <input type="time" class="form-control" name="schedule[<?php echo $index; ?>][start]" value="<?php echo htmlspecialchars($row['start']); ?>">
                            </td>

                            <!-- Eindtijd -->
                            <td>
                                <input type="time" class="form-control" name="schedule[<?php echo $index; ?>][end]" value="<?php echo htmlspecialchars($row['end']); ?>">
                            </td>

                            <!-- Verwijderknop -->
                            <td>
                                <button type="submit" name="remove" class="btn btn-danger">Verwijder</button>
                                <input type="hidden" name="remove_index" value="<?php echo $index; ?>">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div><br>
            
            <!-- Knoppen om het rooster op te slaan of medewerkers toe te voegen -->
            <div class="btn-container">
                <button type="submit" name="save" class="btn btn-success">Opslaan</button>
                <button type="submit" name="add" class="btn btn-primary">Medewerker Toevoegen</button>
            </div>
        </form>
    </div>

    <!-- Script voor functionaliteiten zoals custom invoervelden -->
    <script src="agenda.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
