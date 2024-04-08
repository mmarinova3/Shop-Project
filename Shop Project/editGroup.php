<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $groupId = $_POST["groupId"];
    $columnName = $_POST["columnName"];
    $columnValue = $_POST["columnValue"];

    $sql = "UPDATE Type SET $columnName = '$columnValue' WHERE group_id = '$groupId'";
    if (mysqli_query($dbConn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($dbConn);
    }
}

mysqli_close($dbConn);
?>