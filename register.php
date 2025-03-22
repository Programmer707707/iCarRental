<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'storage.php';

    $userStorage = new Storage(new JsonIO('users.json'));
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($userStorage->findOne(['email' => $email])) {
        $error = "An account with this email already exists.";
    } else {
        $newUser = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        $userStorage->add($newUser);
        $success = "Registration successful! Please login.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Register</title>
</head>
<body>
    <header>
        <div class="container">
            <h1>iKarRental</h1>
            <nav>
               <a href="index.php">Home</a>
               <a href="login.php" class="btn">Login</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="form-container">
            <h1>Register</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php elseif (isset($success)): ?>
                <p class="success"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name">
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email">
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter a strong password">
                
                <button type="submit" class="btn">Register</button>
            </form>
            <p>Already have an account? <a href="login.php" class="register-login">Login</a></p>
        </div>
    </main>
</body>
</html>
