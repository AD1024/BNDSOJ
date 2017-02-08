<?php
	// Usage:
	// Method: POST
	/*
		op
			param1 | param2 ... | param_x
	*/
	//0 : Add UserGroup
		// Param: group -> group id
		
	//1 : Remove Single UserGroup
		// Param: group -> group id
		
	//2	: Add user(s) to usergroup
		// Param: group -> group id | users -> username(s)
		
	//3 : Remove several users from usergroup
		// Param: group -> group id | users -> username(s)
	// NOTE: If you want to pass several parameters for `users` or `group`, please use `,` to split each elements.
	if(!isSuperUser(Auth::user())){
		Become404Page();
	}
	if(!isset($_GET['op'])){
		echo 'Missing Operation Code';
		exit();
	}
	if($_GET['op'] != 1 and $_GET['op'] != 2 and $_GET['op'] != 0 and $_GET['op'] != 3 and $_GET['op'] != 4){
		echo 'Error op code';
		die();
	}
	if($_GET['op'] == 0 or $_GET['op'] == 1){
		if(!isset($_GET['group']) or !$_GET['group']){
			echo 'Missing Parameter';
			exit();
		}
	}else if($_GET['op'] == 2){
		if(!isset($_GET['users']) or !isset($_GET['group']) or !$_GET['group'] or !$_GET['users']){
			echo 'Missing Param';
			exit();
		}
	}else if($_GET['op'] == 3){
		if(!isset($_GET['users']) or !isset($_GET['group']) or !$_GET['group'] or !$_GET['users']){
			echo 'Missing Param to remove user';
			die();
		}
	}
	$op = $_GET['op'];
	$group = $_GET['group'];
	switch($op){
		case 0:{
			$op = DB::escape($op);
			$group = DB::escape($group);
			$group = htmlspecialchars($group);
			$f = DB::selectCount("select count(*) from user_group where group_name='".$group."';");
			if($f == 0){
				DB::query("insert into user_group(group_name) values('".$group."');");
				echo 'OK';
			}else{
				echo 'ERROR: '.$group.'has been added into database';
			}
			break;
		}
		case 1:{
			$group = DB::escape($group);
			if(!validateUInt($group)){
				echo 'Invalid Group ID';
				die();
			}
			$f = DB::selectCount("select count(*) from user_group where id=".$group.";");
			if($f != 0){
				DB::query("delete from user_group where id=".$group.";");
				DB::query("delete from user_group_map where group_id=".$group.";");
				echo 'OK';
			}else{
				echo 'ERROR:group'.$group.' does not exists';
			}
			break;
		}
		case 2:{
			$users = explode(",",$_GET['users']);
			$group = DB::escape($group);
			if(!validateUInt($group)){
				echo "Invalid Group ID";
				die();
			}
			foreach($users as $x){
				if(!validateUsername($x) or !queryUser($x)){
					continue;
				}
				$flag = DB::selectCount("select count(*) from user_group_map where username='".$x."' and group_id='".$group."';");
				if($flag != 0){
					continue;
				}else{
					DB::query("insert into user_group_map(username,group_id) values('".$x."',".$group.");");
				}
			}
			echo 'OK';
			break;
		}
		case 3:{
			$users = explode(",",$_GET['users']);
			$group = DB::escape($group);
			foreach($users as $x){
				if(!validateUsername($x) or !queryUser($x)){
					continue;
				}
				$flag = DB::selectCount("select count(*) from user_group_map where username='".$x."' and group_id='".$group."';");
				if(!$flag){
					continue;
				}else{
					DB::query("delete from user_group_map where username='".$x."' and group_id='".$group."';");
				}
			}
			echo 'OK';
			break;
		}
		case 4:{
			$groups = explode(",",$_GET['group']);
			foreach($groups as $x){
				if(!validateUInt($x)){
					continue;
				}
				$x = DB::escape($x);
				DB::query("delete from user_group where id=".$x.";");
				DB::query("delete from user_group_map where group_id='".$x."';");
			}
			echo 'OK';
			break;
		}
	}
?>
