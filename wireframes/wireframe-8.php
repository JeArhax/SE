<?php
// Include the database connection file
include '../includes/db.php';

// Fetch user information from the database
$sql = "SELECT fullname, email, age, address, gender, status, residency, qr_code FROM users"; // Added qr_code to the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wireframe 8 - User Information</title>
    <link rel="stylesheet" href="../css/wireframe-8.css"> <!-- Adjust the path if necessary -->
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="path/to/logo.png" alt="Logo" class="logo-icon"> <!-- Adjust the path to your logo -->
        </div>
        <div class="navbar">
            <button class="nav-dropdown">Menu</button>
            <div class="dropdown-content">
                <a href="wireframe-1.php">Home</a>
                <a href="#">About</a>
                <a href="#">Contact</a>
            </div>
        </div>
    </div>

        <div class="main-content">
            <h2 class="title">User  Information</h2>
            <div class="info-box">
                <?php if ($result && $result->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Age</th>
                            <th>Address</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Residency</th>
                            <th>QR Code</th>
                        </tr>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['age']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['residency']); ?></td>
                                <td>
                                    <?php
                                    // Display the QR code image from the database
                                    if (!empty($row['qr_code'])) {
                                        echo '<img src="' . htmlspecialchars($row['qr_code']) . '" alt="QR Code" style="width: 100px; height: 100px;">'; // Adjust size as needed
                                    } else {
                                        echo 'No QR Code available';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>No user information found.</p>
                <?php endif; ?>
            </div>
        </div>

        <footer class="footer">
            <div class="icons">
                <img src="path/to/icon1.png" alt="Icon 1" class="footer-icon"> <!-- Adjust the path to your icons -->
                <img src="path/to/icon2.png" alt="Icon 2" class="footer-icon">
            </div>
            <p>&copy; 2023 Your Company. All rights reserved.</p>
        </footer>


    <?php // Close the database connection
    $conn->close();
    ?>
</body>
</html>