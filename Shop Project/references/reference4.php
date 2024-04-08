<!DOCTYPE html>
<html>
<head>
    <title>Продажби на топ 5 продукта</title>
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
        
        $sql_top_products = "SELECT Product.name AS product_name, COUNT(Sale.product_id) AS product_count,  Product.unit_price AS unit_price
                             FROM Sale
                             INNER JOIN Product ON Sale.product_id = Product.product_id
                             GROUP BY Product.name
                             ORDER BY product_count DESC
                             LIMIT 5";
        $result_top_products = mysqli_query($dbConn, $sql_top_products);

        if (!$result_top_products) {
            die('Грешка при извличане на данни за отчета за продажбите на топ 5 продукта: ' . mysqli_error($dbConn));
        }

        $num_rows_top_products = mysqli_num_rows($result_top_products);

        if ($num_rows_top_products > 0) {
            echo "<h2>Топ 5 класация по брой продажби на продукти:</h2>";
            echo '<table>';
            echo '<tr><th>Продукт</th><th>Цена</th><th>Брой продажби</th></tr>';
            while ($row_top_products = mysqli_fetch_assoc($result_top_products)) {
                echo '<tr>';
                echo '<td>' . $row_top_products['product_name'] . '</td>';
                echo '<td>' . $row_top_products['unit_price'] . '</td>';
                echo '<td>' . $row_top_products['product_count'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "<h2>Няма намерени продажби за нито един продукт.</h2>";
        }

        mysqli_close($dbConn);
        ?>
        <p><a href="shop_index.php">>>Към началната старница</a></p>
    </div>
</body>
</html>
