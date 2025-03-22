<?php
session_start();
require_once 'storage.php';

if (!isset($_GET['car_id'])) {
    die("Car ID not provided.");
}

$carStorage = new Storage(new JsonIO('cars.json'));
$bookingStorage = new Storage(new JsonIO('bookings.json'));

$carId = $_GET['car_id'];
$car = $carStorage->findById($carId);

if (!$car) {
    die("Car not found.");
}

$bookings = $bookingStorage->findAll(['car_id' => $carId]);

$unavailableDates = [];
foreach ($bookings as $booking) {
    $start = new DateTime($booking['start_date']);
    $end = new DateTime($booking['end_date']);
    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($start, $interval, $end->modify('+1 day'));
    foreach ($dateRange as $date) {
        $unavailableDates[] = $date->format('Y-m-d');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></title>
    <script>
        const unavailableDates = <?= json_encode($unavailableDates); ?>;
    </script>
</head>
<body>
    <header>
        <div class="container">
            <h1>iKarRental</h1>
            <nav>
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['email'] === 'admin@ikarrental.hu'): ?>
                        <a href="index.php">Home</a>
                        <a href="adminDashboard.php">Dashboard</a>
                        <a href="logout.php" class="btn">Logout</a>
                    <?php else: ?>
                        <a href="index.php">Home</a>
                        <a href="profile.php">Profile</a>
                        <a href="logout.php" class="btn">Logout</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="index.php">Home</a>
                    <a href="login.php">Login</a>
                    <a href="register.php" class="btn">Registration</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="car-details">
        <div class="car-details-container">
            <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['model']) ?>">
            <div class="car-info">
                <h1><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h1>
                <p><strong>Fuel:</strong> <?= htmlspecialchars($car['fuel_type']) ?></p>
                <p><strong>Shifter:</strong> <?= htmlspecialchars($car['transmission']) ?></p>
                <p><strong>Year of manufacture:</strong> <?= htmlspecialchars($car['year']) ?></p>
                <p><strong>Number of seats:</strong> <?= htmlspecialchars($car['passengers']) ?></p>
                <h2>HUF <?= htmlspecialchars(number_format($car['daily_price_huf'])) ?>/day</h2>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <form action="book.php" method="POST" class="date-selection-form">
                        <input type="hidden" name="car_id" value="<?= htmlspecialchars($car['id']) ?>">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" id="end_date" name="end_date" required>
                        </div>
                        <button type="submit" class="btn">Book it</button>
                    </form>
                <?php else: ?>
                    <p><a href="login.php" style="text-decoration: none; color: yellow;">Login to book</a></p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            const disableDates = (input) => {
                input.addEventListener('change', () => {
                    const selectedDate = new Date(input.value).toISOString().split('T')[0];
                    if (unavailableDates.includes(selectedDate)) {
                        alert('The car is already booked for this date. Please choose another date.');
                        input.value = '';
                    }
                });
            };

            disableDates(startDateInput);
            disableDates(endDateInput);
        });
    </script>
</body>
</html>
