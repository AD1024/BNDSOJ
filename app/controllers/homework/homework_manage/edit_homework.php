<?php
	if($myUser == null || !isSuperUser($myUser)){
		Become404Page();
	}
	
	if(!validateUInt($_GET['op'])){
		Become403Page();
	}
	
	function addgrouphomework($homework_id, $group_id){
		$sql = mysql_query("select * from user_group_map where group_id = ".$group_id.";");
		while($info = mysql_fetch_array($sql)){
			if(DB::selectCount("select count(*) from user_homework where owner = '".$info['username']."' and homework_id = ".$homework_id.";") == 0){
				mysql_query("insert into user_homework(homework_id, owner) values(".$homework_id.", '".$info['username']."');");
			}
		}
	}
	
	if($_GET['op'] === '1'){
		if(validateUInt($_GET['id']) and $_GET['title'] !== "" and $_GET['content'] !== "" and $_GET['duetime'] !== ""){
			$title = htmlspecialchars(DB::escape($_GET['title']));
			$content = htmlspecialchars(DB::escape($_GET['content']));
			$duetime = htmlspecialchars(DB::escape($_GET['duetime']));
			DB::query("update homework_index set title = '".$title."', msg = '".$content."', due = '".$duetime."' where id = ".$_GET['id'].";");
			echo 'OK';
		}else{
			echo "Error!";
		}
	}else if($_GET['op'] === '2'){
		if(validateUInt($_GET['id']) and validateUInt($_GET['problem_id'])){
			$sql = mysql_query("select * from homework_index where id = ".$_GET['id'].";");
			$info = mysql_fetch_array($sql);
			$problems = explode(",",$info['problem_id']);
			$update_data = "";
			$flag = false;
			foreach($problems as $x){
				if(validateUInt($x)){
					if($_GET['problem_id'] < $x and !$flag){
						$flag = true;
						$update_data = $update_data.$_GET['problem_id'].",";
					}else if($_GET['problem_id'] == $x){
						$flag = true;
					}
					$update_data = $update_data.$x.",";
				}
			}
			if(!$flag){
				$update_data = $update_data.$_GET['problem_id'].",";
			}
			DB::query("update homework_index set problem_id = '".$update_data."' where id = ".$_GET['id'].";");
			echo 'OK';
		}else{
			echo "Error!";
		}
	}else if($_GET['op'] === '3'){
		if(validateUInt($_GET['id']) and validateUInt($_GET['problem_id'])){
			$sql = mysql_query("select * from homework_index where id = ".$_GET['id'].";");
			$info = mysql_fetch_array($sql);
			$problems = explode(",",$info['problem_id']);
			$update_data = "";
			foreach($problems as $x){
				if(validateUInt($x)){
					if($_GET['problem_id'] == $x){
						continue;
					}
					$update_data = $update_data.$x.",";
				}
			}
			DB::query("update homework_index set problem_id = '".$update_data."' where id = ".$_GET['id'].";");
			echo 'OK';
		}else{
			echo "Error!";
		}
	}else if($_GET['op'] === '4'){
		if(validateUInt($_GET['id']) and validateUInt($_GET['group_id'])){
			mysql_query("delete from user_homework where homework_id = ".$_GET['id'].";");
			$sql = mysql_query("select * from homework_index where id = ".$_GET['id'].";");
			$info = mysql_fetch_array($sql);
			$problems = explode(",",$info['belong']);
			$update_data = "";
			$flag = false;
			addgrouphomework($_GET['id'], $_GET['group_id']);
			foreach($problems as $x){
				if(validateUInt($x)){
					if($_GET['group_id'] < $x and !$flag){
						$flag = true;
						$update_data = $update_data.$_GET['group_id'].",";
					}else if($_GET['group_id'] == $x){
						$flag = true;
					}
					$update_data = $update_data.$x.",";
					addgrouphomework($_GET['id'], $x);
				}
			}
			if(!$flag){
				$update_data = $update_data.$_GET['group_id'].",";
			}
			DB::query("update homework_index set belong = '".$update_data."' where id = ".$_GET['id'].";");
			echo 'OK';
		}else{
			echo "Error!";
		}
	}else if($_GET['op'] === '5'){
		if(validateUInt($_GET['id']) and validateUInt($_GET['group_id'])){
			mysql_query("delete from user_homework where homework_id = ".$_GET['id'].";");
			$sql = mysql_query("select * from homework_index where id = ".$_GET['id'].";");
			$info = mysql_fetch_array($sql);
			$problems = explode(",",$info['belong']);
			$update_data = "";
			foreach($problems as $x){
				if(validateUInt($x)){
					if($_GET['group_id'] == $x){
						continue;
					}
					$update_data = $update_data.$x.",";
					addgrouphomework($_GET['id'], $x);
				}
			}
			DB::query("update homework_index set belong = '".$update_data."' where id = ".$_GET['id'].";");
			echo 'OK';
		}else{
			echo "Error!";
		}
	}else{
		echo "Error!Wrong operation!".$_GET['op'];
	}
?>
