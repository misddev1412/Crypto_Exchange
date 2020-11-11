<?php

if($_POST) {
	$point = $_POST['point'];
	$token = 0;
	for($i = 1; $i <= $_POST['day']; $i++) {
		$token = $point * 0.002;
		$point = $point - $token;
	}
	
	echo $point;
	echo $token;
}



<html>
	<body>
		<form action="/" method="post">
			<input type="text" name="point" value="0" />
			<input type="number" name="day" value="1" />
			
			<button type="submit"> Cacula </button>
		</form>
	</body>
</html>