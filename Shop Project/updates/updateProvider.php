<!DOCTYPE html>
<html>
<head>
   <title>Редактиране на Доставчици</title>
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
         border: 1px solid black;
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
         display: auto;
         justify-content: center;
         gap: 5px;
         margin-top: 10px;
      }

      .buttons button {
         padding: 8px 20px;
         background-color: #333;
         color: #fff;
         border: none;
         border-radius: 20px;
         cursor: pointer;
         margin: 0 5px;
      }

      .buttons button:hover {
         background-color: #555;
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
         color: #fff;
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
    $eik = $_POST["EIK"];
    $columnName = $_POST["columnName"];
    $columnValue = $_POST["columnValue"];

    $sql = "UPDATE Provider SET $columnName = '$columnValue' WHERE EIK = '$eik'";
    if (mysqli_query($dbConn, $sql)) {
        echo "Записът беше успешно редактиран.";
    } else {
        echo "Грешка при редактиране на записа: " . mysqli_error($dbConn);
    }
}

if (isset($_GET["eik"])) {
    $eik = $_GET["eik"];

    $sql = "DELETE FROM Provider WHERE EIK = '$eik'";
    if (mysqli_query($dbConn, $sql)) {
        echo "Записът беше успешно изтрит.";
    } else {
        echo "Грешка при изтриване на записа: " . mysqli_error($dbConn);
    }
}

$sql = "SELECT * FROM Provider";
$result = mysqli_query($dbConn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>Име</th>";
    echo "<th>ЕИК</th>";
    echo "<th>Опции</th>";
    echo "</tr></thead>";
    echo "<tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["provider"] . "</td>";
        echo "<td>" . $row["EIK"] . "</td>";
        echo "<td class='buttons'>";
        echo "<button onclick='showEditForm(\"" . $row["provider"] . "\", \"" . $row["EIK"] . "\")'>Редактирай</button>";
        echo "<button onclick='deleteRecord(\"" . $row["EIK"] . "\")'>Изтрий</button>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

    echo "<div id='editForm' style='display:none;'>";
    echo "<h2>Редактиране на запис</h2>";
    echo "<form method='post' action=''>";
    echo "<input type='hidden' name='EIK' id='eik'>";
    echo "<label for='columnName'>Колона:</label>";
    echo "<select name='columnName' id='columnName'>";
    echo "<option value='provider'>Доставчик</option>";
    echo "<option value='EIK'>ЕИК</option>";
    echo "</select>";
    echo "<br>";
    echo "<label for='columnValue'>Стойност:</label>";
    echo "<input type='text' name='columnValue' id='columnValue'>";
    echo "<br>";
    echo "<div class='form-buttons'>";
    echo "<input type='submit' value='Запази'>";
    echo "<button type='button' onclick='hideEditForm()'>Откажи</button>";
    echo "</div>";
    echo "</form>";
    echo "</div>";
} else {
    echo "Няма данни.";
}
mysqli_close($dbConn);
?>

<script>
    function showEditForm(provider, eik) {
        document.getElementById('eik').value = eik;
        document.getElementById('columnName').selectedIndex = -1;
        document.getElementById('columnValue').value = '';

        var selectElement = document.getElementById('columnName');
        for (var i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].value === 'provider' && selectElement.options[i].innerHTML === provider) {
                selectElement.selectedIndex = i;
                break;
            }
            if (selectElement.options[i].value === 'EIK' && selectElement.options[i].innerHTML === eik) {
                selectElement.selectedIndex = i;
                break;
            }
        }

        document.getElementById('editForm').style.display = 'block';
    }

    function hideEditForm() {
        document.getElementById('editForm').style.display = 'none';
    }

    function deleteRecord(eik) {
        if (confirm("Сигурни ли сте, че искате да изтриете записа?")) {
            window.location.href = "updateProvider.php?eik=" + eik;
        }
    }
</script>
<p><a href="shop_index.php">>>Към началната страница</a></p>
</body>
</html>
