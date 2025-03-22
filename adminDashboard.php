<?php
session_start();
require_once 'storage.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']['is_admin']) || !$_SESSION['user']['is_admin']) {
    header('Location: login.php');
    exit;
}

$carStorage = new Storage(new JsonIO('cars.json'));
$bookingStorage = new Storage(new JsonIO('bookings.json'));

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
                    'id' => $_POST['id'],
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

            case 'edit_booking':
                $bookingStorage->update($_POST['id'], [
                    'id' => $_POST['id'],
                    'user' => $_POST['user'],
                    'car_id' => $_POST['car_id'],
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                ]);
                break;

            case 'delete_booking':
                $bookingStorage->delete($_POST['id']);
                break;
        }
    }
}

$cars = $carStorage->findAll();
$bookings = $bookingStorage->findAll();
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
            <a href="index.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php" class="btn">Logout</a>
        </nav>
    </div>
</header>
<main class="admin-dashboard">
    <h1>Admin Dashboard</h1>

    <section class="manage-cars">
        <h2>Manage Cars</h2>
        <form method="POST" action="" class="add-car-form">
            <input type="hidden" name="action" value="add">
            <input type="text" name="brand" placeholder="Brand" required>
            <input type="text" name="model" placeholder="Model" required>
            <input type="number" name="year" placeholder="Year" required>
            <input type="text" name="transmission" placeholder="Transmission" required>
            <input type="text" name="fuel_type" placeholder="Fuel Type" required>
            <input type="number" name="passengers" placeholder="Passengers" required>
            <input type="number" name="daily_price_huf" placeholder="Daily Price (HUF)" required>
            <input type="url" name="image" placeholder="Image URL" required>
            <button type="submit" class="btn">Add Car</button>
        </form>
        <table class="car-table">
            <thead>
            <tr>
                <th>Actions</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Year</th>
                <th>Transmission</th>
                <th>Fuel Type</th>
                <th>Passengers</th>
                <th>Daily Price</th>
                <th>Image</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cars as $id => $car): ?>
                <tr>
                    <td>
                        <form method="POST" action="" class="inline-form">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                            <button class="btn danger">Delete</button>
                        </form>
                        <button class="btn edit" onclick="toggleEditForm('<?= $id ?>')">Edit</button>
                    </td>
                    <td><?= htmlspecialchars($car['brand']) ?></td>
                    <td><?= htmlspecialchars($car['model']) ?></td>
                    <td><?= htmlspecialchars($car['year']) ?></td>
                    <td><?= htmlspecialchars($car['transmission']) ?></td>
                    <td><?= htmlspecialchars($car['fuel_type']) ?></td>
                    <td><?= htmlspecialchars($car['passengers']) ?></td>
                    <td><?= htmlspecialchars($car['daily_price_huf']) ?></td>
                    <td><img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['model']) ?>" width="50"></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="manage-bookings">
        <h2>Manage Bookings</h2>
        <table class="car-table">
            <thead>
            <tr>
                <th>Actions</th>
                <th>User</th>
                <th>Car</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bookings as $id => $booking): ?>
                <tr>
                    <td>
                        <form method="POST" action="" class="inline-form">
                            <input type="hidden" name="action" value="delete_booking">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                            <button class="btn danger">Delete</button>
                        </form>
                        <button class="btn edit" onclick="toggleEditForm('booking-<?= $id ?>')">Edit</button>
                    </td>
                    <td><?= htmlspecialchars($booking['user']) ?></td>
                    <td><?= htmlspecialchars($booking['car_id']) ?></td>
                    <td><?= htmlspecialchars($booking['start_date']) ?></td>
                    <td><?= htmlspecialchars($booking['end_date']) ?></td>
                </tr>
                <tr id="edit-form-booking-<?= $id ?>" style="display: none;">
                    <td colspan="5">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="edit_booking">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                            <input type="text" name="user" value="<?= htmlspecialchars($booking['user']) ?>" required>
                            <input type="text" name="car_id" value="<?= htmlspecialchars($booking['car_id']) ?>" required>
                            <input type="date" name="start_date" value="<?= htmlspecialchars($booking['start_date']) ?>" required>
                            <input type="date" name="end_date" value="<?= htmlspecialchars($booking['end_date']) ?>" required>
                            <button type="submit" class="btn">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>
<script>
    function toggleEditForm(id) {
        const editRow = document.getElementById(`edit-form-${id}`);
        const displayStyle = editRow.style.display;
        editRow.style.display = displayStyle === 'none' ? 'table-row' : 'none';
    }
</script>
</body>
</html>
