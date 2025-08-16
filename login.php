<?php
// Database connection settings
$servername = "localhost"; // Change this to your database server
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "classicb"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: {$conn->connect_error}");
}

// Initialize variables
$login_error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM logdata WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass);
    
    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if a user was found
    if ($result->num_rows > 0) {
        // User found, redirect to sample.php
        header("Location: product.php");
        exit();
    } else {
        $login_error = "Invalid username or password";
    }
    
    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet"  type="text/css" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
<a href="index.php" class="return-btn">Return</a>


    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">
        <div class="login">
            <form method="POST" action="">
                <label for="chk" aria-hidden="true" >Login</label>
                <input type="text" name="username" placeholder="User name" required="">
                <input type="password" name="password" placeholder="Password" required="">
                <button>Login</button> 
                <?php
                if (!empty($login_error)) {
                    echo "<p style='color:red; text-align:center;'>$login_error</p>";
                }
                ?>
            </form>
        </div>
        
        <div class="help">
            <form>
                <label for="chk"  aria-hidden="true"> Help</label>
                <h2>For Password contact Developer</h2>
                <label for="chk"  class="back">Back</label>
            </form>
        </div>

        
        </div>

</body>
</html>