<html>
<head>
     <title>Въвеждане на Доставки</title>
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

      select {
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
         width: 80%;
         margin: 0 auto;
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
<div class="container">
  <form method="POST">
    <label for="product">Продукт:</label>
    <select id="product" name="product[]">
      <?php
        include "config.php";
        $sql = "SELECT * FROM Product";
        $result = mysqli_query($dbConn, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['product_id'] . "'>" . $row['name'] . "</option>";
          }
        } else {
          echo "<option value=''>Няма налични продукти</option>";
        }
        mysqli_close($dbConn);
      ?>
    </select>
    <label for="delivery_price">Доставна цена:</label>
    <input type="number" id="delivery_price" name="delivery_price[]">
    <label for="number">Брой:</label>
    <input type="number" id="number" name="number[]">
    <label for="provider">Доставчик:</label>
    <select id="provider" name="provider[]">
      <?php
        include "config.php";
        $sql = "SELECT * FROM Provider";
        $result = mysqli_query($dbConn, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['EIK'] . "'>" . $row['provider'] . "</option>";
          }
        } else {
          echo "<option value=''>Няма налични доставчици</option>";
        }
        mysqli_close($dbConn);
      ?>
    </select>
   
    <input type="submit" name="submit" value="Въведи">
  </form>

  <?php
    include "config.php";

    if (isset($_POST["submit"])) { 
      $products = $_POST["product"];
      $delivery_prices = $_POST["delivery_price"];
      $number_products = $_POST["number"];
      $providers = $_POST["provider"];

      for ($i = 0; $i < count($products); $i++) {
        $product = mysqli_real_escape_string($dbConn, $products[$i]);
        $delivery_price = mysqli_real_escape_string($dbConn, $delivery_prices[$i]);
        $provider = mysqli_real_escape_string($dbConn, $providers[$i]);
        $quantity_check = "SELECT quantity FROM Product WHERE product_id = '$product'";
        $quantity_result = mysqli_query($dbConn, $quantity_check);
        $row = mysqli_fetch_assoc($quantity_result);
        $available_quantity = $row["quantity"];

        $product_type_query = "SELECT Product.type_id, Type.name FROM Product JOIN Type ON Product.type_id = Type.group_id WHERE Product.product_id = '$product'";
        $product_type_result = mysqli_query($dbConn, $product_type_query);
        $product_type_row = mysqli_fetch_assoc($product_type_result);
        $product_type = $product_type_row["name"];

      //  if ($available_quantity >= $number_products[$i]) {
          $sql = "INSERT INTO Deliveries(product_id, product_type, delivery_price, number_of_products, provider_eik) VALUES ('$product','$product_type','$delivery_price','$number_products[$i]','$provider')";
          $result = mysqli_query($dbConn, $sql);

          if (!$result) {
            die('Грешка!!!');
          }
       // } else {
       //   echo "Грешка: Наличното количество от продукт $product е по-малко от желаното количество за доставка.";
       //  exit();
       // }
      }

      echo "Добавихте записи.";
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
      echo "<th>Номер</th>";
      echo "<th>Доставчик</th>";
      echo "</tr></thead>";

      $rows = $result->fetch_all(MYSQLI_ASSOC);
      $last_row = end($rows);
      foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>" . $row["delivery_id"] . "</td>";
        echo "<td>" . $row["product_id"] . "</td>";
        echo "<td>" . $row["product_type"] . "</td>";
        echo "<td>" . $row["delivery_price"] . "</td>";
        echo "<td>" . $row["number_of_products"] . "</td>";
        echo "<td>" . $row["provider_eik"] . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "Няма данни.";
    }
    mysqli_close($dbConn);
  ?>

  <p><a href="shop_index.php">>>Към начална старница</a></p>
   </div>
</body>
</html>
