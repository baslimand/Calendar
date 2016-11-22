<?php

require("Calendar.php");

$db = new PDO("mysql:dbname=test;host=127.0.0.1", "root", "wysiwyg") or die("Fail to connect");

$events = $db->query("SELECT * FROM test1 ORDER BY `date_start`", PDO::FETCH_ASSOC);

$cal = new Calendar($_POST["month"], $_POST["year"]);

$cal -> displayCalendar("1500px", "950px", $events);

?>
