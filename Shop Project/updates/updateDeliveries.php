<!DOCTYPE html>
<html>
<head>
    <title>Редактиране на Доставки</title>
    <style>
        #columnValueContainer {
            display: block;
        }

        #productContainer,
        #providerContainer {
            display: none;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f2f2f2;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: auto;
            margin: 0 auto;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 2px solid black;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #eaeaea;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

       .buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
}

.buttons button {
    padding: 8px 20px;
    background-color: black;
    color: white;
    border: none;
    border-radius: 50px;
    cursor: pointer;
}

        .buttons button:first-child {
            margin-right: 10px;
        }

        .buttons button:hover {
            background-color: gray;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        select,
        input[type="text"] {
            padding: 5px;
            width: 200px;
            margin-bottom: 10px;
        }

        input[type="submit"],
        button[type="button"] {
            padding: 8px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            width: 120px;
        }

        .form-buttons {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .form-buttons button {
            margin: 0 5px;
        }

        .button {
            background-color: #333;
            color: white;
            border-radius: 20px;
            padding: 8px 20px;
            border: none;
            cursor: pointer;
            margin-right: 5px;
        }

        .button:hover {
            background-color: gray;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #333;
            text-decoration: none;
        }
    </style>
</head>
<body>
<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deliveryId = $_POST["deliveryId"];
    $columnName = $_POST["columnName"];
    $columnValue = $_POST["columnValue"];

    if ($columnName === 'product_id') {
        $columnValue = $_POST["productValue"];
    } elseif ($columnName === 'provider_eik') {
        $columnValue = $_POST["providerValue"];
    }

    $sql = "UPDATE Deliveries SET $columnName = '$columnValue' WHERE delivery_id = $deliveryId";
    if (mysqli_query($dbConn, $sql)) {
        echo "Записът беше успешно редактиран.";
    } else {
        echo "Грешка при редактиране на записа: " . mysqli_error($dbConn);
    }
}

if (isset($_GET["deliveryId"])) {
    $deliveryId = $_GET["deliveryId"];

    $sql = "DELETE FROM Deliveries WHERE delivery_id = $deliveryId";
    if (mysqli_query($dbConn, $sql)) {
        echo "Записът беше успешно изтрит.";
    } else {
        echo "Грешка при изтриване на записа: " . mysqli_error($dbConn);
    }
}

$sql = "SELECT Deliveries.*, 
               Product.name AS product_id,
               Type.name AS product_type,
               Provider.provider AS provider_eik
        FROM Deliveries
        JOIN Product ON Deliveries.product_id = Product.product_id
        JOIN Type ON Product.type_id = Type.group_id
        JOIN Provider ON Deliveries.provider_eik = Provider.EIK";
$result = mysqli_query($dbConn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>";
    echo "<thead><tr>";
    echo "<th>Продукт</th>";
    echo "<th>Група</th>";
    echo "<th>Доставна цена</th>";
    echo "<th>Брой</th>";
    echo "<th>Доставчик</th>";
    echo "<th>Действия</th>";
    echo "</tr></thead>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["product_id"] . "</td>";
        echo "<td>" . $row["product_type"] . "</td>";
        echo "<td>" . $row["delivery_price"] . "</td>";
        echo "<td>" . $row["number_of_products"] . "</td>";
        echo "<td>" . $row["provider_eik"] . "</td>";
        echo "<td>";
        echo "<button class='button' onclick='showEditForm(" . $row["delivery_id"] . ", \"". $row["product_id"] . "\", " . $row["delivery_price"] . ", " . $row["number_of_products"] . ", \"" . $row["provider_eik"] . "\")'>Редактирай</button>";
        echo "<button class='button' onclick='deleteRecord(" . $row["delivery_id"] . ")'>Изтрий</button>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<div id='editForm' style='display:none;'>";
    echo "<h2>Редактиране на запис</h2>";
    echo "<form method='post' action=''>";
    echo "<input type='hidden' name='deliveryId' id='deliveryId'>";
    echo "<label for='columnName'>Колона:</label>";
    echo "<select name='columnName' id='columnName' onchange='toggleColumn(this.value)'>";
    echo "<option value='product_id'>Продукт</option>";
    echo "<option value='delivery_price'>Доставна цена</option>";
    echo "<option value='number_of_products'>Брой</option>";
    echo "<option value='provider_eik'>Доставчик</option>";
    echo "</select>";
    echo "<br>";

    echo "<div id='columnValueContainer'>";
    echo "<label for='columnValue'>Стойност:</label><br>";
    echo "<input type='text' name='columnValue' id='columnValue'>";
    echo "</div>";

    $sql = "SELECT * FROM Product";
    $result = mysqli_query($dbConn, $sql);

    echo "<div id='productContainer'>";
    echo "<label for='productValue'>Продукти:</label><br>";
    echo "<select name='productValue' id='productValue'>";
    echo "<option value=''>Изберете продукт</option>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['product_id'] . "'>" . $row['name'] . "</option>";
        }
    } else {
        echo "<option value=''>Няма налични продукти</option>";
    }

    echo "</select>";
    echo "</div>";

    $sql = "SELECT * FROM Provider";
    $result = mysqli_query($dbConn, $sql);

    echo "<div id='providerContainer'>";
    echo "<label for='providerValue'>Доставчик:</label><br>";
    echo "<select name='providerValue' id='providerValue'>";
    echo "<option value=''>Изберете доставчик</option>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['EIK'] . "'>" . $row['provider'] . "</option>";
        }
    } else {
        echo "<option value=''>Няма налични доставчици</option>";
    }

    echo "</select>";
    echo "</div>";

    echo "<div class='form-buttons'>";
    echo "<input type='submit' value='Запази'>";
    echo "<button type='button' onclick='cancelEdit()'>Откажи</button>";
    echo "</div>";
    echo "</form>";
    echo "</div>";

    // JavaScript functions
    echo "<script>
        function showEditForm(deliveryId, productId, price, quantity, providerId) {
            document.getElementById('deliveryId').value = deliveryId;
            document.getElementById('columnName').value = 'delivery_price';
            document.getElementById('columnValue').value = price;
            document.getElementById('productValue').value = productId;
            document.getElementById('productContainer').style.display = 'none';
            document.getElementById('providerValue').value = providerId;
            document.getElementById('providerContainer').style.display = 'none';
            document.getElementById('columnValueContainer').style.display = 'block';
            document.getElementById('editForm').style.display = 'block';
        }

        function deleteRecord(deliveryId) {
            if (confirm('Сигурни ли сте, че искате да изтриете записа?')) {
                window.location.href = 'updateDeliveries.php?deliveryId=' + deliveryId;
            }
        }

        function toggleColumn(columnName) {
            if (columnName === 'product_id') {
                document.getElementById('productContainer').style.display = 'block';
                document.getElementById('providerContainer').style.display = 'none';
                document.getElementById('columnValueContainer').style.display = 'none';
            } else if (columnName === 'provider_eik') {
                document.getElementById('providerContainer').style.display = 'block';
                document.getElementById('productContainer').style.display = 'none';
                document.getElementById('columnValueContainer').style.display = 'none';
            } else {
                document.getElementById('providerContainer').style.display = 'none';
                document.getElementById('productContainer').style.display = 'none';
                document.getElementById('columnValueContainer').style.display = 'block';
            }
        }

        function cancelEdit() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>";
} else {
    echo "Няма налични записи.";
}

mysqli_close($dbConn);
?>

<a href="shop_index.php">>>Към началната страница</a>

</body>
</html>
