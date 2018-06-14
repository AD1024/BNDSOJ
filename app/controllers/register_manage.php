<?php
	requirePHPLib('form');
	requirePHPLib('judger');
	
	if ($myUser == null || !isSuperUser($myUser)) {
		become403Page();
	}

	$cur_tab = 'register';
	
	$tabs_info = array(
		'users' => array(
			'name' => '用户操作',
			'url' => "/super-manage/users"
		),
		'blogs' => array(
			'name' => '博客管理',
			'url' => "/super-manage/blogs"
		),
		'submissions' => array(
			'name' => '提交记录',
			'url' => "/super-manage/submissions"
		),
		'custom-test' => array(
			'name' => '自定义测试',
			'url' => '/super-manage/custom-test'
		),
		'click-zan' => array(
			'name' => '点赞管理',
			'url' => '/super-manage/click-zan'
		),
		'search' => array(
			'name' => '搜索管理',
			'url' => '/super-manage/search'
		),
		'user-group' => array(
			'name' => '用户组管理',
			'url' => '/super-manage/user-group'
		),
		'tmp-user' => array(
			'name' => '临时用户管理',
			'url' => '/super-manage/tmp-user'
		),
		'user-password-reset' => array(
			'name' => '用户密码重置',
			'url' => '/super-manage/user-password-reset'
		),
		'register' => array(
			'name' => '注册开关',
			'url' => '/super-manage/register'
		)
	);

	if($_GET['submit'] == 'true' and validateUInt($_GET['op'])){
		if(intval($_GET['op']) === 0){
			mysql_query("update register_status set `status` = 0;");
		}else{
			mysql_query("update register_status set `status` = 1;");
		}
	}

	$sql = mysql_query("select * from register_status;");
	$info = mysql_fetch_array($sql);
	$rstatus = $info['status'];

?>
<?php echoUOJPageHeader('系统管理') ?>
<div class="row">
	<div class="col-sm-3">
		<?= HTML::tablist($tabs_info, $cur_tab, 'nav-pills nav-stacked') ?>
	</div>
	
	<div class="col-sm-9">
		<div align="center"><h1>注册开关</h1></div>
		<div align="center">
			<?php
				if(intval($rstatus) !== 0){
			?>
			<p class="text-success">当前状态：注册已开启</p>
			<a type="button" href="?submit=true&op=0" class="btn btn-primary btn-lg">关闭注册</a>
			<?php
				}else{
			?>
			<p class="text-danger">当前状态：注册已关闭</p>
			<a type="button" href="?submit=true&op=1" class="btn btn-primary btn-lg">开启注册</a>
			<?php
				}
			?>
		</div>
	</div>
</div>


<?php echoUOJPageFooter() ?>
