<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}

$user = $_SESSION['user'];

$servername = "localhost";
$username = 'root';
$password = '';
$dbname = "tentian";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recordsPerPage = 10;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $recordsPerPage;

// Fetch distinct values for filters
$productNameQuery = "SELECT DISTINCT producttype FROM inventory";
$productNames = $conn->query($productNameQuery)->fetch_all(MYSQLI_ASSOC);

$categoryQuery = "SELECT DISTINCT category FROM inventory";
$categories = $conn->query($categoryQuery)->fetch_all(MYSQLI_ASSOC);

$statusQuery = "SELECT DISTINCT status FROM inventory";
$statuses = $conn->query($statusQuery)->fetch_all(MYSQLI_ASSOC);

// Get selected filters
$productNameFilter = $_POST['product-name-filter'] ?? '';
$categoryFilter = $_POST['category-filter'] ?? '';
$statusFilter = $_POST['status-filter'] ?? '';
$sortOrder = $_POST['sort-order'] ?? 'DESC'; // Default to newest first

// Build SQL query with filters
$sql = "SELECT producttype, product_id, price, description, category, supplier, status, datetime,image FROM inventory";
$conditions = [];

if (!empty($productNameFilter)) {
    $conditions[] = "producttype = '$productNameFilter'";
}
if (!empty($categoryFilter)) {
    $conditions[] = "category = '$categoryFilter'";
}
if (!empty($statusFilter)) {
    $conditions[] = "status = '$statusFilter'";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Apply sorting
$sql .= " ORDER BY datetime $sortOrder LIMIT $offset, $recordsPerPage";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../admin/files/admin.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
    <title>Show Inventory</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
    <style>
        .filter {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <?php include ('../admin/adminsidebar.php')?>

    <div class="main-content">
        <div class="showI"><h1>Show Inventory</h1></div>
        <div class="showI-table" data-aos="fade-up">
            <form method="POST">
                <div class="filter">
                    <div class="filter-group">
                        <label for="product-name-filter">Product Name:</label>
                        <select name="product-name-filter">
                            <option value="">All Products</option>
                            <?php foreach ($productNames as $product) : ?>
                                <option value="<?= $product['producttype'] ?>" <?= ($product['producttype'] === $productNameFilter) ? 'selected' : '' ?>>
                                    <?= $product['producttype'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label for="sort-order">Sort by Date:</label>
                        <select name="sort-order">
                            <option value="ASC" <?= ($sortOrder === 'ASC') ? 'selected' : '' ?>>Oldest First</option>
                            <option value="DESC" <?= ($sortOrder === 'DESC') ? 'selected' : '' ?>>Newest First</option>
                        </select>
                    </div>

                    <label for="category-filter">Category:</label>
                    <select name="category-filter">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category['category'] ?>" <?= ($category['category'] === $categoryFilter) ? 'selected' : '' ?>>
                                <?= $category['category'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="status-filter">Status:</label>
                    <select name="status-filter">
                        <option value="">All Statuses</option>
                        <?php foreach ($statuses as $status) : ?>
                            <option value="<?= $status['status'] ?>" <?= ($status['status'] === $statusFilter) ? 'selected' : '' ?>>
                                <?= $status['status'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" name="apply" class="apply-filter">Apply Filter</button>
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Order Type</th>                        
                        <th>Product ID</th>                        
                        <th>Price</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row["producttype"] ?></td>
                                <td><?= $row["product_id"] ?></td>
                                <td>₱<?= number_format($row["price"], 2) ?></td>
                                <td><?= $row["description"] ?></td>
                                <td><?= $row["category"] ?></td>
                                <td><?= $row["supplier"] ?></td>
                                <td><?= $row["status"] ?></td>
                                <td><?= date("F j, Y", strtotime($row["datetime"])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr><td colspan="8">No inventory records found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php
        // Retrieve the total number of records
        $totalRecords = $conn->query("SELECT COUNT(*) AS total FROM inventory")->fetch_assoc()['total'];
        $totalPages = ceil($totalRecords / $recordsPerPage);
        ?>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a class="page-link <?= ($i === $currentPage) ? "active" : "" ?>" href="show_inventory.php?page=<?= $i ?>"><?= $i ?></a>
            <?php endfor; ?>
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
