<?php

$pdo = new PDO("mysql:host=localhost;port=3306;dbname=products_crud", 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT * FROM products WHERE id = :id";
$stmt = $pdo->prepare($sql);
$params = [
    'id' => $id
];
$stmt->execute($params);
$product = $stmt->fetch(PDO::FETCH_ASSOC);


// echo "<pre>";
// var_dump($product);
// echo "</pre>";
// exit;


$error = [];

$title = $product['title'];
$description = $product['description'];
$price = $product['price'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    if (!$title) {
        $error[] = "Product Title is Required!";
    }
    if (!$description) {
        $error[] = "Product Description is required!";
    }
    if (!$price) {
        $error[] = "Product Price is required!";
    }

    if (!is_dir('images')) {
        mkdir('images');
    }


    if (empty($error)) {

        $image = $_FILES['image'] ??  null;

        $imagePath = $product['image'];

        if ($image && $image['tmp_name']) {
            if ($product['image']) {
                unlink($product['image']);
            }

            $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        $sql = "UPDATE products SET title= :title, description= :description, image= :image, price= :price WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $params = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'image' => $imagePath,
            'price' => $price
        ];

        $result = $stmt->execute($params);
        header('Location: index.php');
    }
}

// Function to generate a random name for folder of the file
function randomString($n)
{
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}



?>




<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Table Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
</head>

<body>
    <h3>Update Product: <strong><?= $product['title'] ?></strong></h3>
    <div class="container mt-2">
        <a href="index.php" class="btn btn-secondary mb-4">List of Product</a>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php foreach ($error as $error): ?>
                    <div><?= $error ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <?php if ($product['image']): ?>
                <img class="update-image" src="<?= $product['image'] ?>" alt="">
            <?php endif; ?>

            <div class="mb-3">
                <label>Product Image</label>
                <input name="image" type="file" class="form-control">
            </div>
            <div class="mb-3">
                <label>Product Title</label>
                <input name="title" type="text" class="form-control" value="<?= $title ?>">
            </div>
            <div class="mb-3">
                <label>Product description</label>
                <textarea name="description" class="form-control"><?= $description ?></textarea>
            </div>
            <div class="mb-3">
                <label>Price</label>
                <input name="price" type="number" step="0.01" class="form-control" value="<?= $price ?>">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>

</body>

</html>