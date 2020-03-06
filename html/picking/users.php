<?php
require 'common.php';

//Grab all the users from our database
$items = $database->select("items", [
    'id',
    'name',
    'rfid_uid'
]);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Order Picking System</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>

    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Order Picking System</a>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="attendance.php" class="nav-link">View Order Items</a>
            </li>
            <li class="nav-item">
                <a href="users.php" class="nav-link active">Item Pick list</a>
            </li>
        </ul>
    </nav>
    <div class="container">
        <div class="row">
            <h2>Order Pick List</h2>
        </div>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Item</th>
                    <th scope="col">RFID UID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //Loop through and list all the information of each user including their RFID UID
                foreach($items as $item) {
                    echo '<tr>';
                    echo '<td scope="row">' . $item['id'] . '</td>';
                    echo '<td>' . $item['name'] . '</td>';
                    echo '<td>' . $item['rfid_uid'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</html>
