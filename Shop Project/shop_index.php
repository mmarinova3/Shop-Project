<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Магазин</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
    }
    
    .header {
      background-color: #333;
      color: #fff;
      padding: 20px;
      text-align: center;
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      margin-top: 30px;
    }

    .container section {
      margin: 20px;
    }

    .container h2 {
      text-align: center;
    }

    .container a {
      display: block;
      margin-top: 10px;
      padding: 12px 20px;
      background-color: lightgray;
      color: #333;
      text-decoration: none;
      border-radius: 4px;
    }

    .container a:hover {
      background-color: #ddd;
    }

    .description {
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <a href="create.php">
    <div class="header">
      <h1>Магазин</h1>
       <p class="description">(натиснете тук, за да създадете база данни)</p>
    </div>
  </a>

  <div class="container">
    <section>
      <h2>Въвеждане/Разглеждане</h2>
      <a href="inputProduct.php">Продукт</a>
      <a href="inputEmployee.php">Служител</a>
      <a href="inputCustomer.php">Клиент</a>
      <a href="inputSale.php">Продажба</a>
      <a href="inputProvider.php">Доставчик</a>
      <a href="inputDelivery.php">Доставки</a>
      <a href="inputGroup.php">Група</a>
      <a href="inputPosition.php">Позиция</a>
    </section>

    <section>
      <h2>Редактиране/Изтриване</h2>
      <a href="updateProduct.php">Продукт</a>
      <a href="updateEmployee.php">Служител</a>
      <a href="updateCustomer.php">Клиент</a>
      <a href="updateSale.php">Продажба</a>
      <a href="updateProvider.php">Доставчик</a>
      <a href="updateDeliveries.php">Доставки</a>
      <a href="updateGroup.php">Група</a>
      <a href="updatePosition.php">Позиция</a>
    </section>

    <section>
      <h2>Търсене</h2>
      <a href="searchProducts.php">Търсене на продукти</a>
    </section>

    <section>
      <h2>Справки</h2>
      <a href="reference1.php">Продажби за период</a>
      <a href="reference2.php">Продажби за служител (подредени по дата)</a>
      <a href="reference3.php">Продажби за клиент</a>
      <a href="reference4.php">Топ 5 класация по продажба на продукти</a>
      <a href="reference5.php">Информация за доставени продукти на определена дата</a>
      <a href="reference6.php">Информация за доставки от определен доставчик</a>
    </section>
  </div>


</body>
</html>
