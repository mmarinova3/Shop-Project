<!DOCTYPE html>
<html>
<head>
    <title>Доставки от доставчик</title>
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

        $provider = isset($_POST['provider']) ? $_POST['provider'] : '';
        $sql_providers = "SELECT DISTINCT provider_eik, provider FROM Deliveries JOIN Provider ON Deliveries.provider_eik = Provider.eik";
        $result_providers = mysqli_query($dbConn, $sql_providers);

        if (!$result_providers) {
            die('Грешка: ' . mysqli_error($dbConn));
        }

        $providers = array();
        while ($row = mysqli_fetch_assoc($result_providers)) {
            $providers[] = array('eik' => $row['provider_eik'], 'name' => $row['provider']);
        }

        echo "<form method='post'>";
        echo "<label for='provider'>Изберете доставчик:</label>";
        echo "<select name='provider' id='provider'>";
        foreach ($providers as $p) {
            echo "<option value='" . $p['eik'] . "'>" . $p['name'] . "</option>";
        }
        echo "</select><br><br>";
        echo "<input type='submit' value='Покажи доставките'>";
        echo "</form>";

        if ($provider != '') {
            $sql = "SELECT p.name, d.delivery_price, d.number_of_products, pr.provider
                    FROM Product p
                    INNER JOIN Deliveries d ON p.product_id = d.product_id
                    INNER JOIN Provider pr ON d.provider_eik = pr.eik
                    WHERE pr.eik = '$provider'";
            $result = mysqli_query($dbConn, $sql);

            if (!$result) {
                die('Грешка: ' . mysqli_error($dbConn));
            }

            if (mysqli_num_rows($result) == 0) {
                echo "<h2>Не са намерени доставки от доставчик '" . $providers[array_search($provider, array_column($providers, 'eik'))]['name'] . "'</h2>";
            } else {
                echo "<h2>Доставки от " . $providers[array_search($provider, array_column($providers, 'eik'))]['name'] . ":</h2>";
                echo "<table>";
                echo "<tr><th>Име на продукта</th><th>Цена на доставката</th><th>Брой продукти</th><th>Име на доставчика</th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>" . $row['name'] . "</td><td>" . $row['delivery_price'] . "</td><td>" . $row['number_of_products'] . "</td><td>" . $row['provider'] . "</td></tr>";
                }
                echo "</table>";
            }
        }

        mysqli_close($dbConn);
        ?>
        <p><a href="shop_index.php">>>Към началната страница</a></p>
    </div>
</body>
</html>
