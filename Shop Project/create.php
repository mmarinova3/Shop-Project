
<a href="shop_index.php">>>Към началната старница</h2><br>
    
<?php
$host = 'localhost';
$dbUser = 'root';
$dbPass = '';

$dbConn = mysqli_connect($host, $dbUser, $dbPass);
if (!$dbConn) {
    die('Не може да се осъществи връзка със сървъра: ' . mysqli_connect_error());
}

$sql = 'CREATE DATABASE IF NOT EXISTS shop_db';
if (mysqli_query($dbConn, $sql)) {
    echo "Базата данни е създадена. <br>";
} else {
    echo "Грешка при създаване на базата данни: " . mysqli_error($dbConn);
}

mysqli_select_db($dbConn, 'shop_db');

$sql = "CREATE TABLE IF NOT EXISTS `Type` (
   group_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL
)";
$result = mysqli_query($dbConn, $sql);
if(!$result)
    die('Грешка при създаване на таблицата. ' . mysqli_error($dbConn));
echo "Таблицата 'Група' е създадена!<br>"; 

$sql = "CREATE TABLE IF NOT EXISTS Product (
  product_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL,
  type_id INT(6) UNSIGNED NOT NULL,
  unit_price DECIMAL(4,2) NOT NULL,
  quantity INT(15) NOT NULL,
  FOREIGN KEY (type_id) REFERENCES Type(group_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8";
$result = mysqli_query($dbConn,$sql);
if(!$result)
die('Грешка при създаване на таблицата. ' . mysqli_error($dbConn));
echo "Таблицата 'Продукт' е създадена!<br>";

$sql = "CREATE TABLE IF NOT EXISTS `Position` (
    position_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL
)";
$result = mysqli_query($dbConn, $sql);
if(!$result)
    die('Грешка при създаване на таблицата. ' . mysqli_error($dbConn));
echo "Таблицата 'Позиция' е създадена!<br>"; 


$sql = "CREATE TABLE IF NOT EXISTS Employee (
    employee_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    position_id INT(6) UNSIGNED NOT NULL,
    phone VARCHAR(15) NOT NULL,
    FOREIGN KEY (position_id) REFERENCES `Position`(position_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8";
$result = mysqli_query($dbConn, $sql);
if(!$result)
    die('Грешка при създаване на таблицата. ' . mysqli_error($dbConn));
echo "Таблицата 'Служител' е създадена!<br>";


$sql = "CREATE TABLE IF NOT EXISTS Customer (
    customer_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    phone VARCHAR(15) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8";
$result = mysqli_query($dbConn, $sql);
if (!$result)
    die('Грешка при създаване на таблицата. ' . mysqli_error($dbConn));
echo "Таблицата 'Клиент' е създадена!<br>";

$sql = "CREATE TABLE IF NOT EXISTS Sale_Customer (
    sale_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id INT(6) UNSIGNED NOT NULL,
    sale_date DATE NOT NULL,
    employee_id INT(6) UNSIGNED NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES Employee(employee_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8";

$result = mysqli_query($dbConn, $sql);
if (!$result) {
    die('Грешка при създаване на таблицата. ' . mysqli_error($dbConn));
}
echo "Таблицата 'Продажба_Клиент' е създадена!<br>";

$sql = "CREATE TABLE IF NOT EXISTS Sale (
    saled_product_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sale_id INT(6) UNSIGNED NOT NULL,
    product_id INT(6) UNSIGNED NOT NULL,
    customer_id INT(6) UNSIGNED NOT NULL,
    employee_id INT(6) UNSIGNED NOT NULL,
    date DATE NOT NULL,
    price DECIMAL(4,2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES Sale_Customer(sale_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES Employee(employee_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8";

$result = mysqli_query($dbConn, $sql);
if (!$result) {
    die('Грешка при създаване на таблицата. ' . mysqli_error($dbConn));
}
echo "Таблицата 'Продажба' е създадена!<br>";


$sql = "CREATE TABLE IF NOT EXISTS Provider (
            provider VARCHAR(30) NOT NULL,
            EIK INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY
        ) ENGINE=INNODB DEFAULT CHARSET=utf8";
$result = mysqli_query($dbConn, $sql);
if (!$result)
    die('Грешка при създаване на таблицата. ' . mysqli_error($dbConn));
echo "Таблицата 'Доставчик' е създадена!<br>"; 


$sql = "CREATE TABLE IF NOT EXISTS Deliveries (
    delivery_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT(6) UNSIGNED NOT NULL,
    product_type INT(6) NOT NULL,
    delivery_price DECIMAL(4,2) NOT NULL,
    number_of_products INT(6)  NOT NULL,
    provider_eik INT(10) UNSIGNED NOT NULL,       
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (provider_eik) REFERENCES Provider(EIK)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8";
$result = mysqli_query($dbConn,$sql);
if(!$result)
    die('Грешка при създаване на таблицата. ' . mysqli_error($dbConn));
echo "Таблицата 'Доставки' е създадена!<br>";  


mysqli_close($dbConn);
?>

