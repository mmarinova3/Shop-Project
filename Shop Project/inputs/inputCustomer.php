<!DOCTYPE html>
<html>
<head>
   <title>Въвеждане на Клиенти</title>
   <style>
      body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
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
    }

    input[type="text"] {
      padding: 5px;
      width: 200px;
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
      <input type="text" name="name[]" required><br>
      <label>Телефон:</label>
      <input type="text" name="phone[]" required><br>
      <input type="submit" name="submit" value="Въведи">
   </form>
<div class="container">
   <?php
      if(isset($_POST["submit"])) { 
         include "config.php";
         
         $names = $_POST["name"];
         $phones = $_POST["phone"];
         
         for ($i = 0; $i < count($names); $i++) {
            $name = mysqli_real_escape_string($dbConn, $names[$i]);
            $phone = mysqli_real_escape_string($dbConn, $phones[$i]);
            $sql = "INSERT INTO Customer (name, phone) VALUES ('$name', '$phone')";
            $result = mysqli_query($dbConn, $sql);
            
            if (!$result) {
               die('Грешка!!!');
            }
         }
         
         echo "Добавихте записи.";
      }

      include "config.php";
      $sql = "SELECT * FROM Customer";
      $result = mysqli_query($dbConn, $sql);

      if (mysqli_num_rows($result) > 0) {
         echo "<table>";
         echo "<thead><tr>";
         echo "<th>Име</th>";
         echo "<th>Телефон</th>";
         echo "</tr></thead>";

         while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
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
