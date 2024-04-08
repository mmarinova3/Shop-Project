<!DOCTYPE html>
<html>
<head>
   <title>Въвеждане на Служители</title>
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
      <label>Име:</label>
      <input type="text" name="name[]">
      <label>Позиция:</label>
      <select name="position[]">
         <option value="">Изберете позиция</option>
         <?php
            include "config.php";
            $sql = "SELECT * FROM Position";
            $result = mysqli_query($dbConn, $sql);

            if (mysqli_num_rows($result) > 0) {
               while ($row = mysqli_fetch_assoc($result)) {
                  echo "<option value='" . $row['position_id'] . "'>" . $row['name'] . "</option>";
               }
            } else {
               echo "<option value=''>Няма налични позиции</option>";
            }
            mysqli_close($dbConn);
         ?>
      </select>
      <label>Телефон:</label>
      <input type="text" name="mobile[]">
      <input type="submit" name="submit" value="Въведи">
   </form>
  <div class="container">
   <?php
      if(isset($_POST["submit"])) { 
         include "config.php";
         
         $names = $_POST["name"];
         $positions = $_POST["position"];
         $mobiles = $_POST["mobile"];
         
         
         for ($i = 0; $i < count($names); $i++) {
            $name = mysqli_real_escape_string($dbConn, $names[$i]);
            $position = mysqli_real_escape_string($dbConn, $positions[$i]);
            $mobile = mysqli_real_escape_string($dbConn, $mobiles[$i]);
            $sql = "INSERT INTO Employee (name, position_id, phone) VALUES ('$name', '$position', '$mobile')";
            $result = mysqli_query($dbConn, $sql);
            
            if (!$result) {
               die('Грешка!!!');
            }
         }
         
         echo "Добавихте записи.";
      }

      include "config.php";
      $sql = "SELECT Employee.*, Position.name AS position_id FROM Employee JOIN Position ON Employee.position_id = Position.position_id";
      $result = mysqli_query($dbConn, $sql);

      if (mysqli_num_rows($result) > 0) {
         echo "<table>";
         echo "<thead><tr>";
         echo "<th>Име</th>";
         echo "<th>Позиция</th>";
         echo "<th>Телефон</th>";
         echo "</tr></thead>";

         while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["position_id"] . "</td>";
            echo "<td>" . $row["phone"] . "</td>";
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
