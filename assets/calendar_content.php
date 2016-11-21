<?php

require("Calendar.php");

$db = mysql_connect("127.0.0.1", "root", "wysiwyg") or die ("ss");
mysql_select_db("test", $db);

$myquery = mysql_query("SELECT * FROM test1 ORDER BY date_start");
while($myrow = mysql_fetch_assoc($myquery)){
	$events[] = $myrow;
}

$cal = new Calendar($_POST["month"], $_POST["year"]);

$cal -> displayCalendar("1500px", "950px", $events);

?>