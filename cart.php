<?php
session_start();
require 'config.php';

// user must be logged in to access cart
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?login_required=1");
    exit();
}

// get user name
$userName = $_SESSION['name'];

// get cart items
$cart = $_SESSION['cart'] ?? [];
$totalPrice = 0;
$cartItems = [];
$success_msg = "";

// reserve tickets
if (isset($_POST['reserve']) && $cart) {
    foreach ($cart as $event_id => $qty) {
        $stmt = $conn->prepare("UPDATE events SET available = available - ? WHERE id = ?");
        $stmt->bind_param("ii", $qty, $event_id);
        $stmt->execute();
    }
    $_SESSION['cart'] = [];
    $cart = [];
    $success_msg = "Booking confirmed! Tickets reserved.";
}

// empty cart
if (isset($_POST['empty_cart'])) {
    $_SESSION['cart'] = [];
    $cart = [];
    $success_msg = "Cart has been emptied.";
}

// get event information
if ($cart) {
    $ids = implode(',', array_keys($cart));
    $query = "SELECT * FROM events WHERE id IN ($ids)";
    $result = $conn->query($query);

    while ($event = $result->fetch_assoc()) {
        $qty = $cart[$event['id']];
        $price = $event['price'];
        $total = $qty * $price;
        $totalPrice += $total;

        $cartItems[] = [
            'name' => $event['name'],
            'date' => $event['event_date'],
            'qty' => $qty,
            'price' => $price,
            'total' => $total
        ];
    }
}

// Get Date & Time
$currentDateTime = date("Y-m-d H:i:s");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart - Event Booking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div>
        <a href="home.php">
            <span>Event Booking System</span>
        </a>
    </div>

    <div>
        <span>Welcome <?php echo htmlspecialchars($userName); ?>.</span>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
    </div>
</header>

<main>
    <section class="cart-section">
        <h1>Your Cart</h1>
        <p><strong>Current Date & Time:</strong> <?php echo $currentDateTime; ?></p>

        <?php if ($cartItems) { ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($cartItems as $item) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['date']); ?></td>
                            <td><?php echo $item['qty']; ?></td>
                            <td><?php echo number_format($item['price'],2); ?> $</td>
                            <td><?php echo number_format($item['total'],2); ?> $</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <p><strong>Total Price:</strong> <?php echo number_format($totalPrice, 2); ?> $</p>

            <form method="POST">
                <button type="submit" name="reserve">Reserve Tickets</button>
            </form>

            <form method="POST" style="display:inline;">
                <button type="submit" name="empty_cart" class="btn-empty">Empty Cart</button>
            </form>

            <?php if ($success_msg) { ?>
                <p class="message success"><?php echo htmlspecialchars($success_msg); ?></p>
            <?php } ?>

        <?php } else { ?>
            <p>Your cart is empty.</p>
        <?php } ?>
    </section>
</main>

<footer>
    <p>© Event Booking System — <?php echo date("Y"); ?></p>
</footer>

</body>
</html>