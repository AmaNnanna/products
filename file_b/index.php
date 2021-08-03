<?php

include "connectfiles/header.php";
require_once "connectfiles/dbconnect.php";

$search = $_GET['search'] ?? '';

if ($search) {
    $statement = $pdo->prepare('SELECT * FROM products WHERE title LIKE :title ORDER BY title DESC');
    $statement->bindValue(':title', "%$search%");
}else{
    $statement = $pdo->prepare('SELECT * FROM products ORDER BY title DESC');
}
$statement->execute();

$products = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Product Page</h1>

    <form action="">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search for products" name="search" value="<?php echo $search ?>">
            <div class="input-group-append">
            <button class="btn btn-secondary" type="submit">Search</button>
            </div>
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
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>

            <?php

            foreach ($products as $i => $product) { ?>
                <tr>
                    <th scopte="row"><?php echo $i + 1 ?></th>
                    <td>
                        <img src="<?php echo $product['image'] ?>" style="width: 100px; height: auto;">
                    </td>
                    <td><?php echo $product['title'] ?></td>
                    <td><?php echo $product['price'] ?></td>
                    <td><?php echo $product['date'] ?></td>
                    <td>
                        <a href="update.php?id=<?php echo $product['id'] ?>" class="btn btn-sm btn-light">Edit</a>
                        <form action="delete.php" method="POST" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?php echo $product['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php }

            ?>

        </tbody>
    </table>

    <p><a href="create.php" class="btn btn-success">Add New Product</a></p>

    <?php

    include "connectfiles/footer.php";

    ?>