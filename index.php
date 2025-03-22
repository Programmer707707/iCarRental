<?php
session_start();
$isLoggedIn = isset($_SESSION['user']);

require_once 'storage.php';

$carStorage = new Storage(new JsonIO('cars.json'));

$cars = $carStorage->findAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>iKarRental - Rent Cars Easily</title>
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
                        <a href="adminDashboard.php">Dashboard</a>
                        <a href="logout.php" class="btn">Logout</a>
                    <?php else: ?>
                        <!-- User Navbar -->
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
    <main class="homepage">
        <div class="container">
            <h2>Rent cars easily!</h2>
            <form id="filterForm">
                <div class="filters">
                    <div>
                        <label for="seats">Seats</label>
                        <input type="number" id="seats" name="seats" min="0">
                    </div>
                    <div>
                        <label for="dateFrom">From</label>
                        <input type="date" id="dateFrom" name="dateFrom">
                    </div>
                    <div>
                        <label for="dateTo">Until</label>
                        <input type="date" id="dateTo" name="dateTo">
                    </div>
                    <div>
                        <label for="transmission">Gear Type</label>
                        <select id="transmission" name="transmission">
                            <option value="">Any</option>
                            <option value="Automatic">Automatic</option>
                            <option value="Manual">Manual</option>
                        </select>
                    </div>
                    <div>
                        <label for="priceMin">Price (Min)</label>
                        <input type="number" id="priceMin" name="priceMin">
                    </div>
                    <div>
                        <label for="priceMax">Price (Max)</label>
                        <input type="number" id="priceMax" name="priceMax">
                    </div>
                    <button type="submit" id="filter">Filter</button>
                </div>
            </form>
           
            <div id="carList" class="car-list">
                
            </div>

        </div>
    </main>
    <script src="app.js"></script>

</body>
</html>
