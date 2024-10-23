<?php
session_start();

include 'connect_db.php';
$customer_id = $_SESSION['customer_id'];
$car_id = $_POST['car_id'];
$redeem_points = isset($_POST['redeem_points']) && $_POST['redeem_points'] === 'yes';
$days = $_POST['days'];

$sql = "SELECT email FROM customers WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $customer_id);
$stmt->execute();
$stmt->bind_result($customer_email);
$stmt->fetch();
$stmt->close();

$sql = "SELECT * FROM cars WHERE car_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();

$car_result = $stmt->get_result();
$car = $car_result->fetch_assoc();

$total_rent_payment = $car['price_per_day'] * $days;


if ($redeem_points) {
    // Check how many points the user has
    $sql = "SELECT points FROM customers WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $customer_id);
    $stmt->execute();
    $stmt->bind_result($points);
    $stmt->fetch();
    $stmt->close();

    // Apply discount if they have enough points
    if ($points >= 50) {

        $rent_points = 50;

        $total_rent_payment = $total_rent_payment - 500;

        // Insert rental request into database with discount applied
        $sql = "INSERT INTO rentals (customer_id, days, payment_amount, car_id, customer_email, points, status) 
        VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiiisi', $customer_id, $days, $total_rent_payment, $car_id, $customer_email, $rent_points);

        if ($stmt->execute()) {
        header('Location: customer_dashboard.php?msg=Rental request submitted');
        } else {
        echo "Error: " . $stmt->error;
        }
    }

} else {
    // Insert rental request into database with discount applied
    $sql = "INSERT INTO rentals (customer_id, days, payment_amount, car_id, customer_email, status) 
    VALUES (?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiis', $customer_id, $days, $total_rent_payment, $car_id, $customer_email);

    if ($stmt->execute()) {
    header('Location: customer_dashboard.php?msg=Rental request submitted');
    } else {
    echo "Error: " . $stmt->error;
    }
}

