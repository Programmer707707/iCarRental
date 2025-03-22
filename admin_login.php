<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $adminEmail = "admin@ikarrental.hu";
    $adminPassword = "admin";

    if ($email === $adminEmail && $password === $adminPassword) {
        $_SESSION['admin'] = true;
        header('Location: admin_profile.php');
        exit;
    }

    $error = "Invalid admin credentials.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Admin Login</title>
</head>
<body>
    <header>
        <div class="container">
            <h1>iKarRental</h1>
            <nav>
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['email'] === 'admin@ikarrental.hu'): ?>
                        <!-- Admin Navbar -->
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
        <div class="form-container">
            <h1>Admin Login</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </main>
</body>
</html>
