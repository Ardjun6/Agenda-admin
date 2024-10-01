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
                    <th>Maandag</th>
                    <th>Dinsdag</th>
                    <th>Woensdag</th>
                    <th>Donderdag</th>
                    <th>Vrijdag</th>
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
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td style="<?php echo ($row['monday'] === 'Halve dag') ? 'background-color: #ffebcd;' : 
                      (($row['monday'] === 'Thuis werken') ? 'background-color: #ADD8E6;' : 
                      (($row['monday'] === 'Vrij') ? 'background-color: #d4edda;' : 
                      (($row['monday'] === 'Verlof') ? 'background-color: #e2d6f7;' : 
                      (($row['monday'] === 'Ziek') ? 'background-color: #f8d7da;' : 
                      (($row['monday'] === 'School') ? 'background-color: #cce5ff;' : 
                      (($row['monday'] === 'Eigen invoer') ? 'background-color: #d8b2d1;' : '')))))); ?>">
            <?php echo $row['monday'] === 'Eigen invoer' ? htmlspecialchars($row['custom_monday']) : htmlspecialchars($row['monday']); ?>
        </td>
        <td style="<?php echo ($row['tuesday'] === 'Halve dag') ? 'background-color: #ffebcd;' : 
                      (($row['tuesday'] === 'Thuis werken') ? 'background-color: #ADD8E6;' : 
                      (($row['tuesday'] === 'Vrij') ? 'background-color: #d4edda;' : 
                      (($row['tuesday'] === 'Verlof') ? 'background-color: #e2d6f7;' : 
                      (($row['tuesday'] === 'Ziek') ? 'background-color: #f8d7da;' : 
                      (($row['tuesday'] === 'School') ? 'background-color: #cce5ff;' : 
                      (($row['tuesday'] === 'Eigen invoer') ? 'background-color: #d8b2d1;' : '')))))); ?>">
            <?php echo $row['tuesday'] === 'Eigen invoer' ? htmlspecialchars($row['custom_tuesday']) : htmlspecialchars($row['tuesday']); ?>
        </td>
        <td style="<?php echo ($row['wednesday'] === 'Halve dag') ? 'background-color: #ffebcd;' : 
                      (($row['wednesday'] === 'Thuis werken') ? 'background-color: #ADD8E6;' : 
                      (($row['wednesday'] === 'Vrij') ? 'background-color: #d4edda;' : 
                      (($row['wednesday'] === 'Verlof') ? 'background-color: #e2d6f7;' : 
                      (($row['wednesday'] === 'Ziek') ? 'background-color: #f8d7da;' : 
                      (($row['wednesday'] === 'School') ? 'background-color: #cce5ff;' : 
                      (($row['wednesday'] === 'Eigen invoer') ? 'background-color: #d8b2d1;' : '')))))); ?>">
            <?php echo $row['wednesday'] === 'Eigen invoer' ? htmlspecialchars($row['custom_wednesday']) : htmlspecialchars($row['wednesday']); ?>
        </td>
        <td style="<?php echo ($row['thursday'] === 'Halve dag') ? 'background-color: #ffebcd;' : 
                      (($row['thursday'] === 'Thuis werken') ? 'background-color: #ADD8E6;' : 
                      (($row['thursday'] === 'Vrij') ? 'background-color: #d4edda;' : 
                      (($row['thursday'] === 'Verlof') ? 'background-color: #e2d6f7;' : 
                      (($row['thursday'] === 'Ziek') ? 'background-color: #f8d7da;' : 
                      (($row['thursday'] === 'School') ? 'background-color: #cce5ff;' : 
                      (($row['thursday'] === 'Eigen invoer') ? 'background-color: #d8b2d1;' : '')))))); ?>">
            <?php echo $row['thursday'] === 'Eigen invoer' ? htmlspecialchars($row['custom_thursday']) : htmlspecialchars($row['thursday']); ?>
        </td>
        <td style="<?php echo ($row['friday'] === 'Halve dag') ? 'background-color: #ffebcd;' : 
                      (($row['friday'] === 'Thuis werken') ? 'background-color: #ADD8E6;' : 
                      (($row['friday'] === 'Vrij') ? 'background-color: #d4edda;' : 
                      (($row['friday'] === 'Verlof') ? 'background-color: #e2d6f7;' : 
                      (($row['friday'] === 'Ziek') ? 'background-color: #f8d7da;' : 
                      (($row['friday'] === 'School') ? 'background-color: #cce5ff;' : 
                      (($row['friday'] === 'Eigen invoer') ? 'background-color: #d8b2d1;' : '')))))); ?>">
            <?php echo $row['friday'] === 'Eigen invoer' ? htmlspecialchars($row['custom_friday']) : htmlspecialchars($row['friday']); ?>
        </td>
        <td><?php echo htmlspecialchars($row['start'] ?: ''); ?></td>
        <td><?php echo htmlspecialchars($row['end'] ?: ''); ?></td>
    </tr>
    <?php endif; ?>
    <?php endforeach; ?>
</tbody>        
</table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
