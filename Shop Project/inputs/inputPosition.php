<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Въвеждане на Позиция</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
       background-color: darkgray;
    }

    form {
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
  <div class="container">
    <form method="POST">
      <label>Име на позицията:</label>
      <input type="text" name="name[]" required>
      <input type="submit" name="submit" value="Въведи">
    </form>

    


  <?php
    if (isset($_POST["submit"])) { 
        include "config.php";
        
        $positions = $_POST["name"];
        
        foreach ($positions as $position) {
            $name = mysqli_real_escape_string($dbConn, $position);
            $sql = "INSERT INTO Position (name) VALUES ('$name')";
            $result = mysqli_query($dbConn, $sql);
            
            if (!$result) {
                die('Грешка!!!');
            }
        }
        
        echo "<p>Добавихте записи.</p>";
    }

    include "config.php";
    $sql = "SELECT * FROM Position";
    $result = mysqli_query($dbConn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<thead><tr>";
        echo "<th>Номер</th>";
        echo "<th>Име</th>";
        echo "</tr></thead>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["position_id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Няма данни.</p>";
    }
    mysqli_close($dbConn);
  ?>
  <p><a href="shop_index.php">>> Към началната страница</a></p>
  </div>
</body>
</html>
