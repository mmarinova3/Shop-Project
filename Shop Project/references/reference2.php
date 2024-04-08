<!DOCTYPE html>
<html>
<head>
    <title>Продажби за служител</title>
    <style>
        body {
            background-color: #f2f2f2;
            color: black;
            text-align: center;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            margin: 20px auto;
            background-color: white;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 2px solid black;
        }

        th {
            background-color: #333;
            color: white;
        }

        form {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        select {
            padding: 5px;
        }

        input[type="submit"] {
            padding: 5px 10px;
        }

        h2 {
            margin-bottom: 20px;
        }

        a {
            color: black;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php
    include "config.php";

    if (isset($_POST['employee_name'])) {
        $employee_name = $_POST['employee_name'];

        $sql = "SELECT sale_id, product.name AS product_name, customer.name AS customer_name, employee.name AS employee_name, date, price
                FROM Sale
                INNER JOIN Product ON Sale.product_id = Product.product_id
                INNER JOIN Customer ON Sale.customer_id = Customer.customer_id
                INNER JOIN Employee ON Sale.employee_id = Employee.employee_id
                WHERE Employee.name = '$employee_name'
                ORDER BY date ASC";
        $result = mysqli_query($dbConn, $sql);
        if (!$result) {
            die('Грешка при извличане на данни от таблицата Sale: ' . mysqli_error($dbConn));
        }

        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            echo "<h2>Продажби за служител $employee_name, подредени по дата</h2>";
            echo '<table>';
            echo '<tr><th>ID</th><th>Продукт</th><th>Клиент</th><th>Служител</th><th>Дата</th><th>Цена</th></tr>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['sale_id'] . '</td>';
                echo '<td>' . $row['product_name'] . '</td>';
                echo '<td>' . $row['customer_name'] . '</td>';
                echo '<td>' . $row['employee_name'] . '</td>';
                echo '<td>' . $row['date'] . '</td>';
                echo '<td>' . $row['price'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "<h2>Служителят $employee_name няма продажби.</h2>";
        }

    } else {
        echo "<h2>Изберете служител за продажби:</h2>";
    }

    $employee_query = "SELECT DISTINCT name FROM Employee";
    $employee_result = mysqli_query($dbConn, $employee_query);

    ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="employee_name">Служител:</label>
        <select name="employee_name" id="employee_name">
            <?php
            while ($employee_row = mysqli_fetch_assoc($employee_result)) {
                echo '<option value="' . $employee_row['name'] . '">' . $employee_row['name'] . '</option>';
            }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Покажи">
    </form>

    <p><a href="shop_index.php">>>Към началната старница</a></p>

</body>
</html>
