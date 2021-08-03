<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=userlog', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$statement->bindValue(':id', $id);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);

$errors = [];

$title = $product['title'];
$description = $product['description'];
$price = $product['price'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date = date('Y-m-d H:i:s');

    if (!$title) {
        $errors[] = 'Provide title for the new product';
    }
    if (!$price) {
        $errors[] = 'Provide price for the new product';
    }

    if (empty($errors)) {

        $image = $_FILES['image'] ?? null;
        $imagePath = $product['image'];

        if ($image && $image['tmp_name']) {

            if ($product['image']) {
                unlink($product['image']);
            }

            $imagePath = 'images/' . randomString(6) . '/' . $image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        $statement = $pdo->prepare('UPDATE products SET title = :title, image = :image, description = :description, date = :date WHERE id = :id');

        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':date', $date);
        $statement->bindValue(':id', $id);

        $statement->execute();

        header('Location: index.php');
    }
}

function randomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Product Page</title>
</head>

<body>

    <h1 style="text-align: center;" class="my-3">Update <b><?php echo $product['title'] ?></b></h1>

    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger" style="text-align: center;">
            <?php foreach ($errors as $error) : ?>
                <div><?php echo $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="mx-5 px-5" action="" method="POST" enctype="multipart/form-data">

        <?php if ($product['image']) : ?>
            <img src="<?php echo $product['image'] ?>" alt="" style="width: 120px;">
        <?php endif; ?>


        <div class="input-group mb-3 form-group">
            <input type="file" class="form-control" name="image">
            <label class="input-group-text" for="image"></label>
        </div>
        <div class="mb-3 form-group">
            <label for="title" class="form-label">Product Title</label>
            <input type="text" class="form-control" name="title" value="<?php echo $title ?>">
        </div>
        <div class="mb-3 form-group">
            <label for="description" class="form-label">Product Description</label>
            <textarea class="form-control" name="description"><?php echo $description ?></textarea>
        </div>
        <div class="mb-3 form-group">
            <label for="price" class="form-label">Product Price</label>
            <input type="number" step="0.01" class="form-control" name="price" value="<?php echo $price ?>">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>


    </form>



</body>

</html>