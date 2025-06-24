<?php
header('Content-Type: application/json');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Database config - change to your credentials
$host = 'localhost';
$db   = 'thatelo_db';
$user = 'root';
$pass = '';  // your MySQL root password if any

// Gmail SMTP settings
$smtpHost = 'smtp.gmail.com';
$smtpUsername = 'praisemorgets@gmail.com';
$smtpPassword = 'uvgj qpfg ubxz spdh';  
$smtpPort = 587;

function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo ($_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate inputs
$name = clean($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = clean($_POST['phone'] ?? '');
$service = clean($_POST['service'] ?? '');
$date = clean($_POST['date'] ?? '');
$message = clean($_POST['message'] ?? '');

if (!$name || !$email || !$phone || !$service || !$date) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

// Prepare email content
$mailBody = "You have received a new booking:\n\n" .
            "Name: $name\n" .
            "Email: $email\n" .
            "Phone: $phone\n" .
            "Service: $service\n" .
            "Preferred Date: $date\n" .
            "Additional Message: " . ($message ?: 'None') . "\n\n" .
            "Please follow up to confirm.";

// Send email using PHPMailer
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUsername;
    $mail->Password = $smtpPassword;
    $mail->SMTPSecure = 'tls';
    $mail->Port = $smtpPort;

    //Recipients
    $mail->setFrom('info@thateloattorneys.co.za', 'Thatelo Attorneys');
    $mail->addAddress('info@thateloattorneys.co.za'); // Your law firm's email
    $mail->addReplyTo($email, $name);

    // Content
    $mail->isHTML(false);
    $mail->Subject = "New Booking from $name";
    $mail->Body = $mailBody;

    $mail->send();

    // Send confirmation to client
    $mail->clearAddresses();
    $mail->addAddress($email);
    $mail->Subject = "Consultation Booking Confirmation";
    $mail->Body = "Dear $name,\n\nThank you for booking a consultation. We will contact you soon.\n\nBest regards,\nThatelo Attorneys";
    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Booking submitted successfully!']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
}


// Connect to database
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Failed to connect to database']);
    exit;
}

// Prepare and execute insert statement
$stmt = $mysqli->prepare("INSERT INTO bookings (name, email, phone, service, preferred_date, message) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param('ssssss', $name, $email, $phone, $service, $date, $message);
$inserted = $stmt->execute();
$stmt->close();

if (!$inserted) {
    echo json_encode(['success' => false, 'message' => 'Failed to save booking']);
    exit;
}

$mysqli->close();
?>
