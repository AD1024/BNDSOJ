<?php
	requirePHPLib('form');
	requirePHPLib('judger');
	echoUOJPageHeader();
	if($myUser == null){
		redirectToLogin();
	}
	if(!isSuperUser(Auth::user())){
		become403Page();
	}
	if(!validateUsername($_GET['username'])){
		become404Page();
	}
	if(isset($_POST['AlterGroupAction'])){
		$req = $_REQUEST['after_change'];
		$req = preg_replace("#/#", "", $req);
		mysql_query("update user_info set user_category='".$req."' where username='".$_GET['username']."';");
		// echo "update user_info set user_category='".$req."' where username='".$_GET['username']."';";
		echo '<script>alert("Action Successfully Commited");</script>';
	}
?>
<h1 class="page-header" align="center"><?=UOJLocale::get('user_alternation')?></h1>
<div class="row">
	<div class="col-sm-9">
		<div>
			<form action="" method="post" name="user_group_alter_text" role="form">
				<div class="input-group">
  					<span class="input-group-addon"><?=UOJLocale::get('usergroup')?></span>
  					<input type="text" class="form-control" name="after_change" placeholder="change_usergroup">
				</div>
				</br>
				<input type="hidden" name="AlterGroupAction" value="AlterGroupAction">
				<div align="center"><button class="btn btn-danger" type="submit">确认</button></div>
			</form>
		</div>
	</div>
</div>
