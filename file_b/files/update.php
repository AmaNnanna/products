<?php

include "connectfiles/header.php";
require_once "connectfiles/dbconnect.php";



$statement = $pdo->prepare('UPDATE products SET title = :title, image = :image, description = :description, date = :date WHERE id = :id');
$statement->bindValue(':title', $title);
$statement->bindValue(':image', $imagePath);
$statement->bindValue(':description', $description);
$statement->bindValue(':date', $date);
$statement->bindValue(':id', $id);

$statement->execute();

?>