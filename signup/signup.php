<?php
session_start();
include '../includes/db.php'; // Include the database connection file
require '../vendor/autoload.php'; // Include the QR code library

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $repeat_pass = $_POST['repeat-password'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $residency = $_POST['residency'];

    // Validate password match
    if ($pass !== $repeat_pass) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    // Generate a unique QR code
    try {
        $qrCode = new QrCode(
            data: $email, // Use the email as the QR code data
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        // Write the QR code without a logo
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Convert QR code to base64 string for embedding in HTML
        $qrCodeContent = 'data:image/png;base64,' . base64_encode($result->getString());
    } catch (ValidationException $e) {
        echo "Validation error: " . $e->getMessage();
        exit;
    } catch (Exception $e) {
        echo "QR Code generation failed: " . $e->getMessage();
        exit;
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, age, address, gender, status, residency, qr_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissssss", $fullname, $email, $hashedPassword, $age, $address, $gender, $status, $residency, $qrCodeContent);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Store user data in session
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;
        $_SESSION['qr_code'] = $qrCodeContent;

        // Redirect to Wireframe 8 page
        header("Location: ../wireframes/wireframe-8.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>