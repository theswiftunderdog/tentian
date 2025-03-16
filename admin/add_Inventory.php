<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');

$user = $_SESSION['user'];

include('../Connection/Connection.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId'];
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $supplier = $_POST['supplier'];
    $status = $_POST['status'];
    $quantity = $_POST['quantity'];
    $dateTime = date('Y-m-d H:i:s');

    $image = $_FILES['image']['name'];
    $targetDir = "uploads/"; 
    $targetFile = $targetDir . basename($image);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO inventory (product_id, product_name, price, description, category, supplier, status, quantity, datetime, image) 
            VALUES (:product_id, :product_name, :price, :description, :category, :supplier, :status, :quantity, :datetime, :image)");
        
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':supplier', $supplier);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':datetime', $dateTime);
        $stmt->bindParam(':image', $image);
        
        if ($stmt->execute()) {
header("location:show_inventory.php");
exit();
        } else {
            echo "<script>alert('Failed to add product!');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../admin/files/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&family=Roboto+Condensed&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Titillium+Web&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
    <title>Add Inventory</title>
    <link rel="icon" href="../TentianSys/image/Icon.png" type="image/x-icon">    
</head>
<body>
    <?php include ('../admin/adminsidebar.php')?>

    <div class="main-content">
        <div class="form-group" data-aos="zoom-in" data-aos-duration="1000">
            <div class="header-font">
                <h1>Add Product</h1>
            </div>
            <form id="productForm" method="POST" enctype="multipart/form-data" >
                <div class="form-row">
                    <label for="productId">Product ID:</label>
                    <div class="input-container">
                        <input type="text" id="productId" name="productId" required>
                    </div>
                </div>
                <div class="form-row">
                    <label for="productName">Product Name:</label>
                    <div class="input-container">
                        <input type="text" id="productName" name="productName" required>
                    </div>
                </div>
                <div class="form-row">
                    <label for="price">Price:</label>
                    <div class="input-container">
                        <input type="number" id="price" name="price" required>
                    </div>
                </div>
                <div class="form-row">
                    <label for="quantity">Quantity:</label>
                    <div class="input-container">
                        <input type="number" id="quantity" name="quantity" required>
                    </div>
                </div>
                <div class="form-row">
                    <label for="description">Description:</label>
                    <div class="input-container">
                        <textarea id="description" name="description" required></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <label for="supplier">Supplier:</label>
                    <div class="input-container">
                        <input type="text" id="supplier" name="supplier" required>
                    </div>
                </div>

                <div class="form-row">
                    <label for="category">Category:</label>
                    <div class="input-container">
                        <select id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="For Machine">For Machine</option>
                            <option value="For Sale">For Sale</option>
                            <option value="For Operation Use">For Operation Use</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <label for="status">Status:</label>
                    <div class="input-container">
                        <select id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="In Use">In Use</option>
                            <option value="Stock">Stock</option>
                            <option value="Empty">Empty</option>
                            <option value="Broken">Broken</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <label for="image">Image Upload</label>
                    <div class="input-container">
                        <input type="file" id="image" name="image" required>
                    </div>
                </div>

                <div>
                    <button type="submit" name="add" id="addProductButton">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../admin/files/adminscript.js"></script>
    <script src="../admin/files/rotate.js"></script>
    
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    
    <script>
    AOS.init();
    </script>
</body>
</html>
