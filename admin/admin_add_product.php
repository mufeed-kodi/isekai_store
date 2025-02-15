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
        <label>Category:</label>
        <select name="category_id">
            <option value="">-- Select Category --</option>
            <?php while($cat = $catResult->fetch_assoc()){ ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
            <?php } ?>
        </select>
        <label>Product Name:</label>
        <textarea name="name" required rows="2" cols="20"></textarea>
        <label>Description:</label>
        <textarea name="description" required rows="5" cols="20"></textarea>
        <label>Price:</label>
        <input type="number" name="price" step="0.01" required>
        <label>Stock:</label>
        <input type="number" name="stock" value="0" required>
        <label>Image:</label>
        <input type="file" name="image">
        <button type="submit">Add Product</button>
    </form>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
