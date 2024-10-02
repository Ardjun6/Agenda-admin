<?php
$allowed_ips = ['127.0.0.1', '77.60.30.241'];
if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
    exit;
}

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load the schedule data from the JSON file
$schedule = [];
if (file_exists('schedule.json')) {
    $jsonData = file_get_contents('schedule.json');
    $schedule = json_decode($jsonData, true);

    // Check if JSON decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo 'JSON decode error: ' . json_last_error_msg();
        $schedule = []; // Reset schedule if there is an error
    }
}

// Set locale to Dutch for proper weekday translation
setlocale(LC_TIME, 'nl_NL.UTF-8');

// Get the current date
$today = new DateTime();

// Find Monday of the current week
$monday = clone $today;
$monday->modify(('Monday' == $today->format('l')) ? 'this Monday' : 'last Monday');

// Create an array to hold the week dates
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
    <meta http-equiv="refresh" content="60;url=https://khpi.nl/Productiviteit/index.php" /> 
    <title>Rooster Weergeven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="scherm4.css" rel="stylesheet">
</head>
<body>

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
                </tr>
            </thead>
            <tbody>
    <?php foreach ($schedule as $row): ?>
    <?php
    // Check if at least one relevant field is not empty
    $hasContent = !empty($row['name']) || 
                  !empty($row['monday']) || 
                  !empty($row['tuesday']) || 
                  !empty($row['wednesday']) || 
                  !empty($row['thursday']) || 
                  !empty($row['friday']) || 
                  !empty($row['start']) || 
                  !empty($row['end']) ||
                  !empty($row['custom_monday']) ||
                  !empty($row['custom_tuesday']) ||
                  !empty($row['custom_wednesday']) ||
                  !empty($row['custom_thursday']) ||
                  !empty($row['custom_friday']);
    ?>
    <?php if ($hasContent): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['name'] ?? ''); ?></td>
        <td style="<?php echo isset($row['monday']) && $row['monday'] === 'Halve dag' ? 'background-color: #ffebcd;' : 
                      (isset($row['monday']) && $row['monday'] === 'Thuis werken' ? 'background-color: #ADD8E6;' : 
                      (isset($row['monday']) && $row['monday'] === 'Vrij' ? 'background-color: #d4edda;' : 
                      (isset($row['monday']) && $row['monday'] === 'Verlof' ? 'background-color: #e2d6f7;' : 
                      (isset($row['monday']) && $row['monday'] === 'Ziek' ? 'background-color: #f8d7da;' : 
                      (isset($row['monday']) && $row['monday'] === 'School' ? 'background-color: #cce5ff;' : 
                      (isset($row['monday']) && $row['monday'] === 'Eigen invoer' ? 'background-color: #d8b2d1;' : '')))))); ?>">
            <?php echo isset($row['monday']) && $row['monday'] === 'Eigen invoer' ? htmlspecialchars($row['custom_monday'] ?? '') : htmlspecialchars($row['monday'] ?? ''); ?>
        </td>
        <td style="<?php echo isset($row['tuesday']) && $row['tuesday'] === 'Halve dag' ? 'background-color: #ffebcd;' : 
                      (isset($row['tuesday']) && $row['tuesday'] === 'Thuis werken' ? 'background-color: #ADD8E6;' : 
                      (isset($row['tuesday']) && $row['tuesday'] === 'Vrij' ? 'background-color: #d4edda;' : 
                      (isset($row['tuesday']) && $row['tuesday'] === 'Verlof' ? 'background-color: #e2d6f7;' : 
                      (isset($row['tuesday']) && $row['tuesday'] === 'Ziek' ? 'background-color: #f8d7da;' : 
                      (isset($row['tuesday']) && $row['tuesday'] === 'School' ? 'background-color: #cce5ff;' : 
                      (isset($row['tuesday']) && $row['tuesday'] === 'Eigen invoer' ? 'background-color: #d8b2d1;' : '')))))); ?>">
            <?php echo isset($row['tuesday']) && $row['tuesday'] === 'Eigen invoer' ? htmlspecialchars($row['custom_tuesday'] ?? '') : htmlspecialchars($row['tuesday'] ?? ''); ?>
        </td>
        <td style="<?php echo isset($row['wednesday']) && $row['wednesday'] === 'Halve dag' ? 'background-color: #ffebcd;' : 
                      (isset($row['wednesday']) && $row['wednesday'] === 'Thuis werken' ? 'background-color: #ADD8E6;' : 
                      (isset($row['wednesday']) && $row['wednesday'] === 'Vrij' ? 'background-color: #d4edda;' : 
                      (isset($row['wednesday']) && $row['wednesday'] === 'Verlof' ? 'background-color: #e2d6f7;' : 
                      (isset($row['wednesday']) && $row['wednesday'] === 'Ziek' ? 'background-color: #f8d7da;' : 
                      (isset($row['wednesday']) && $row['wednesday'] === 'School' ? 'background-color: #cce5ff;' : 
                      (isset($row['wednesday']) && $row['wednesday'] === 'Eigen invoer' ? 'background-color: #d8b2d1;' : '')))))); ?>">
            <?php echo isset($row['wednesday']) && $row['wednesday'] === 'Eigen invoer' ? htmlspecialchars($row['custom_wednesday'] ?? '') : htmlspecialchars($row['wednesday'] ?? ''); ?>
        </td>
        <td style="<?php echo isset($row['thursday']) && $row['thursday'] === 'Halve dag' ? 'background-color: #ffebcd;' : 
                      (isset($row['thursday']) && $row['thursday'] === 'Thuis werken' ? 'background-color: #ADD8E6;' : 
                      (isset($row['thursday']) && $row['thursday'] === 'Vrij' ? 'background-color: #d4edda;' : 
                      (isset($row['thursday']) && $row['thursday'] === 'Verlof' ? 'background-color: #e2d6f7;' : 
                      (isset($row['thursday']) && $row['thursday'] === 'Ziek' ? 'background-color: #f8d7da;' : 
                      (isset($row['thursday']) && $row['thursday'] === 'School' ? 'background-color: #cce5ff;' : 
                      (isset($row['thursday']) && $row['thursday'] === 'Eigen invoer' ? 'background-color: #d8b2d1;' : '')))))); ?>">
            <?php echo isset($row['thursday']) && $row['thursday'] === 'Eigen invoer' ? htmlspecialchars($row['custom_thursday'] ?? '') : htmlspecialchars($row['thursday'] ?? ''); ?>
        </td>
        <td style="<?php echo isset($row['friday']) && $row['friday'] === 'Halve dag' ? 'background-color: #ffebcd;' : 
                      (isset($row['friday']) && $row['friday'] === 'Thuis werken' ? 'background-color: #ADD8E6;' : 
                      (isset($row['friday']) && $row['friday'] === 'Vrij' ? 'background-color: #d4edda;' : 
                      (isset($row['friday']) && $row['friday'] === 'Verlof' ? 'background-color: #e2d6f7;' : 
                      (isset($row['friday']) && $row['friday'] === 'Ziek' ? 'background-color: #f8d7da;' : 
                      (isset($row['friday']) && $row['friday'] === 'School' ? 'background-color: #cce5ff;' : 
                      (isset($row['friday']) && $row['friday'] === 'Eigen invoer' ? 'background-color: #d8b2d1;' : '')))))); ?>">
            <?php echo isset($row['friday']) && $row['friday'] === 'Eigen invoer' ? htmlspecialchars($row['custom_friday'] ?? '') : htmlspecialchars($row['friday'] ?? ''); ?>
        </td>
        <td><?php echo htmlspecialchars($row['start'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($row['end'] ?? ''); ?></td>
    </tr>
    <?php endif; ?>
    <?php endforeach; ?>
</tbody>        
</table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
