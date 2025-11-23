<?php
session_start();
require_once "../database/config.php";

// Check if admin is logged in
// I added this to prevent unauthorized access
if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

// Check if event ID is provided in the URL
// Example: viewEvent.php?id=3
if (!isset($_GET['id'])) {
    die("No event selected.");
}

$event_id = $_GET['id'];

// Fetch event details from database using the event ID
$query = "SELECT * FROM events WHERE id = $event_id";
$result = mysqli_query($conn, $query);
$event = mysqli_fetch_assoc($result);

// If the event does not exist in the database
if (!$event) {
    die("Event not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Event</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

<div class="container">

    <!-- Include the admin sidebar (same sidebar for all admin pages) -->
    <?php include 'admin_sidebar.php'; ?>

    <!-- Main content section -->
    <div class="main-section">
        <h2>Event Details</h2>

        <div class="event-box">

            <!-- Display event name -->
            <p><strong>Event Name:</strong> <?= $event['name'] ?></p>

            <!-- Display event date and time -->
            <p><strong>Date & Time:</strong> <?= $event['date_time'] ?></p>

            <!-- Display event location -->
            <p><strong>Location:</strong> <?= $event['location'] ?></p>

            <!-- Display ticket price -->
            <p><strong>Ticket Price:</strong> <?= $event['price'] ?> SAR</p>

            <!-- Display event max ticket limit -->
            <p><strong>Maximum Tickets:</strong> <?= $event['max_tickets'] ?></p>

            <!-- Display event image -->
            <p><strong>Event Image:</strong></p>
            <img src="../uploads/<?= $event['image'] ?>" 
                 style="width: 320px; border-radius: 10px;">

        </div>

    </div>

</div>

</body>
</html>
