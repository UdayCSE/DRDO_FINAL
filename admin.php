<?php
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and process the form data
    $objective11Data = $_POST["objective11"];
    $objective12Data = $_POST["objective12"];// Replace "objective12" with the actual name of the form

    // Store data in a MySQL database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "cabs";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into a table (create the table structure in your database)
    $sql = "INSERT INTO your_table_name (objective11_data, objective12_data) VALUES ('$objective11Data', '$objective12Data')";

    if ($conn->query($sql) === TRUE) {
        // Data inserted successfully
    } else {
        // Handle database error
    }

    // Close the database connection
    $conn->close();

    // Send an email notification
    $to = "udays.cs22@bmsce.ac.in";
    $subject = "Form Submission";
    $message = "Objective 11 Data: $objective11Data\nObjective 12 Data: $objective12Data";
    mail($to, $subject, $message);

    // Redirect the user to a thank you page or back to the original page
    header("Location: thank_you.php"); // Replace "thank_you.php" with the actual page you want to redirect to
    exit;
}
?>

