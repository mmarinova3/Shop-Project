<!DOCTYPE html>
<html>
<head>
    <title>Редактиране на Продажби</title>
    <style>
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
        $saleId = $_POST["saleId"];
        $columnName = $_POST["columnName"];
        $columnValue = $_POST["columnValue"];

        if ($columnName === 'product_id') {
        $columnValue = $_POST["productValue"];
    } elseif ($columnName === 'customer_id') {
        $columnValue = $_POST["customerValue"];
    } elseif ($columnName === 'employee_id') {
        $columnValue = $_POST["employeeValue"];
    }


        $sql = "UPDATE Sale SET $columnName = '$columnValue' WHERE saled_product_id = $saleId";
        if (mysqli_query($dbConn, $sql)) {
            echo "Записът беше успешно редактиран.";
        } else {
            echo "Грешка при редактиране на записа: " . mysqli_error($dbConn);
        }
    }

    if (isset($_GET["saleId"])) {
        $saleId = $_GET["saleId"];

        $sql = "DELETE FROM Sale WHERE saled_product_id = $saleId";
        if (mysqli_query($dbConn, $sql)) {
            echo "Записът беше успешно изтрит.";
        } else {
            echo "Грешка при изтриване на записа: " . mysqli_error($dbConn);
        }
    }

    $sql = "SELECT Sale.*, Product.name AS product_name, Customer.name AS customer_name, Employee.name AS employee_name FROM Sale
            JOIN Product ON Sale.product_id = Product.product_id
            JOIN Customer ON Sale.customer_id = Customer.customer_id
            JOIN Employee ON Sale.employee_id = Employee.employee_id";

    $result = mysqli_query($dbConn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>";
        echo "<thead><tr>";
        echo "<th>Номер</th>";
        echo "<th>Продукт</th>";
        echo "<th>Клиент</th>";
        echo "<th>Служител</th>";
        echo "<th>Дата</th>";
        echo "<th>Цена</th>";
        echo "<th>Действия</th>";
        echo "</tr></thead>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["sale_id"] . "</td>";
            echo "<td>" . $row["product_name"] . "</td>";
            echo "<td>" . $row["customer_name"] . "</td>";
            echo "<td>" . $row["employee_name"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo "<td>" . $row["price"] . "</td>";
            echo "<td>";
            echo "<button class='button' onclick='showEditForm(" . $row["saled_product_id"] . ", \"" . $row["sale_id"] . "\", \"" . $row["product_id"] . "\", \"" . $row["customer_id"] . "\", \"" . $row["employee_id"] . "\", \"" . $row["date"] . "\", \"" . $row["price"] . "\")'>Редактирай</button>";
            echo "<button class='button' onclick='deleteRecord(" . $row["saled_product_id"] . ")'>Изтрий</button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<div id='editForm' style='display:none;'>";
        echo "<h2>Редактиране на запис</h2>";
        echo "<form method='post' action=''>";
        echo "<input type='hidden' id='saledProductId' name='saleId'>";
        echo "<label for='editColumnName'>Име на колона:</label>";
         echo "<select name='columnName' id='columnName' onchange='toggleColumn(this.value)'>";
        echo "<option value='product_id'>Продукт</option>";
        echo "<option value='customer_id'>Клиент</option>";
        echo "<option value='employee_id'>Служител</option>";
        echo "<option value='date'>Дата</option>";
        echo "<option value='price'>Цена</option>";
        echo "</select>";

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

    $sql = "SELECT * FROM Customer";
    $result = mysqli_query($dbConn, $sql);

    echo "<div id='customerContainer'>";
    echo "<label for='customerValue'>Клиент:</label><br>";
    echo "<select name='customerValue' id='customerValue'>";
    echo "<option value=''>Изберете клиент</option>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['customer_id'] . "'>" . $row['name'] . "</option>";
        }
    } else {
        echo "<option value=''>Няма налични клиенти</option>";
    }

    echo "</select>";
    echo "</div>";


    $sql = "SELECT * FROM Employee";
    $result = mysqli_query($dbConn, $sql);

    echo "<div id='employeeContainer'>";
    echo "<label for='employeeValue'>Служител:</label><br>";
    echo "<select name='employeeValue' id='employeeValue'>";
    echo "<option value=''>Изберете служител</option>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['employee_id'] . "'>" . $row['name'] . "</option>";
        }
    } else {
        echo "<option value=''>Няма налични служители</option>";
    }

    echo "</select>";
    echo "</div>";

    echo "<div class='form-buttons'>";
    echo "<input type='submit' value='Запази'>";
    echo "<button type='button' onclick='cancelEdit()'>Откажи</button>";
    echo "</div>";
    echo "</form>";
    echo "</div>";
    } else {
        echo "Няма налични записи.";
    }

    mysqli_close($dbConn);
    ?>

    <script>
        function showEditForm(saledProductId, saleId, productId, customerId, employeeId, date, price) {
             document.getElementById('saledProductId').value = saledProductId;
            document.getElementById('columnName').value = 'price';
            document.getElementById('columnValue').value = price;
            document.getElementById('productValue').value = productId;
            document.getElementById('productContainer').style.display = 'none';
            document.getElementById('customerValue').value = customerId;
            document.getElementById('customerContainer').style.display = 'none';
            document.getElementById('employeeValue').value = employeeId;
            document.getElementById('employeeContainer').style.display = 'none';
            document.getElementById('columnValueContainer').style.display = 'block';
            document.getElementById('editForm').style.display = 'block';
        }

  function deleteRecord(saledProductId) {
    if (confirm("Сигурни ли сте, че искате да изтриете записа?")) {
        window.location.href = "updateSale.php?saleId=" + saledProductId;
    }
}

        function toggleColumn(columnName) {
    if (columnName === 'product_id') {
        document.getElementById('productContainer').style.display = 'block';
        document.getElementById('customerContainer').style.display = 'none';
        document.getElementById('employeeContainer').style.display = 'none';
        document.getElementById('columnValueContainer').style.display = 'none';
    } else if (columnName === 'customer_id') {
        document.getElementById('customerContainer').style.display = 'block';
        document.getElementById('productContainer').style.display = 'none';
        document.getElementById('employeeContainer').style.display = 'none';
        document.getElementById('columnValueContainer').style.display = 'none';
    } else if (columnName === 'employee_id') {
        document.getElementById('employeeContainer').style.display = 'block';
        document.getElementById('customerContainer').style.display = 'none';
        document.getElementById('productContainer').style.display = 'none';
        document.getElementById('columnValueContainer').style.display = 'none';
    } else {
        document.getElementById('employeeContainer').style.display = 'none';
        document.getElementById('customerContainer').style.display = 'none';
        document.getElementById('productContainer').style.display = 'none';
        document.getElementById('columnValueContainer').style.display = 'block';
    }
}


        function cancelEdit() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>

    <p><a href="shop_index.php">>>Към началната страница</a></p>
</body>
</html>

