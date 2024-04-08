<!DOCTYPE html>
<html>
<head>
    <title>Редактиране на Продукти</title>
    <style>
        #columnValueContainer {
            display: block;
        }

        #groupContainer {
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
    $productId = $_POST["productId"];
    $columnName = $_POST["columnName"];
    $columnValue = $_POST["columnValue"];

    if ($columnName === 'type_id') {
        $columnValue = $_POST["groupValue"]; 
    }

    $sql = "UPDATE Product SET $columnName = '$columnValue' WHERE product_id = $productId";
    if (mysqli_query($dbConn, $sql)) {
        echo "Записът беше успешно редактиран.";
    } else {
        echo "Грешка при редактиране на записа: " . mysqli_error($dbConn);
    }
}

if (isset($_GET["productId"])) {
    $productId = $_GET["productId"];

    $sql = "DELETE FROM Product WHERE product_id = $productId";
    if (mysqli_query($dbConn, $sql)) {
        echo "Записът беше успешно изтрит.";
    } else {
        echo "Грешка при изтриване на записа: " . mysqli_error($dbConn);
    }
}

$sql = "SELECT Product.*, Type.name AS type_name FROM Product JOIN Type ON Product.type_id = Type.group_id";
$result = mysqli_query($dbConn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>";
    echo "<thead><tr>";
    echo "<th>Наименование</th>";
    echo "<th>Група</th>";
    echo "<th>Ед. цена</th>";
    echo "<th>Брой</th>";
    echo "<th>Действия</th>";
    echo "</tr></thead>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["type_name"] . "</td>";
        echo "<td>" . $row["unit_price"] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "<td>";
        echo "<button class='button' onclick='showEditForm(" . $row["product_id"] . ", \"" . $row["name"] . "\", \"" . $row["type_id"] . "\", \"" . $row["unit_price"] . "\", \"" . $row["quantity"] . "\")'>Редактирай</button>";
        echo "<button class='button' onclick='deleteRecord(" . $row["product_id"] . ")'>Изтрий</button>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<div id='editForm' style='display:none;'>";
    echo "<h2>Редактиране на запис</h2>";
    echo "<form method='post' action=''>";
    echo "<input type='hidden' name='productId' id='productId'>";
    echo "<label for='columnName'>Колона:</label>";
    echo "<select name='columnName' id='columnName' onchange='toggleColumn(this.value)'>";
    echo "<option value='name'>Име</option>";
    echo "<option value='type_id'>Група</option>";
    echo "<option value='unit_price'>Ед. цена</option>";
    echo "<option value='quantity'>Брой</option>";
    echo "</select>";
    echo "<br>";

    echo "<div id='columnValueContainer'>";
    echo "<label for='columnValue'>Стойност:</label><br>";
    echo "<input type='text' name='columnValue' id='columnValue'>";
    echo "</div>";

    $sql = "SELECT * FROM Type";
    $result = mysqli_query($dbConn, $sql);

    echo "<div id='groupContainer'>";
    echo "<label for='groupValue'>Група:</label><br>";
    echo "<select name='groupValue' id='groupValue'>";
    echo "<option value=''>Изберете група</option>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['group_id'] . "'>" . $row['name'] . "</option>";
        }
    } else {
        echo "<option value=''>Няма налични групи</option>";
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
        function showEditForm(productId, name, typeId, unitPrice, quantity) {
            document.getElementById('productId').value = productId;
            document.getElementById('columnName').value = 'name';
            document.getElementById('columnValue').value = name;
            document.getElementById('groupValue').value = typeId;
            document.getElementById('groupContainer').style.display = 'none';
            document.getElementById('columnValueContainer').style.display = 'block';
            document.getElementById('editForm').style.display = 'block';
        }

        function deleteRecord(productId) {
            if (confirm('Сигурни ли сте, че искате да изтриете записа?')) {
                window.location.href = 'updateProduct.php?productId=' + productId;
            }
        }

        function toggleColumn(columnName) {
            if (columnName === 'type_id') {
                document.getElementById('groupContainer').style.display = 'block';
                document.getElementById('columnValueContainer').style.display = 'none';
            } else {
                document.getElementById('groupContainer').style.display = 'none';
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

<a href="shop_index.php">>>Kъм началната страница</a>

</body>
</html>