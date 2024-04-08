<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Въвеждане на Доставчик</title>
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
      margin-bottom: 5px;
    }

    input[type="text"],
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
  <div class="container">
    <form method="POST">
      <label for="name">Доставчик:</label>
      <input type="text" id="name" name="name[]" required>
      <label for="eik">ЕИК:</label>
      <input type="number" id="eik" name="eik[]" required>
      <input type="submit" name="submit" value="Въведи">
    </form>

    <?php
    if (isset($_POST["submit"])) { 
        include "config.php";
        
        $providers = $_POST["name"];
        $eiks = $_POST["eik"];

        for ($i = 0; $i < count($providers); $i++) {
            $name = mysqli_real_escape_string($dbConn, $providers[$i]);
            $eik = mysqli_real_escape_string($dbConn, $eiks[$i]);

            $sql = "INSERT INTO Provider (provider, EIK) VALUES ('$name','$eik')";
            $result = mysqli_query($dbConn, $sql);
            
            if (!$result) {
                die('Грешка!!!');
            }
        }
        
        echo "<p>Добавихте записи.</p>";
    }

    include "config.php";
    $sql = "SELECT * FROM Provider";
    $result = mysqli_query($dbConn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>";
        echo "<thead><tr>";
        echo "<th>Име</th>";
        echo "<th>ЕИК</th>";
        echo "</tr></thead>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["provider"] . "</td>";
            echo "<td>" . $row["EIK"] . "</td>";
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
