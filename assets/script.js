function load_calendar_contents(month, year){
	
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.abort();
	xmlhttp.open("POST", "../assets/calendar_content.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xmlhttp.onreadystatechange=function() {

		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("calendar_content").innerHTML = xmlhttp.responseText;
		}
	}

	xmlhttp.send("month=" + month + "&year=" + year);

}

function select_day_box(id){
	document.getElementById(id).style.backgroundColor = "#d3e2e8";
}

function deselect_day_box(id){
	document.getElementById(id).style.backgroundColor = "#EFF0F4";
}