<?php
// admin/admin_edit_product.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';
$errors = array();

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product details
$query = "SELECT * FROM products WHERE id='$product_id'";
$result = $conn->query($query);
if ($result->num_rows !== 1) {
    die("Product not found.");
}
$product = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = isset($_POST['category_id']) && $_POST['category_id'] != '' ? intval($_POST['category_id']) : 'NULL';

    // Handle new image upload if provided
    $image = $product['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../images/";
        $filename = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $filename;
        } else {
            $errors[] = "Failed to upload new image.";
        }
    }
    
    if(empty($errors)){
        $updateQuery = "UPDATE products SET 
                        category_id=$category_id, 
                        name='$name', 
                        description='$description', 
                        price='$price', 
                        image='$image' 
                        WHERE id='$product_id'";
        if ($conn->query($updateQuery) === TRUE) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $errors[] = "Error: " . $conn->error;
        }
    }
}

// Fetch categories for selection
$catResult = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai Admin - Edit Product</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h2>Edit Product</h2>
    <?php
    if(!empty($errors)){
        foreach($errors as $error){
            echo "<p class='error'>$error</p>";
        }
    }
    ?>
    <form method="POST" action="admin_edit_product.php?id=<?php echo $product_id; ?>" enctype="multipart/form-data">
        <label>Category:</label><br>
        <select name="category_id">
            <option value="">-- Select Category --</option>
            <?php while($cat = $catResult->fetch_assoc()){ ?>
                <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $product['category_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php } ?>
        </select><br>
        <label>Product Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>
        <label>Description:</label><br>
        <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br>
        <label>Price:</label><br>
        <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required><br>
        <label>Current Image:</label><br>
        <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" width="80" height="80"><br>
        <label>Change Image (optional):</label><br>
        <input type="file" name="image"><br><br>
        <button type="submit">Update Product</button>
    </form>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
