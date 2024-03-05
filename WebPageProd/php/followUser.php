<?php
include 'server_connection.php';
session_start(); // Make sure session is started to access $_SESSION
$connection = connect_to_database();

if (isset($_SESSION['username']) && isset($_POST['username'])) {
    $follower = $_SESSION['username'];
    $following = $_POST['username'];

    // Prepare and execute the SQL statement
    $stmt = $connection->prepare("INSERT INTO follow (following, follower) VALUES (?, ?)");
    $stmt->bind_param("ss", $following, $follower);
    $stmt->execute();

    // Close statement and connection
    $stmt->close();
    $connection->close();

    // Return success response
    echo "Followed successfully";
} else {
    // Return error response if session or username is not set
    echo "Error: Session or username not set";
}
?>
