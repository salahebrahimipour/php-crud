<?php
$pdo = new PDO("mysql:host=localhost;port=3306;dbname=products_crud", 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$search = $_GET['search'] ?? '';

if ($search) {
    $sql = "SELECT * FROM products WHERE title LIKE :title ORDER BY id DESC";
    $params = ['title' => "%$search%"];
} else {
    $sql = "SELECT * FROM products ORDER BY id DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params ?? []);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <h3>Crud application</h3>
    <div class="container mt-5">
        <a href="create.php" class="btn btn-success">Create Product</a>

        <form>
            <div class="input-group mb-5 mt-5">
                <input type="text" class="form-control" placeholder="Search for Products username" name="search" value="<?= $search ?>">
                <button class="btn btn-secondary" type="submit" id="button-addon2">Search</button>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Title</th>
                    <th scope="col">Price</th>
                    <th scope="col">Create Date</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $i => $product): ?>
                    <tr>
                        <th scope="row"><?= $i + 1 ?></th>
                        <td><img src="<?= $product['image'] ?>" style="width:100px;height:100px;"></td>
                        <td><?= $product['title'] ?></td>
                        <td><?= $product['price'] ?></td>
                        <td><?= $product['created_at'] ?></td>
                        <td>
                            <a href="update.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary">Edit</a>
                            <form action="delete.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>