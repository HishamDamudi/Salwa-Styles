<?php
$servername = "localhost";
$username = "root";
$password = "";
$database_name="classicb";
// Create connection
$conn = mysqli_connect($servername, $username, $password,$database_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
session_start(); // Start the session

?>
<div class="footer-center">
      <h4>Follow Us</h4>
      <div class="social-icons">
        <a href="https://www.instagram.com/your-instagram-id" target="_blank" id="instagram">
        <i class="fa-brands fa-instagram fa-beat-fade fa-2xl" style="color: #f54d5d;"></i> 
        </a>
        <a href="https://www.facebook.com/your-facebook-id" target="_blank" id="facebook">
        <i class="fa-brands fa-facebook fa-beat-fade fa-2xl" style="color: #1660df;"></i>
        </a>
      </div>
    </div>
