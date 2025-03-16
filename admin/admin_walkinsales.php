<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sales_name = $_POST['sales_name'];
    $full_name = $_POST['full_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $sales_date = $_POST['sales_date'];

    include('../Connection/Connection.php');

    // Get the last sales_id from the database
    $query = $conn->query("SELECT MAX(CAST(SUBSTRING_INDEX(sales_id, ':', -1) AS UNSIGNED)) AS max_id FROM sales WHERE sales_id LIKE 'walkin:%'");
    $result = $query->fetch(PDO::FETCH_ASSOC);

    // Determine the next sales_id
    $nextId = $result['max_id'] ? $result['max_id'] + 1 : 1;
    $sales_id = "walkin:" . $nextId;

    // Insert into database
    $sql = "INSERT INTO sales (sales_id, sales_name, full_name, quantity, price, sales_date) 
            VALUES (:sales_id, :sales_name, :full_name, :quantity, :price, :sales_date)";
    
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':sales_id', $sales_id);
    $stmt->bindParam(':sales_name', $sales_name);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':sales_date', $sales_date);

    try {
        $stmt->execute();
        echo "Data inserted successfully! Sales ID: " . $sales_id;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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
    <title>Add Customer</title>
    <link rel="icon" href="../TentianSys/image/Icon.png" type="image/x-icon">    
</head>
<body>
    <?php include ('../admin/adminsidebar.php')?>

    <div class="main-content">
        <div class="form-group" data-aos="zoom-in" data-aos-duration="1000">
            <div class="header-font">
                <h1>Walk-in Sales</h1>
            </div>
            <form id="customerForm" method="POST">

            <input type="hidden" name="sales_id" value="<?php echo $sales_id; ?>">

            <input type="number" name="full_name" hidden value="<?=rand(0,1000);?>">
                <div class="form-row">
                    <label for="fullName">Order Name:</label>
                    <div class="input-container">
                        <input type="text" id="sales_name" name="sales_name" required>
                    </div>
                </div>

                <div class="form-row">
                    <label for="completeAddress">Quantity:</label>
                    <div class="input-container">
                <input type="number" name="quantity" id="quantity" required>
                </div>
                </div>

                <div class="form-row">
                    <label for="completeAddress">Price:</label>
                    <div class="input-container">
                    <input type="number" name="price" id="price" required>
                    </div>
                </div>

                <div class="form-row">
                    <label for="completeAddress">Date today:</label>
                    <div class="input-container">
                    <input type="datetime-local" name="sales_date" id="sales_date">
                </div>
                </div>

                <div>
                    <button type="submit" name="add" id="addCustomerButton">Create Order</button>
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
