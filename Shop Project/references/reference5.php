<!DOCTYPE html>
<html>
<head>
    <title>Продажби по дата</title>
    <style>
        body {
            background-color: #f2f2f2;
            color: black;
            text-align: center;
            padding: 20px;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
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
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
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
    <div class="container">
        <?php
        include "config.php";

        if (isset($_POST['submit'])) {
            $date = $_POST['date'];
        } else {
            $date = date('Y-m-d');
        }

        $sql = "SELECT Sale.sale_id, Product.name AS product_name, Sale.price, Sale.date, Customer.name AS customer_name, Employee.name AS employee_name
                FROM Sale
                INNER JOIN Product ON Sale.product_id = Product.product_id
                INNER JOIN Customer ON Sale.customer_id = Customer.customer_id
                INNER JOIN Employee ON Sale.employee_id = Employee.employee_id
                WHERE Sale.date = '$date'";

        $result = mysqli_query($dbConn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<h2>Продажби на дата $date</h2>";
            echo "<form method='post'><label for='date'>Дата: </label><input type='date' name='date' value='$date'><input type='submit' name='submit' value='Покажи'></form>";
            echo "<table>";
            echo "<tr><th>ID на продажбата</th><th>Продукт</th><th>Цена</th><th>Дата</th><th>Клиент</th><th>Служител</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['sale_id'] . "</td>";
                echo "<td>" . $row['product_name'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>" . $row['customer_name'] . "</td>";
                echo "<td>" . $row['employee_name'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<h2>Няма доставки на тази дата.</h2>";
            echo "<form method='post'><label for='date'>Дата: </label><input type='date' name='date' value='$date'><input type='submit' name='submit' value='Покажи'></form>";
        }

        mysqli_close($dbConn);
        ?>
        <p><a href="shop_index.php">>>Към началната страница</a></p>
    </div>
</body>
</html>
