<!DOCTYPE html>
<html>
<head>
   <title>Въвеждане на Продукти</title>
   <style>
      body {
         font-family: Arial, sans-serif;
         margin: 20px;
         background-color: darkgray;
      }

      form {
         display: flex;
         flex-direction: column;
         align-items: center;
         margin-bottom: 20px;
      }

      label {
         font-weight: bold;
         margin-bottom: 5px;
      }

      input[type="text"],
      select,
      input[type="number"] {
         padding: 5px;
         width: 200px;
         margin-bottom: 10px;
      }

      input[type="submit"] {
         padding: 8px 12px;
         background-color: black;
         color: #fff;
         border: none;
         cursor: pointer;
      }
       .container {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

     table {
      border-collapse: collapse;
      width: 100%;
      max-width: 600px;
      margin-top: 20px;
    }

    table th,
    table td {
      border: 2px solid black;
      padding: 8px;
    }

    table th {
      background-color: #f2f2f2;
      font-weight: bold;
      text-align: left;
    }

    table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

      a {
      color: black;
      text-decoration: none;
      font-weight: bold;
    }
   </style>
</head>
<body>
   <form method="POST">
      <label>Наименование:</label>
      <input type="text" name="name[]">
      <label>Група:</label>
      <select name="group[]">
         <option value="">Изберете група</option>
         <?php
            include "config.php";
            $sql = "SELECT * FROM Type";
            $result = mysqli_query($dbConn, $sql);

            if (mysqli_num_rows($result) > 0) {
               while ($row = mysqli_fetch_assoc($result)) {
                  echo "<option value='" . $row['group_id'] . "'>" . $row['name'] . "</option>";
               }
            } else {
               echo "<option value=''>Няма налични групи</option>";
            }
            mysqli_close($dbConn);
         ?>
      </select>
      <label>Ед. цена:</label>
      <input type="text" name="price[]">
      <label>Брой:</label>
      <input type="number" name="quantity[]">
      <input type="submit" name="submit" value="Въведи">
   </form>
  <div class="container">
   <?php
      if(isset($_POST["submit"])) { 
         include "config.php";
         
         $names = $_POST["name"];
         $groups = $_POST["group"];
         $prices = $_POST["price"];
         $quantities = $_POST["quantity"];
         
         for ($i = 0; $i < count($names); $i++) {
            $name = mysqli_real_escape_string($dbConn, $names[$i]);
            $group = mysqli_real_escape_string($dbConn, $groups[$i]);
            $price = mysqli_real_escape_string($dbConn, $prices[$i]);
            $quantity = mysqli_real_escape_string($dbConn, $quantities[$i]);
            $sql = "INSERT INTO Product (name, type_id, unit_price, quantity) VALUES ('$name', '$group', '$price', '$quantity')";
            $result = mysqli_query($dbConn, $sql);
            
            if (!$result) {
               die('Грешка!!!');
            }
         }
         
         echo "Добавихте записи.";
      }

      include "config.php";
      $sql = "SELECT Product.*, Type.name AS type_id FROM Product JOIN Type ON Product.type_id = Type.group_id";
      $result = mysqli_query($dbConn, $sql);

      if (mysqli_num_rows($result) > 0) {
         echo "<table>";
         echo "<thead><tr>";
         echo "<th>Наименование</th>";
         echo "<th>Група</th>";
         echo "<th>Ед. цена</th>";
         echo "<th>Брой</th>";
         echo "</tr></thead>";

         while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["type_id"] . "</td>";
            echo "<td>" . $row["unit_price"] . "</td>";
            echo "<td>" . $row["quantity"] . "</td>";
            echo "</tr>";
         }
         echo "</table>";
      } else {
         echo "Няма данни.";
      }
      mysqli_close($dbConn);
   ?>
    <p><a href="shop_index.php">>> Към началната страница</a></p>
</div>
</body>
</html>
