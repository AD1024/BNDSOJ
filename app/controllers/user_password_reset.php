<?php
	requirePHPLib('form');
	requirePHPLib('judger');
	
	if ($myUser == null || !isSuperUser($myUser)) {
		become403Page();
	}

	// become404page();

	if($_POST['submit'] === 'true'){
		$password = $_POST['password'];
		$username = $_POST['username'];
		if(validatePassword($password) and validateUsername($username)){
			$password = getPasswordToStore($password, $username);
			DB::update("update user_info set password = '$password' where username = '$username'");
			echo "0";
			exit();
		}else{
			if(!validatePassword($password)){
				echo "1";
			}

			if(!validateUsername($username)){
				echo "2";
			}
		}
		exit();
	}

	$cur_tab = 'user-password-reset';
	
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

?>
<?php
	$REQUIRE_LIB['md5'] = '';
	$REQUIRE_LIB['dialog'] = '';
?>
<?php echoUOJPageHeader('系统管理') ?>
<div class="row">
	<div class="col-sm-3">
		<?= HTML::tablist($tabs_info, $cur_tab, 'nav-pills nav-stacked') ?>
	</div>
	
	<div class="col-sm-9">
		<div align="center"><h1>用户密码重置</h1></div>
		<div align="center">
			<form id="form-reset" class="form-horizontal" action="" method="POST">
				<div class="form-group">
					<label for="username" class="col-sm-2 control-label">用户名</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="username" name="username" placeholder="username">
					</div>
				</div>
				<div class="form-group">
					<label for="password" class="col-sm-2 control-label">新密码</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="password" name="password" placeholder="Password">
					</div>
				</div>
			</form>
			<div class="col-sm-offset-2 col-sm-10">
				<button id="submit-form" class="btn btn-success">重置</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#submit-form').click(function() {
			$.post('/super-manage/user-password-reset', {
				_token : "<?= crsf_token() ?>",
				submit: true, 
				username: $('#username').val(), 
				password: md5($('#password').val(), "<?= getPasswordClientSalt() ?>")
			}, function(msg) {
				if(msg === '0') {
					alert('重置成功!');
					window.location.reload();
				}else if(msg === '1') {
					alert('密码格式错误');
				}else if(msg === '2') {
					alert('用户名格式错误');
				}
			});
		})
	})
</script>

<?php echoUOJPageFooter() ?>
