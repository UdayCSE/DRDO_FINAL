<?php
// Include database connection configuration
include 'db_config.php';

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $designation = $_POST['designation'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $email = $_POST['email'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data into the database
    $query = "INSERT INTO users (name, username, designation, password, role) VALUES (?, ?, ?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $name, $username, $designation, $hashed_password, $role);

    if (mysqli_stmt_execute($stmt)) {
        // Registration successful, send a verification email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'udayshivanna6@gmail.com'; // Replace with your email
            $mail->Password = 'gzkt yqml mqhx cbcb'; // Replace with your email password
            $mail->SMTPSecure = 'ssl'; // Enable TLS encryption
            $mail->Port = 465; // TCP port to connect

            // Sender and recipient settings
            $mail->setFrom('udayshivanna6@gmail.com', 'Uday Shivanna'); // Set your name and email
            $mail->addAddress($email, $name); // Set recipient email and name

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification'; // Email subject
            $mail->Body = 'jinki jaka jinki jakka';

            $mail->send();
            echo 'Verification email sent. Please check your inbox.';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }

        // Redirect to login page
        header("location: login.html");
    } else {
        // Handle registration error (e.g., duplicate username)
        echo "Error: " . mysqli_error($conn);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>
