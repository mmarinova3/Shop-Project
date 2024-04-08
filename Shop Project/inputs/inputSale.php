<!DOCTYPE html>
<html>
<head>
   <title>Въвеждане на Продажби</title>
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
      <label>Продукт:</label>
      <select name="product[]" required>
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
      <label>Клиент:</label>
      <select name="customer[]" required>
         <?php
         include "config.php";
         $sql = "SELECT * FROM Customer";
         $result = mysqli_query($dbConn, $sql);

         if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
               echo "<option value='" . $row['customer_id'] . "'>" . $row['name'] . "</option>";
            }
         } else {
            echo "<option value=''>Няма налични клиенти</option>";
         }
         mysqli_close($dbConn);
         ?>
      </select>
      <label>Служител:</label>
      <select name="employee[]" required>
         <?php
         include "config.php";
         $sql = "SELECT * FROM Employee";
         $result = mysqli_query($dbConn, $sql);

         if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
               echo "<option value='" . $row['employee_id'] . "'>" . $row['name'] . "</option>";
            }
         } else {
            echo "<option value=''>Няма налични служители</option>";
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
      $customers = $_POST["customer"];
      $employees = $_POST["employee"];

      if (empty($products) || empty($customers) || empty($employees)) {
         echo "Моля, изберете продукт, клиент и служител.";
         exit;
      }

      $date = date("Y-m-d");

      $sql = "SELECT sale_id FROM Sale_Customer WHERE customer_id = '$customers[0]' AND sale_date = '$date' AND employee_id = '$employees[0]'";
      $result = mysqli_query($dbConn, $sql);

      if (!$result) {
         die('Грешка при проверка на продажба. ' . mysqli_error($dbConn));
      }

      if (mysqli_num_rows($result) > 0) {
         $row = mysqli_fetch_assoc($result);
         $saleId = $row['sale_id'];
      } else {
         $sql = "INSERT INTO Sale_Customer (customer_id, sale_date, employee_id) VALUES ('$customers[0]', '$date', '$employees[0]')";
         $result = mysqli_query($dbConn, $sql);

         if (!$result) {
            die('Грешка при добавяне на продажба. ' . mysqli_error($dbConn));
         }

         $saleId = mysqli_insert_id($dbConn);
      }

      foreach ($products as $product) {
         $product_price_query = "SELECT unit_price FROM Product WHERE product_id = '$product'";
         $product_price_result = mysqli_query($dbConn, $product_price_query);
         $product_price_row = mysqli_fetch_assoc($product_price_result);
         $price = $product_price_row["unit_price"];

         $sql = "INSERT INTO Sale (sale_id, product_id, customer_id, employee_id, date, price) VALUES ('$saleId', '$product', '$customers[0]', '$employees[0]', '$date', '$price')";
         $result = mysqli_query($dbConn, $sql);

         if (!$result) {
            die('Грешка при добавяне на продукт. ' . mysqli_error($dbConn));
         }
      }
      echo "Добавихте записи.";
   }

   $sql = "SELECT Sale.*, 
      Product.name AS product_name, 
      Customer.name AS customer_name, 
      Employee.name AS employee_name,
      Sale.price
      FROM Sale 
      JOIN Product ON Sale.product_id = Product.product_id
      JOIN Sale_Customer ON Sale.sale_id = Sale_Customer.sale_id
      JOIN Customer ON Sale_Customer.customer_id = Customer.customer_id
      JOIN Employee ON Sale.employee_id = Employee.employee_id;";

   $result = mysqli_query($dbConn, $sql);

   if (mysqli_num_rows($result) > 0) {
      echo "<table>";
      echo "<thead><tr>";
      echo "<th>ID</th>";
      echo "<th>Продукт</th>";
      echo "<th>Клиент</th>";
      echo "<th>Служител</th>";
      echo "<th>Дата</th>";
      echo "<th>Цена</th>";
      echo "</tr></thead>";

      while ($row = mysqli_fetch_assoc($result)) {
         echo "<tr>";
         echo "<td>" . $row["sale_id"] . "</td>";
         echo "<td>" . $row["product_name"] . "</td>";
         echo "<td>" . $row["customer_name"] . "</td>";
         echo "<td>" . $row["employee_name"] . "</td>";
         echo "<td>" . $row["date"] . "</td>";
         echo "<td>" . $row["price"] . "</td>";
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
