<!DOCTYPE html>
<html>
<head>
   <title>Редактиране на Групи</title>
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
         display: auto;
         justify-content: center;
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

      .buttons button:first-child {
         margin-right: 10px;
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $groupId = $_POST["groupId"];
    $columnName = $_POST["columnName"];
    $columnValue = $_POST["columnValue"];

    $sql = "UPDATE Type SET $columnName = '$columnValue' WHERE group_id = $groupId";
    if (mysqli_query($dbConn, $sql)) {
        echo "Записът беше успешно редактиран.";
    } else {
        echo "Грешка при редактиране на записа: " . mysqli_error($dbConn);
    }
}

// Handle record deletion
if (isset($_GET["groupId"])) {
    $groupId = $_GET["groupId"];

    $sql = "DELETE FROM Type WHERE group_id = $groupId";
    if (mysqli_query($dbConn, $sql)) {
        echo "Записът беше успешно изтрит.";
    } else {
        echo "Грешка при изтриване на записа: " . mysqli_error($dbConn);
    }
}

$sql = "SELECT * FROM Type ";
$result = mysqli_query($dbConn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>Номер</th>";
    echo "<th>Група</th>";
    echo "<th>Действия</th>";
    echo "</tr></thead>";
    echo "<tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["group_id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td class='buttons'>";
        echo "<button onclick='showEditForm(\"" . $row["group_id"] . "\", \"" . $row["name"] . "\")'>Редактирай</button>";
        echo "<button onclick='deleteRecord(\"" . $row["group_id"] . "\")'>Изтрий</button>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

    echo "<div id='editForm' style='display:none;'>";
    echo "<h2>Редактиране на запис</h2>";
    echo "<form method='post' action=''>";
    echo "<input type='hidden' name='groupId' id='groupId'>";
    echo "<label for='columnName'>Колона:</label>";
    echo "<select name='columnName' id='columnName'>";
    echo "<option value='name'>Група</option>";
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
    function showEditForm(groupId, groupName) {
        document.getElementById('groupId').value = groupId;
        document.getElementById('columnValue').value = groupName;
        document.getElementById('editForm').style.display = 'block';
    }

    function hideEditForm() {
        document.getElementById('editForm').style.display = 'none';
    }

    function deleteRecord(groupId) {
        if (confirm("Сигурни ли сте, че искате да изтриете записа?")) {
            window.location.href = "updateGroup.php?groupId=" + groupId;
        }
    }
</script>
<p><a href="shop_index.php">>>Към началната страница</a></p>
</body>
</html>
