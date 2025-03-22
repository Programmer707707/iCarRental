<?php
session_start();
require_once 'storage.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']['is_admin']) || !$_SESSION['user']['is_admin']) {
    header('Location: login.php');
    exit;
}

$carStorage = new Storage(new JsonIO('cars.json'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $carStorage->add([
                    'brand' => $_POST['brand'],
                    'model' => $_POST['model'],
                    'year' => $_POST['year'],
                    'transmission' => $_POST['transmission'],
                    'fuel_type' => $_POST['fuel_type'],
                    'passengers' => $_POST['passengers'],
                    'daily_price_huf' => $_POST['daily_price_huf'],
                    'image' => $_POST['image'],
                ]);
                break;

            case 'edit':
                $carStorage->update($_POST['id'], [
                    'id' => $_POST['id'], // Preserve ID
                    'brand' => $_POST['brand'],
                    'model' => $_POST['model'],
                    'year' => $_POST['year'],
                    'transmission' => $_POST['transmission'],
                    'fuel_type' => $_POST['fuel_type'],
                    'passengers' => $_POST['passengers'],
                    'daily_price_huf' => $_POST['daily_price_huf'],
                    'image' => $_POST['image'],
                ]);
                break;

            case 'delete':
                $carStorage->delete($_POST['id']);
                break;
        }
    }
}

$cars = $carStorage->findAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <header>
        <div class="container">
            <h1>iKarRental</h1>
            <nav>
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['email'] === 'admin@ikarrental.hu'): ?>
                        <!-- Admin Navbar -->
                        <a href="profile.php">Profile</a>
                        <a href="adminDashboard.php">Admin Dashboard</a>
                        <a href="logout.php" class="btn">Logout</a>
                    <?php else: ?>
                        <!-- User Navbar -->
                        <a href="index.php">Home</a>
                        <a href="profile.php">Profile</a>
                        <a href="logout.php" class="btn">Logout</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php" class="btn">Registration</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main>
        <h1>Admin Dashboard</h1>
        <section>
            <h2>Manage Cars</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                <input type="text" name="brand" placeholder="Brand" required>
                <input type="text" name="model" placeholder="Model" required>
                <input type="number" name="year" placeholder="Year" required>
                <input type="text" name="transmission" placeholder="Transmission" required>
                <input type="text" name="fuel_type" placeholder="Fuel Type" required>
                <input type="number" name="passengers" placeholder="Passengers" required>
                <input type="number" name="daily_price_huf" placeholder="Daily Price (HUF)" required>
                <input type="url" name="image" placeholder="Image URL" required>
                <button type="submit">Add Car</button>
            </form>
            <ul>
                <?php foreach ($cars as $id => $car): ?>
                    <li>
                        <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                            <button type="submit">Delete</button>
                        </form>
                        <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                            <input type="text" name="brand" value="<?= htmlspecialchars($car['brand']) ?>">
                            <input type="text" name="model" value="<?= htmlspecialchars($car['model']) ?>">
                            <input type="number" name="year" value="<?= htmlspecialchars($car['year']) ?>">
                            <input type="text" name="transmission" value="<?= htmlspecialchars($car['transmission']) ?>">
                            <input type="text" name="fuel_type" value="<?= htmlspecialchars($car['fuel_type']) ?>">
                            <input type="number" name="passengers" value="<?= htmlspecialchars($car['passengers']) ?>">
                            <input type="number" name="daily_price_huf" value="<?= htmlspecialchars($car['daily_price_huf']) ?>">
                            <input type="url" name="image" value="<?= htmlspecialchars($car['image']) ?>">
                            <button type="submit">Edit</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <section>
            <h2>View All Bookings</h2>
            <ul>
                <?php
                $bookingStorage = new Storage(new JsonIO('bookings.json'));
                $bookings = $bookingStorage->findAll();
                foreach ($bookings as $booking): ?>
                    <li>
                        User: <?= htmlspecialchars($booking['user']) ?>,
                        Car ID: <?= htmlspecialchars($booking['car_id']) ?>,
                        From: <?= htmlspecialchars($booking['start_date']) ?>,
                        To: <?= htmlspecialchars($booking['end_date']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>
</html>
