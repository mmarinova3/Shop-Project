<!DOCTYPE html>
<html>
<head>
   <title>Редактиране на Клиенти</title>
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
         max-width: 600px;
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
      $customerId = $_POST["customer_id"];
      $columnName = $_POST["columnName"];
      $columnValue = $_POST["columnValue"];

      $sql = "UPDATE Customer SET $columnName = '$columnValue' WHERE customer_id = $customerId";
      if (mysqli_query($dbConn, $sql)) {
         echo "Записът беше успешно редактиран.";
      } else {
         echo "Грешка при редактиране на записа: " . mysqli_error($dbConn);
      }
   }

   // Handle record deletion
   if (isset($_GET["customerId"])) {
      $customerId = $_GET["customerId"];

      $sql = "DELETE FROM Customer WHERE customer_id = $customerId";
      if (mysqli_query($dbConn, $sql)) {
         echo "Записът беше успешно изтрит.";
      } else {
         echo "Грешка при изтриване на записа: " . mysqli_error($dbConn);
      }
   }

   $sql = "SELECT * FROM Customer ";
   $result = mysqli_query($dbConn, $sql);

   if (mysqli_num_rows($result) > 0) {
      echo "<table>";
      echo "<thead><tr>";
      echo "<th>Номер</th>";
      echo "<th>Име</th>";
      echo "<th>Телефон</th>";
      echo "<th>Опции</th>";
      echo "</tr></thead>";

      while ($row = mysqli_fetch_assoc($result)) {
         echo "<tr>";
         echo "<td>" . $row["customer_id"] . "</td>";
         echo "<td>" . $row["name"] . "</td>";
         echo "<td>" . $row["phone"] . "</td>";
         echo "<td class='buttons'>";
         echo "<button onclick='showEditForm(" . $row["customer_id"] . ", \"" . $row["name"] . "\", \"" . $row["phone"] . "\")'>Редактирай</button>";
         echo "<button onclick='deleteRecord(" . $row["customer_id"] . ")'>Изтрий</button>";
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";

      // Edit form
      echo "<div id='editForm' style='display: none;'>";
      echo "<h2>Редактиране на запис</h2>";
      echo "<form method='post' action=''>";
      echo "<input type='hidden' name='customer_id' id='customerId'>";
      echo "<label for='columnName'>Колона:</label>";
      echo "<select name='columnName' id='columnName'>";
      echo "<option value='name'>Име</option>";
      echo "<option value='phone'>Телефон</option>";
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
      function showEditForm(customerId, name, phone) {
         document.getElementById('customerId').value = customerId;
         document.getElementById('columnName').selectedIndex = -1;
         document.getElementById('columnValue').value = '';

         var selectElement = document.getElementById('columnName');
         for (var i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].value === 'name' && selectElement.options[i].innerHTML === name) {
               selectElement.selectedIndex = i;
               break;
            }
            if (selectElement.options[i].value === 'phone' && selectElement.options[i].innerHTML === phone) {
               selectElement.selectedIndex = i;
               break;
            }
         }

         document.getElementById('editForm').style.display = 'block';
      }

      function hideEditForm() {
         document.getElementById('editForm').style.display = 'none';
      }

      function deleteRecord(customerId) {
         if (confirm("Сигурни ли сте, че искате да изтриете записа?")) {
            window.location.href = "updateCustomer.php?customerId=" + customerId;
         }
      }
   </script>
   <p><a href="shop_index.php">>>Към началната страница</a></p>
</body>
</html>
