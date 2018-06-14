<?php
	if($myUser == null || !isSuperUser($myUser)){
		Become404Page();
	}
	
	if($_GET['title'] !== "" and $_GET['content'] !== ""){
		$title = htmlspecialchars(DB::escape($_GET['title']));
		$content = htmlspecialchars(DB::escape($_GET['content']));
		$duetime = date("Y-m-d h:i:s", time() + 86400);
		DB::query("insert into homework_index(title, msg, due) values('".$title."', '".$content."', '".$duetime."');");
		echo 'OK';
	}else{
		echo "Error!";
	}
?>
