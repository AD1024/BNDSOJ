<?php
	requirePHPLib('form');
	requirePHPLib('judger');
	echoUOJPageHeader();
	if($myUser == null){
		redirectToLogin();
	}
	if(!isSuperUser(Auth::user())){
		become403Page();
		exit();
	}
	if(isset($_POST['GroupAlter'])){
		$user_names = array();
		$user_names = explode(",",$_POST['users']);
		$group = $_POST['group'];
		$group = preg_replace("#/#", "", $group);
		foreach($user_names as $x){
			if(!validateUsername($x) || !queryUser($x)) continue;
			mysql_query("update user_info set user_category='".$group."' where username='".$x."';");
			// echo "update user_info set user_category='".$group."' where username='".$x."';";
		}
	}
	// $users = mysql_fetch_array("select * from user_info;");
?>
<h1 class="page-header" align="center"><?=UOJLocale::get('homework_management')?></h1>
<ul class="nav nav-tabs" role="tablist">
	<li><a href="/homework/add" role="tab"><?=UOJLocale::get('add_new_homework')?></a></li>
	<li class="active"><a href="/homework/user_manage" role="tab"><?=UOJLocale::get('user_group_manage')?></a></li>
</ul>

<form action="" method="post" name="multi_alt" role="form">
	<div class="input-group">
		<span class="input-group-addon">Username</span>
		<input type="text" class="form-control" name="users" placeholder="Username">
	</div>
	</br>
	<div class="input-group">
		<span class="input-group-addon">UserGroup</span>
		<input type="text" class="form-control" name="group" placeholder="UserGroup">
	</div>
	</br>
	<input type="hidden" name="GroupAlter" value="GroupAlter"></input>
	<div align="center">
		<button type="submit" id="confirm_group_change" data-loading-text="EXM" class="btn btn-primary" autocomplete="off">
  		<?=UOJLocale::get('submit')?>
		</button>
	</div>
</form>
</br>
<form class="form-horizontal" action="" method="post" name="userform" role="form">
	<div class="table-responsive">
		<table class="table table-bordered table-hover table-striped table-text-center">
			<thead>
				<tr>
					<th>Username</th>
					<th>UserGroup</th>
					<!---
					<th><?php UOJLocale::get('username') ?></th>
					<th><?php UOJLocale::get('usergroup') ?></th>
					--->
				</tr>
			</thead>
			<tbody>
<?php
			$sql_query = DB::query('select * from user_info;');
			while($row = DB::fetch($sql_query,MYSQL_ASSOC)){
				echo '<tr>';
				echo '<td><a href="/homework/user_manage/'.$row['username'].'">'.$row['username'].'</a></td>';
				echo '<td>'.($row['user_category'] == null?UOJLocale::get('no_category'):$row['user_category']).'</td>';
			}
			echo '</tbody>';
		echo '</table>';
	echo '</div>';
echo '</form>';
?>
<?php echoUOJPageFooter();?>
