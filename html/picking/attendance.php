<?php
require 'common.php';

//Grab all users from our database
$items = $database->select("items", [
    'id',
    'name',
]);

//Check if we have a year passed in through a get variable, otherwise use the current year
if (isset($_GET['year'])) {
    $current_year = int($_GET['year']);
} else {
    $current_year = date('Y');
}

//Check if we have a month passed in through a get variable, otherwise use the current year
if (isset($_GET['month'])) {
    $current_month = $_GET['month'];
} else {
    $current_month = date('n');
}

//Calculate the amount of days in the selected month
$num_days = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);

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
        <a class="navbar-brand" href="#">RFID order picking system</a>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="attendance.php" class="nav-link active">View </a>
            </li>
            <li class="nav-item">
                <a href="users.php" class="nav-link">View Items</a>
            </li>
        </ul>
    </nav>
    <div class="container">
        <div class="row">
            <h2>Pick List</h2>
        </div>
        <table class="table table-striped table-responsive">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <?php
                        //Generate headers for all the available days in this month
                        for ( $iter = 1; $iter <= $num_days; $iter++) {
                            echo '<th scope="col" style="min-width:200px;max-width:300px;">' . $iter . '</th>';
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    //Loop through all our available users
                    foreach($items as $item) {
                        echo '<tr>';
                        echo '<td scope="row">' . $item['name'] . '</td>';

                        //Iterate through all available days for this month
                        for ( $iter = 1; $iter <= $num_days; $iter++) {
                            
                            //For each pass grab any attendance that this particular user might of had for that day
                            $orderpicking = $database->select("picklist", [
                                'clock_in'
                            ], [
                                'item_id' => $item['id'],
                                'clock_in[<>]' => [
                                    date('Y-m-d', mktime(0, 0, 0, $current_month, $iter, $current_year)),
                                    date('Y-m-d', mktime(24, 60, 60, $current_month, $iter, $current_year))
                                ]
                            ]);

                            //Check if our database call actually found anything
                            if(!empty(orderpicking)) {
                                //If we have found some data we loop through that adding it to the tables cell
                                echo '<td class="table-success">';
                                foreach($orderpicking as $orderpicking_data) {
                                    echo $orderpicking_data['clock_in'] . '</br>';
                                }
                                echo '</td>';
                            } else {
                                //If there was nothing in the database notify the user of this.
                                echo '<td class="table-secondary">No Data</td>';
                            }
                        }
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</html>
