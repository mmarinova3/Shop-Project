<!DOCTYPE html>
<html>
<head>
    <title>Търсене на Продукти</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            text-align: center;
            background-color:  #f2f2f2;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            padding: 5px;
            width: 200px;
        }

        table {
            border-collapse: collapse;
            margin: 0 auto;
        }

        table th,
        table td {
            border: 2px solid black;
            padding: 8px;
        }

        table th {
            background-color: #f2f2f2;
        }

        a {
            color: black;
            text-decoration: none;
            font-weight: bold;
        }

        .search-label {
            font-style: italic;
            font-size: 12px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Търсене на Продукти</h1>
    <div class="search-label">по цена, наименование, група</div>
    <form method="get">
        <label for="search_query">Въведи:</label>
        <input type="text" name="search_query" id="search_query">
        <button type="submit">Търси</button>
    </form>
    <?php
    include "config.php";

    function search_products($search_query) {
        $dbConn = mysqli_connect('localhost', 'root', '');
        mysqli_select_db($dbConn, 'shop_db');

        $search_query = mysqli_real_escape_string($dbConn, $search_query);

        $sql = "SELECT Product.*, Type.name AS type_name FROM Product JOIN Type ON Product.type_id = Type.group_id WHERE Product.name LIKE '%$search_query%' OR Product.unit_price LIKE '%$search_query%' OR Type.name LIKE '%$search_query%'";
        $result = mysqli_query($dbConn, $sql);

        $products = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        mysqli_close($dbConn);

        if (empty($products)) {
            echo "Няма намерени продукти.";
        }

        return $products;
    }

    if (isset($_GET['search_query'])) {
        $search_query = $_GET['search_query'];
        $products = search_products($search_query);
        if (!empty($products)) {
            echo "<h2>Резултати от търсенето:</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Име</th><th>Цена</th><th>Група</th></tr>";
            foreach ($products as $product) {
                echo "<tr>";
                echo "<td>" . $product['product_id'] . "</td>";
                echo "<td>" . $product['name'] . "</td>";
                echo "<td>" . $product['unit_price'] . "</td>";
                echo "<td>" . $product['type_name'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
    ?>
    <p><a href="shop_index.php">>>Към началната страница</a></p>
</body>
</html>
