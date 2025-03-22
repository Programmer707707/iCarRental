<?php
require_once 'storage.php';

$carStorage = new Storage(new JsonIO('cars.json'));
$bookings = new Storage(new JsonIO('bookings.json'));
$filters = $_GET;

$cars = $carStorage->findAll();

$filteredCars = array_filter($cars, function($car) use ($filters, $bookings) {
    $available = true;

    if (!empty($filters['seats']) && $car['passengers'] != $filters['seats']) {
        return false;
    }
    if (!empty($filters['transmission']) && $car['transmission'] !== $filters['transmission']) {
        return false;
    }
    if (!empty($filters['priceMin']) && $car['daily_price_huf'] < $filters['priceMin']) {
        return false;
    }
    if (!empty($filters['priceMax']) && $car['daily_price_huf'] > $filters['priceMax']) {
        return false;
    }

    if (!empty($filters['dateFrom']) || !empty($filters['dateTo'])) {
        $carBookings = $bookings->findAll(['car_id' => $car['id']]);
        foreach ($carBookings as $booking) {
            if (
                ($filters['dateFrom'] <= $booking['end_date'] && $filters['dateFrom'] >= $booking['start_date']) ||
                ($filters['dateTo'] <= $booking['end_date'] && $filters['dateTo'] >= $booking['start_date'])
            ) {
                return false;
            }
        }
    }

    return $available;
});

header('Content-Type: application/json');
echo json_encode(array_values($filteredCars));
?>
