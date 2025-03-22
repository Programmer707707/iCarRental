<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'storage.php';

    $userStorage = new Storage(new JsonIO('users.json'));
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email)){
        $error = "Email is required.";
    } 
    elseif(empty($password)){
        $error = "Password is required.";
    }
    elseif (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } 
    else {
        $user = $userStorage->findOne(['email' => $email]);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            if ($user['email'] === 'admin@ikarrental.hu') {
                header('Location: adminDashboard.php');
            } else {
                header('Location: profile.php');
            }
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>
    <header>
        <div class="container">
            <h1>iKarRental</h1>
            <nav>
               <a href="index.php">Home</a>
               <a href="register.php" class="btn">Register</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="form-container">
            <h1>Login</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email">
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
                
                <button type="submit" class="btn">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php" class="login-register">Register</a></p>
        </div>
    </main>
</body>
</html>
