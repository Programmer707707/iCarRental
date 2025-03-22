<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carId = $_POST['car_id'] ?? null;
    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;

    if (!$carId || !$startDate || !$endDate) {
        header("Location: bookingStatus.php?status=failure");
        exit;
    }

    $cars = json_decode(file_get_contents('cars.json'), true);
    $bookings = json_decode(file_get_contents('bookings.json'), true) ?: [];
    $selectedCar = array_filter($cars, fn($car) => $car['id'] == $carId);
    $selectedCar = array_shift($selectedCar);

    foreach ($bookings as $booking) {
        if ($booking['car_id'] == $carId &&
            (($startDate >= $booking['start_date'] && $startDate <= $booking['end_date']) ||
             ($endDate >= $booking['start_date'] && $endDate <= $booking['end_date']))) {
            header("Location: bookingStatus.php?status=failure&car_id=$carId");
            exit;
        }
    }

    $bookings[] = [
        'car_id' => $carId,
        'user' => $_SESSION['user']['email'],
        'start_date' => $startDate,
        'end_date' => $endDate
    ];
    file_put_contents('bookings.json', json_encode($bookings));

    header("Location: bookingStatus.php?status=success&car_id=$carId&start_date=$startDate&end_date=$endDate");
    exit;
}
?>
