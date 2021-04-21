<?php

$host = "localhost";

$root = "root";
$root_password = "nagarro";

$user = 'root';
$pass = 'newpass';
$db = "newdb";
$table = "tasks";

try {
    $dbh = new PDO("mysql:host=$host", $root, $root_password);

    $dbh->exec("CREATE DATABASE IF NOT EXISTS `$db`;
            CREATE USER '$user'@'localhost' IDENTIFIED BY '$pass';
            GRANT ALL ON `$db`.* TO '$user'@'localhost';
            FLUSH PRIVILEGES;")
    or die(print_r($dbh->errorInfo(), true));

    $dbh->query("use $db");

    $sql = "CREATE TABLE IF NOT EXISTS $table (
    	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    	job LONGTEXT,
    	attempt TINYINT(1) UNSIGNED,
    	created_at TIMESTAMP
	)";

	$dbh->exec($sql);

	$queryTasks = "SELECT * FROM $table WHERE attempt = 0 ORDER BY created_at ASC";

	$tasks = $dbh->query($queryTasks);

	$sql = '';

	if (!empty($tasks)) {
	    foreach ($tasks as $task) {
	    	$sql .= "UPDATE tasks SET attempt = 1 WHERE id = " . $task['id'] . ";";
	    }
    	$dbh->exec($sql);
	}
}
catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}



