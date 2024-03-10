<?php
session_start();
include 'server_connection.php';
$connection = connect_to_database();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $username = $_SESSION['username']; // The logged-in user's username

    // SQL query to select distinct users who have conversed with the logged-in user
    $query = "SELECT c.username, MAX(m.timestamp) AS latest_timestamp
    FROM chat c
    JOIN messages m ON c.chatid = m.chatid
        WHERE c.chatid IN (
            SELECT chatid
        FROM chat
        WHERE username = ?
        )
    AND c.username <> ?
    GROUP BY c.username
    ORDER BY latest_timestamp DESC;
    ";

    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $chatPartners = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $chatPartners[] = [
                'username' => $row['username']
            ];
        }
        
        echo json_encode($chatPartners);
    } else {
        echo "Error: " . mysqli_error($connection);
    }

    mysqli_close($connection);
} else {
    echo "User not logged in";
}
?>
