<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "classicb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products for display
$sql = "SELECT id, name, description, price, stocks, image_path FROM products ORDER BY id";
$result = $conn->query($sql);

$sql_accessories = "SELECT name, description, price, stocks, image_path FROM accessories ORDER BY id";
$result_accessories = $conn->query($sql_accessories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script defer src="https://kit.fontawesome.com/f5062340fd.js"></script>
    <script src="https://kit.fontawesome.com/f5062340fd.js" crossorigin="anonymous"></script>
    <title>Salwa Styles</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="nav">
        <div class="container">
            <div class="logo">
                <a onclick="scrollToSection('home')">
                    <img src="logo.png" class="logo" alt="Logo">
                </a>
            </div>
            <div id="mainListDiv" class="main_list">
                <ul class="navlinks">
                    <li class="nav-item"><a onclick="scrollToSection('home')">Home</a></li>
                    <li class="nav-item"><a onclick="scrollToSection('product1')">Product</a></li>
                    <li class="nav-item"><a onclick="scrollToSection('contact')">Contact</a></li>
                </ul>
            </div>
            <span class="navTrigger">
                <i></i><i></i><i></i>
            </span>
        </div>
    </nav>
    <section id="home">
            <div class="home_page ">
    <div class="home_img ">
    <div class="slideshow-container">

<div class="mySlides fade">
  <img src="banner1.jpg" style="width:100%">
</div>

<div class="mySlides fade">
  <img src="banner2.jpg" style="width:100%">
</div>

<div class="mySlides fade">
  <img src="banner3.jpg" style="width:100%">
</div>

<a class="prev" onclick="plusSlides(-1)"><</a>
<a class="next" onclick="plusSlides(1)">></a>

</div>
<br>

<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span> 
  <span class="dot" onclick="currentSlide(2)"></span> 
  <span class="dot" onclick="currentSlide(3)"></span>
</div>
    </div>
    </div>
</section>
<section id="product1" class="section-p1">
    <b><h1>Featured Products</h1></b>
  
    <div class="pro-container">
      <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
      <div class="pro">
        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" width="20" height="220">
    
        <div class="des">
            <span><b><?php echo htmlspecialchars($row['name']); ?></b></span>
            <h5 class="short-desc"><?php echo htmlspecialchars(substr($row['description'], 0, 100)); ?>...</h5>
            <h5 class="full-desc" style="display:none;"><?php echo htmlspecialchars($row['description']); ?></h5>
            <a href="javascript:void(0);" class="read-more">Read more</a>
            <h4><strong>Price:</strong>₹<?php echo htmlspecialchars($row['price']); ?> </h4>
            <h5>Stocks:  <?php echo htmlspecialchars($row['stocks']); ?> </h5> 
        </div>
    
      </div>  
      <?php endwhile; ?>
      <?php else: ?>
      <?php endif; ?>
    </div>
</section>


  <section id="product1" class="section-p1">
    <b><h1>Accessories</h1></b>
  
    <div class="pro-container">
      <?php if ($result_accessories->num_rows > 0): ?>
      <?php while($row = $result_accessories->fetch_assoc()): ?>
      <div class="pro">
        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" width="220" height="220">
        <div class="des">
            <span><b><?php echo htmlspecialchars($row['name']); ?></b></span>
            <h5 class="short-desc"><?php echo htmlspecialchars(substr($row['description'], 0, 100)); ?>...</h5>
            <h5 class="full-desc" style="display:none;"><?php echo htmlspecialchars($row['description']); ?></h5>
            <a href="javascript:void(0);" class="read-more">Read more</a>
            <h4><strong>Price:</strong> ₹<?php echo htmlspecialchars($row['price']); ?></h4>
            <h5>Stocks: <?php echo htmlspecialchars($row['stocks']); ?></h5> 
        </div>
      </div>  
      <?php endwhile; ?>
      <?php else: ?>
      <?php endif; ?>
    </div>
</section>

<section id="contact"
  <!-- Footer Section -->
<footer id="footer">
    <div class="footer-container">
      <div class="footer-left">
        <h3>CLASSIC BURQAS</h3>
        <p>Your go-to destination for elegant and traditional burqas.</p>
        <p>Phone: <a href="tel:+1234567890">+1 234 567 890</a></p>
      </div>
  
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
  
     
      <div class="footer-right">
        <h4>Subscribe to Our Newsletter</h4>
        <form id="subscribe-form">
          <input type="email" id="email-input" placeholder="Your Email" required />
          <button type="submit">Subscribe</button>
        </form>
      </div>
    </div>
  
    <div class="footer-bottom">
      <p>&copy; <?php echo date('Y'); ?> Classic Burqas. All rights reserved.</p>
    </div>
  </footer>

    <!-- Script Files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="index.js"></script>
</body>
</html>
