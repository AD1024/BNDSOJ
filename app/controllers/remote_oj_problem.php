<?php
/*
	Created by dhxh
*/

	requirePHPLib('form');
	requirePHPLib('judger');

	if ($myUser == null) {
		redirectToLogin();
	}


	include "remote_oj/main.php";

	if($_GET['ojname']){
		$oj_name = $_GET['ojname'];
	}else{
		$oj_name = "poj";
	}
	
	
	if($_GET['problemid']){
		$oj_problem_id = $_GET['problemid'];
	}else{
		become404Page();
	}

	$oj_action = "show";
	
	$oj_problem = getojinfo($oj_name, $oj_action, $oj_problem_id, $oj_page_id, $oj_code);

	if (!$oj_problem["isfound"]) {
		become404Page();
	}

	if($_POST['submit'] === "true"){
		if($_POST['answer_answer_upload_type'] !== "editor"){
			echo '<script>alert("本题不支持文件上传提交！");</script>';
		}else{
			$oj_code['language'] = $_POST['answer_answer_language'];
			$oj_code['code'] = $_POST['answer_answer_editor'];
			$oj_code['problem_id'] = $oj_problem['id'];

			//$oj_action = "check";
			//$oj_check = getojinfo($oj_name, $oj_action, $oj_problem_id, $oj_page_id, $oj_code);

			$oj_code['code'] = base64_encode($oj_code['code']);
			
			if($oj_name === 'poj' or $oj_name === 'hdu'){
				$now_time = date("Y-m-d H:i:s", time());
				mysql_query("insert into remote_oj_submissions (oj_name, submitter, language, code, result, status, submit_time, problem_name, problem_id) values ('".$oj_name."', '${myUser['username']}', '".$oj_code['language']."', '".$oj_code['code']."', 'Waiting', 'w', '".$now_time."', '".$oj_problem['title']."', ".$oj_problem['id']." )");
				header('Location: /remoteoj/submissions');
			}else{
				echo '<script>alert("提交失败！");</script>';
			}
		}
		
	}


	
?>
<?php
	$REQUIRE_LIB['mathjax'] = '';
	$REQUIRE_LIB['shjs'] = '';
?>
<?php echoUOJPageHeader(HTML::stripTags($oj_problem['title']) . ' - ' . UOJLocale::get('problems::problem')) ?>

<h1 class="page-header text-center">#<?= $oj_names[$oj_name].$oj_problem['id']?>. <?= $oj_problem['title'] ?></h1>

<ul class="nav nav-tabs" role="tablist">
	<li class="active"><a href="#tab-statement" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-book"></span> <?= UOJLocale::get('problems::statement') ?></a></li>
	<li><a href="#tab-submit-answer" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-upload"></span> <?= UOJLocale::get('problems::submit') ?></a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tab-statement">
		<article class="top-buffer-md">
			<h3>问题描述</h3>
			<p><?php echo $oj_problem["description"]?></p>
			<h3>输入</h3>
			<p><?php echo $oj_problem["input"]; ?></p>
			<h3>输出</h3>
			<p><?php echo $oj_problem["output"]; ?></p>
			<h3>样例输入</h3>
			<pre><?php echo $oj_problem["sample_in"]; ?></pre>
			<h3>样例输出</h3>
			<pre><?php echo $oj_problem["sample_out"]; ?></pre>
			<h3>Source</h3>
			<p><?php echo $oj_problem["source"]; ?></p>
		</article>
	</div>
	<div class="tab-pane" id="tab-submit-answer">
		<div class="top-buffer-sm"></div>
		<form action="" method="post" class="form-horizontal" id="form-answer" enctype="multipart/form-data">
			<input type="hidden" name="submit" value="true">
			<div class="form-group" id="form-group-answer_answer"></div>
			<script type="text/javascript">
				$('#form-group-answer_answer').source_code_form_group('answer_answer', '源文件:answer', "<option selected=\"selected\">C++<\/option><option>Java<\/option><option>C<\/option><option>Pascal<\/option>");
			</script>
			<div class="text-center">
				<button type="submit" id="button-submit-answer" name="submit-answer" value="answer" class="btn btn-default">提交</button>
			</div>
		</form>
	</div>
</div>
<?php echoUOJPageFooter() ?>
