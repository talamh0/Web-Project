<?php
session_start();
include 'config.php';


// If user NOT logged in → redirect to login
if (!isset($_SESSION["user_id"])) {

    // Save the page user tried to access
    $_SESSION["redirect_after_login"] = "event.php?id=" . $_GET["id"];

    header("Location: index.php?login_required=1");
    exit();
}

// get event id
if (!isset($_GET['id'])) {
    die("No event ID specified.");
}
$event_id = (int)$_GET['id'];

// bring event info from DB
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
if (!$event) die("Event not found.");

$error_msg = "";
$success_msg = "";

// Add to cart logic
if (isset($_POST['add_to_cart'])) {

    $qty = (int)$_POST['qty'];

    // Make sure cart array exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check same event only
    if (!empty($_SESSION['cart']) && !isset($_SESSION['cart'][$event_id])) {
        $error_msg = "You can only book tickets for one event at a time.";
    }

    // Check available tickets
    $existingQty = $_SESSION['cart'][$event_id] ?? 0;
    if (!$error_msg && ($qty + $existingQty > $event['available'])) {
        $remaining = $event['available'] - $existingQty;
        $error_msg = "Not enough tickets available. You can add $remaining more.";
    }

    // Add tickets
    if (!$error_msg) {
        $_SESSION['cart'][$event_id] = $existingQty + $qty;
        $success_msg = "$qty tickets added to cart!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($event['name']); ?> - Event Booking</title>
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
        <span>Welcome <?php echo htmlspecialchars($_SESSION['name']); ?>.</span>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
    </div>
</header>

<main>
    <section class="event-details">
        <h1><?php echo htmlspecialchars($event['name']); ?></h1>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
        <p><strong>Time:</strong> <?php echo htmlspecialchars($event['event_time']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
        <p><strong>Price:</strong> <?php echo $event['price']; ?></p>
        <p><strong>Available:</strong> <?php echo $event['available']; ?></p>

        <form method="POST">
            <label>Number of tickets:</label>
            <input type="number" name="qty" min="1" max="<?php echo $event['available']; ?>" required>
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>

        <?php if ($error_msg) { echo "<p class='error'>$error_msg</p>"; } ?>
        <?php if ($success_msg) { echo "<p class='success'>$success_msg</p>"; } ?>
    </section>
</main>
<footer>
    <p>© Event Booking System — <?php echo date("Y"); ?></p>
</footer>

</body>
</html>
