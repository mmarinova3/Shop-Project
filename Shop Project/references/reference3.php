<!DOCTYPE html>
<html>
<head>
    <title>Продажби за клиент</title>
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
    <div class="container">
        <?php
        include "config.php";

        if (isset($_POST['customer_name'])) {
            $customer_name = $_POST['customer_name'];

            $sql = "SELECT sale_id, product.name AS product_name, customer.name AS customer_name, employee.name AS employee_name, date, price
                    FROM Sale
                    INNER JOIN Product ON Sale.product_id = Product.product_id
                    INNER JOIN Customer ON Sale.customer_id = Customer.customer_id
                    INNER JOIN Employee ON Sale.employee_id = Employee.employee_id
                    WHERE Customer.name = '$customer_name'
                    ORDER BY date ASC";
            $result = mysqli_query($dbConn, $sql);
            if (!$result) {
                die('Грешка при извличане на данни от таблицата Sale: ' . mysqli_error($dbConn));
            }

            $num_rows = mysqli_num_rows($result);

            if ($num_rows > 0) {
                echo "<h2>Продажби за клиент $customer_name</h2>";
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
                echo "<h2>Клиентът $customer_name няма продажби.</h2>";
            }

        } else {
            echo "<h2>Изберете клиент за продажби:</h2>";
        }

        $customer_query = "SELECT DISTINCT name FROM Customer";
        $customer_result = mysqli_query($dbConn, $customer_query);

        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="customer_name">Клиент:</label>
            <select name="customer_name" id="customer_name">
                <?php
                while ($customer_row = mysqli_fetch_assoc($customer_result)) {
                    echo '<option value="' . $customer_row['name'] . '">' . $customer_row['name'] . '</option>';
                }
                ?>
            </select>
            <br><br>
            <input type="submit" value="Покажи">
        </form>

        <p><a href="shop_index.php">>>Към началната старница</a></p>
    </div>
</body>
</html>
