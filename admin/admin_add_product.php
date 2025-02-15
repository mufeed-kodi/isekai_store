<?php
// admin/admin_add_product.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';
$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = isset($_POST['category_id']) && $_POST['category_id'] != '' ? intval($_POST['category_id']) : 'NULL';

    // Handle file upload if an image is selected
    $image = 'default.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../images/";
        $filename = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $filename;
        } else {
            $errors[] = "Failed to upload image.";
        }
    }
    
    if(empty($errors)){
        $insertQuery = "INSERT INTO products (category_id, name, description, price, stock, image) 
                        VALUES ($category_id, '$name', '$description', '$price', '$stock', '$image')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $errors[] = "Error: " . $conn->error;
        }
    }
}

$catResult = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai Admin - Add Product</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h2>Add New Product</h2>
    <?php
    if(!empty($errors)){
        foreach($errors as $error){
            echo "<p class='error'>$error</p>";
        }
    }
    ?>
    <form method="POST" action="admin_add_product.php" enctype="multipart/form-data">
        <label>Category:</label><br>
        <select name="category_id">
            <option value="">-- Select Category --</option>
            <?php while($cat = $catResult->fetch_assoc()){ ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
            <?php } ?>
        </select><br>
        <label>Product Name:</label><br>
        <input type="text" name="name" required><br>
        <label>Description:</label><br>
        <textarea name="description" required></textarea><br>
        <label>Price:</label><br>
        <input type="number" name="price" step="0.01" required><br>
        <label>Stock:</label><br>
        <input type="number" name="stock" value="0" required><br>
        <label>Image:</label><br>
        <input type="file" name="image"><br><br>
        <button type="submit">Add Product</button>
    </form>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
