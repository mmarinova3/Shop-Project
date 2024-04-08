<!DOCTYPE html>
<html>
<head>
    <title>Продажби за период</title>
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

        input[type="date"] {
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

    if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $sql = "SELECT sale_id, product.name AS product_name, customer.name AS customer_name, employee.name AS employee_name, date, price
                FROM Sale
                INNER JOIN Product ON Sale.product_id = Product.product_id
                INNER JOIN Customer ON Sale.customer_id = Customer.customer_id
                INNER JOIN Employee ON Sale.employee_id = Employee.employee_id
                WHERE date BETWEEN '$start_date' AND '$end_date'";
        $result = mysqli_query($dbConn, $sql);
        if (!$result) {
            die('Грешка при извличане на данни от таблицата Sale: ' . mysqli_error($dbConn));
        }
        echo "<h2>Продажби за период $start_date - $end_date</h2>";
    } else {
        echo "<h2>Изберете период за продажби:</h2>";
    }
    ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="start_date">Начална дата:</label>
        <input type="date" name="start_date" id="start_date">
        <br><br>
        <label for="end_date">Крайна дата:</label>
        <input type="date" name="end_date" id="end_date">
        <br><br>
        <input type="submit" value="Покажи">
    </form>

    <?php
    if (isset($result)) {
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
    }
    ?>
<p><a href="shop_index.php">>>Към началната старница</a></p>
</body>
</html>
