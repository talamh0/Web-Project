<?php
session_start();
include("database/config.php");

// تأكد أن الأدمن مسجل دخول
if(!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

// جلب كل الفعاليات من قاعدة البيانات
$query = "SELECT * FROM events ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Games</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="css/admin.css">
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2 class="logo">Admin Panel</h2>

    <a href="manageEvents.php"><i class="fa-solid fa-list"></i> Manage Games</a>
    <a href="addEvent.php"><i class="fa-solid fa-plus"></i> Add Game</a>
    <a href="viewBookings.php"><i class="fa-solid fa-ticket"></i> View Bookings</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main">
    <h2 class="page-title">All Qiddiya Games</h2>

    <div class="cards-container">

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            
            <div class="game-card">

                <!-- الصورة -->
                <img src="uploads/<?php echo $row['image']; ?>" class="game-img">

                <!-- الاسم -->
                <h3><?php echo $row['name']; ?></h3>

                <!-- الفئة -->
                <span class="badge <?php echo strtolower($row['category']); ?>">
                    <?php echo $row['category']; ?>
                </span>

                <!-- التاريخ -->
                <p class="date">Available: <?php echo $row['event_date']; ?></p>

                <!-- أزرار التحكم -->
                <div class="actions">
                    <a class="btn view" href="viewEvent.php?id=<?php echo $row['id']; ?>">View</a>
                    <a class="btn edit" href="editEvent.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a class="btn delete" href="deleteEvent.php?id=<?php echo $row['id']; ?>">Delete</a>
                </div>

            </div>

        <?php } ?>

    </div>

</div>

</body>
</html>
