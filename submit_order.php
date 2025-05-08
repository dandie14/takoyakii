<?php
header('Content-Type: text/html; charset=utf-8');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "takoyaki_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Retrieve form data
$firstName    = $_POST['first_name'] ?? '';
$lastName     = $_POST['last_name'] ?? '';
$contact      = $_POST['contact_number'] ?? '';
$address      = $_POST['address'] ?? '';
$productNames = $_POST['product_name'] ?? [];
$quantities   = $_POST['quantity'] ?? [];
$totalPrices  = $_POST['total_price'] ?? [];

if (count($productNames) > 0 && count($quantities) > 0 && count($totalPrices) > 0) {
    $sql = "INSERT INTO orders (first_name, last_name, contact_number, address, product_name, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Loop through each product and save order data
        for ($i = 0; $i < count($productNames); $i++) {
            $product = $productNames[$i];
            $qty = (int)$quantities[$i];
            $price = (float)$totalPrices[$i];

            $stmt->bind_param("sssssid", $firstName, $lastName, $contact, $address, $product, $qty, $price);
            $stmt->execute();
        }
        echo "✔️ Order placed successfully!";
        
        // Optionally clear cart (if session-based)
        session_start();
        unset($_SESSION['cart']);
    } else {
        echo "❌ Error: " . $conn->error;
    }
} else {
    echo "❌ Missing order data.";
}

$conn->close();
?>
