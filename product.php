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

// Handling item deletion (Products or Accessories)
if (isset($_GET['delete_id']) && isset($_GET['type'])) {
    $delete_id = $_GET['delete_id'];
    $type = $_GET['type'];

    $table = ($type === 'product') ? 'products' : 'accessories';

    // Get the image path before deleting
    $sql = "SELECT image_path FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($image_path);
    $stmt->fetch();
    $stmt->close();

    // Delete the item
    $sql = "DELETE FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        // Delete the image file
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        echo ucfirst($type) . " deleted successfully!";
    } else {
        echo "Error deleting $type: " . $conn->error;
    }

    $stmt->close();
    // Redirect to the same page to avoid accidental re-submission
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Handling form submission for adding or updating a product or accessory
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $type = $_POST['type'];
    $table = ($type === 'product') ? 'products' : 'accessories';
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    // Handling the image upload
    $target_dir = "PRODUCT/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if ($_FILES["image"]["size"] > 0) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            die("File is not an image.");
        }

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            die("Sorry, there was an error uploading your file.");
        }
    } else {
        $target_file = isset($_POST['existing_image']) ? $_POST['existing_image'] : null;
    }

    if ($id) {
        $sql = "UPDATE $table SET name=?, description=?, price=?, stocks=?, image_path=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdssi", $name, $description, $price, $stock, $target_file, $id);
    } else {
        $sql = "INSERT INTO $table (name, description, price, stocks, image_path) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $name, $description, $price, $stock, $target_file);
    }

    if ($stmt->execute()) {
        echo ucfirst($type) . ($id ? " updated" : " added") . " successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Fetching products and accessories
$sql_products = "SELECT id, name AS name, description, price, stocks, image_path FROM products ORDER BY id";
$sql_accessories = "SELECT id, name AS name, description, price, stocks, image_path FROM accessories ORDER BY id";
$result_products = $conn->query($sql_products);
$result_accessories = $conn->query($sql_accessories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product & Accessories Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 30px; }
        .container { max-width: 900px; margin: auto; }
        .form-section { background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .table-responsive { margin-top: 20px; }
        .img-thumbnail { max-width: 100px; }
        .btn-toggle { margin-bottom: 20px; }
        .return-button {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1000;
}

.return-link {
    display: inline-block;
    background-color: #ff6f61;
    color: #fff;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.return-link:hover {
    background-color: #ff5a4d;
}

    </style>
</head>
<body>
<div class="container">
    <div class="return-button">
        <a href="index.php" class="return-link">Home</a>
    </div>
</div>
<div class="container">
    
    <h2 class="text-center">Product & Accessories Management</h2>

    <!-- Toggle Between Product and Accessories -->
    <div class="text-center btn-toggle">
        <a href="?add_type=product" class="btn btn-primary">Add Product</a>
        <a href="?add_type=accessory" class="btn btn-secondary">Add Accessory</a>
    </div>

    <!-- Add / Edit Form -->
    <div class="form-section">
        <h4><?php echo isset($_GET['edit_id']) ? 'Edit' : 'Add'; ?> <?php echo ucfirst($_GET['add_type'] ?? 'product'); ?></h4>
        <form action="" method="post" enctype="multipart/form-data">
            <?php if (isset($_GET['edit_id'])): ?>
                <?php
                $edit_id = $_GET['edit_id'];
                $type = $_GET['type'];
                $table = ($type === 'product') ? 'products' : 'accessories';
                $sql = "SELECT name, description, price, stocks, image_path FROM $table WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $edit_id);
                $stmt->execute();
                $stmt->bind_result($name, $description, $price, $stock, $image_path);
                $stmt->fetch();
                $stmt->close();
                ?>
                <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
                <input type="hidden" name="type" value="<?php echo $type; ?>">
            <?php else: ?>
                <input type="hidden" name="type" value="<?php echo $_GET['add_type'] ?? 'product'; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required><?php echo isset($description) ? $description : ''; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo isset($price) ? $price : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo isset($stock) ? $stock : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <?php if (isset($image_path)): ?>
                    <img src="<?php echo $image_path; ?>" alt="Image" class="img-thumbnail mt-2">
                    <input type="hidden" name="existing_image" value="<?php echo $image_path; ?>">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary"><?php echo isset($_GET['edit_id']) ? 'Update' : 'Add'; ?></button>
        </form>
    </div>

    <!-- Products and Accessories List -->
    <div class="table-responsive">
        <h4 class="mt-4">Products</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_products->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['stocks']; ?></td>
                        <td><img src="<?php echo $row['image_path']; ?>" alt="Product Image" class="img-thumbnail"></td>
                        <td>
                            <a href="?edit_id=<?php echo $row['id']; ?>&type=product" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete_id=<?php echo $row['id']; ?>&type=product" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h4 class="mt-4">Accessories</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Acc ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_accessories->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['stocks']; ?></td>
                        <td><img src="<?php echo $row['image_path']; ?>" alt="Accessory Image" class="img-thumbnail"></td>
                        <td>
                            <a href="?edit_id=<?php echo $row['id']; ?>&type=accessory" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete_id=<?php echo $row['id']; ?>&type=accessory" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
