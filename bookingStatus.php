<?php
session_start();
$cars = json_decode(file_get_contents('cars.json'), true);
$status = $_GET['status'] ?? null;
$carId = $_GET['car_id'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

$car = array_filter($cars, fn($car) => $car['id'] == $carId);
$car = array_shift($car);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Booking Status</title>
</head>
<body>
    <header>
        <div class="container">
            <h1>iKarRental</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="profile.php">Profile</a>
                <a href="logout.php" class="btn">Logout</a>
            </nav>
        </div>
    </header>
    <main class="status-page">
        <?php if ($status === 'success'): ?>
            <div class="status-card success">
                <h1>Successful booking!</h1>
                <p>The <?= htmlspecialchars($car['brand'] . " " . $car['model']) ?> has been successfully booked for the interval <?= htmlspecialchars($startDate) ?> - <?= htmlspecialchars($endDate) ?>.</p>
                <p>You can track the status of your reservation on your profile page.</p>
                <a href="profile.php" class="btn">My profile</a>
            </div>
        <?php else: ?>
            <div class="status-card failure">
                <h1>Booking failed!</h1>
                <p><?= htmlspecialchars($car['brand'] . " " . $car['model']) ?> is not available in the specified interval.</p>
                <p>Try entering a different interval or search for another vehicle.</p>
                <a href="index.php" class="btn">Back to the main page</a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
