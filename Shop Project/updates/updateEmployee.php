<!DOCTYPE html>
<html>
<head>
    <title>Редактиране на Служители</title>
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
    $employeeId = $_POST["employeeId"];
    $columnName = $_POST["columnName"];
    $columnValue = $_POST["columnValue"];

    if ($columnName === 'position_id') {
        $columnValue = $_POST["groupValue"];
    }

    $sql = "UPDATE Employee SET $columnName = '$columnValue' WHERE employee_id = $employeeId";
    if (mysqli_query($dbConn, $sql)) {
        echo "Записът беше успешно редактиран.";
    } else {
        echo "Грешка при редактиране на записа: " . mysqli_error($dbConn);
    }
}

if (isset($_GET["employeeId"])) {
    $employeeId = $_GET["employeeId"];

    $sql = "DELETE FROM Employee WHERE employee_id = $employeeId";
    if (mysqli_query($dbConn, $sql)) {
        echo "Записът беше успешно изтрит.";
    } else {
        echo "Грешка при изтриване на записа: " . mysqli_error($dbConn);
    }
}

$sql = "SELECT Employee.*, Position.name AS position_name FROM Employee JOIN Position ON Employee.position_id = Position.position_id";
$result = mysqli_query($dbConn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>";
    echo "<thead><tr>";
    echo "<th>Номер</th>";
    echo "<th>Име</th>";
    echo "<th>Позиция</th>";
    echo "<th>Телефон</th>";
    echo "<th>Действия</th>";
    echo "</tr></thead>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["employee_id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["position_name"] . "</td>";
        echo "<td>" . $row["phone"] . "</td>";
        echo "<td>";
        echo "<button class='button' onclick='showEditForm(" . $row["employee_id"] . ", \"" . $row["name"] . "\", \"" . $row["position_id"] . "\", \"" . $row["phone"] . "\")'>Редактирай</button>";
        echo "<button class='button' onclick='deleteRecord(" . $row["employee_id"] . ")'>Изтрий</button>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<div id='editForm' style='display:none;'>";
    echo "<h2>Редактиране на запис</h2>";
    echo "<form method='post' action=''>";
    echo "<input type='hidden' name='employeeId' id='employeeId'>";
    echo "<label for='columnName'>Колона:</label>";
    echo "<select name='columnName' id='columnName' onchange='toggleColumn(this.value)'>";
    echo "<option value='name'>Име</option>";
    echo "<option value='position_id'>Позиция</option>";
    echo "<option value='phone'>Телефон</option>";
    echo "</select>";
    echo "<br>";

    echo "<div id='columnValueContainer'>";
    echo "<label for='columnValue'>Стойност:</label><br>";
    echo "<input type='text' name='columnValue' id='columnValue'>";
    echo "</div>";

    $sql = "SELECT * FROM Position";
    $result = mysqli_query($dbConn, $sql);

    echo "<div id='groupContainer'>";
    echo "<label for='groupValue'>Позиция:</label><br>";
    echo "<select name='groupValue' id='groupValue'>";
    echo "<option value=''>Изберете позиция</option>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['position_id'] . "'>" . $row['name'] . "</option>";
        }
    } else {
        echo "<option value=''>Няма налични позиции</option>";
    }

    echo "</select>";
    echo "</div>";

    echo "<div class='form-buttons'>";
    echo "<input type='submit' value='Запази'>";
    echo "<button type='button' onclick='cancelEdit()'>Откажи</button>";
    echo "</div>";
    echo "</form>";
    echo "</div>";

    echo "<script>
        function showEditForm(employeeId, name, positionId, phone) {
            document.getElementById('employeeId').value = employeeId;
            document.getElementById('columnName').value = 'name';
            document.getElementById('columnValue').value = name;
            document.getElementById('groupValue').value = positionId;
            document.getElementById('groupContainer').style.display = 'none';
            document.getElementById('columnValueContainer').style.display = 'block';
            document.getElementById('editForm').style.display = 'block';
        }

        function deleteRecord(employeeId) {
            if (confirm('Сигурни ли сте, че искате да изтриете записа?')) {
                window.location.href = 'updateEmployee.php?employeeId=' + employeeId;
            }
        }

        function toggleColumn(columnName) {
            if (columnName === 'position_id') {
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