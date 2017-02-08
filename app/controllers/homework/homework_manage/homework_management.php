<?php
	requirePHPLib('form');
	requirePHPLib('judger');
	echoUOJPageHeader();
	if(!isSuperUser(Auth::user())){
		become403Page();
		exit();
	}
?>
<h1 class="page-header" align="center"><?=UOJLocale::get('homework_management')?></h1>
<ul class="nav nav-tabs" role="tablist">
	<li><a href="/homework/add" role="tab"><?=UOJLocale::get('add_new_homework')?></a></li>
	<li><a href="/homework/user_manage" role="tab"><?=UOJLocale::get('user_group_manage')?></a></li>
</ul>

<?php echoUOJPageFooter();?>
