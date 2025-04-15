<?php
session_start();
include('config/db.php'); // database connection

// Check if POST data is set
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // Prepare SQL query with PDO
        $sql = "SELECT userID, name, email, password, is_admin FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        
        // Execute the query
        $stmt->execute();
        
        // Check if user exists
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Store user info in session
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['is_admin'] = $user['is_admin'];

                // Redirect based on role
                if ($user['is_admin']) {
                    echo "admin"; // you can redirect with JS in frontend
                } else {
                    echo "user";
                }
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "Email not found.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
