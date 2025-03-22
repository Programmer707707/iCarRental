<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$bookings = json_decode(file_get_contents('bookings.json'), true);
$cars = json_decode(file_get_contents('cars.json'), true);

$userBookings = array_filter($bookings, fn($b) => $b['user'] === $_SESSION['user']['email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Profile</title>
</head>
<body>
    <header>
        <div class="container">
            <h1>iKarRental</h1>
            <nav>
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['email'] === 'admin@ikarrental.hu'): ?>
                        <!-- Admin Navbar -->
                        <a href="index.php">Home</a>
                        <a href="adminDashboard.php">Admin Dashboard</a>
                        <a href="logout.php" class="btn">Logout</a>
                    <?php else: ?>
                        <!-- User Navbar -->
                        <a href="index.php">Home</a>
                        <a href="logout.php" class="btn">Logout</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php" class="btn">Registration</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="profile-container">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></h1>
        <h2>My Reservations</h2>
        <div class="car-list">
            <?php foreach ($userBookings as $booking): 
                $car = array_filter($cars, fn($c) => $c['id'] == $booking['car_id']);
                $car = array_shift($car);
            ?>
                <div class="car-card">
                    <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['model']) ?>">
                    <h3><?= htmlspecialchars($car['brand'] . " " . $car['model']) ?></h3>
                    <p><?= htmlspecialchars($car['passengers']) ?> seats - <?= htmlspecialchars(strtolower($car['transmission'])) ?></p>
                    <p>Booked: from <?= htmlspecialchars($booking['start_date']) ?> <br> to <?= htmlspecialchars($booking['end_date']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
