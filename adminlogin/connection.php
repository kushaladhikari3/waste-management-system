<?php
$db = new mysqli('localhost:4306', 'root', 'rootroot', 'wms');
if (!$db) {
    die('Please check Your database connection' . mysqli_error($db));
}

?>