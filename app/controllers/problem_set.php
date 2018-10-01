<?php
	requirePHPLib('form');
	requirePHPLib('judger');
	requirePHPLib('svn');
	if ($myUser == null) {
		redirectToLogin();
	}

	if (isSuperUser($myUser)) {
		$new_problem_form = new UOJForm('new_problem');
		global $ran_id;
		$ran_id = -1;
		$new_problem_form->handle = function() {
			mysql_query("insert into problems (title, is_hidden, submission_requirement) values ('New Problem', 1, '{}')");
			$id = mysql_insert_id();
			global $ran_id;
			$ran_id = $id;
			mysql_query("insert into problems_contents (id, statement, statement_md) values ($id, '', '')");

			// AD1024 Start
			mysql_query("update problems set hackable = 0 where id = {$id}");
			$__config = array('view_details_type'=>'ALL','view_all_details_type'=>'ALL','view_content_type'=>'SELF');
			$__esc_conf = DB::escape(json_encode($__config));
			mysql_query("update problems set extra_config = '$__esc_conf' where id = {$id}");
			// AD1024 End

			svnNewProblem($id);
			// AD1024 Begin
			
			// AD1024 End
		};
		$new_problem_form->submit_button_config['align'] = 'right';
		$new_problem_form->submit_button_config['class_str'] = 'btn btn-primary';
		$new_problem_form->submit_button_config['text'] = UOJLocale::get('problems::add new');
		$new_problem_form->submit_button_config['smart_confirm'] = '';
		
		$new_problem_form->runAtServer();
	}
	
	function echoProblem($problem) {
		global $myUser;
		if (isProblemVisibleToUser($problem, $myUser)) {
			echo '<tr class="text-center">';
			if ($problem['submission_id']) {
				echo '<td class="success">';
			} else {
				echo '<td>';
			}
			echo '#', $problem['id'], '</td>';
			// AD1024 begin
			if(isSuperUser($myUser) && $problem['is_hidden']) {
				echo '<td class="text-left warning">', '<a href="/problem/', $problem['id'], '">', $problem['title'], '</a>   '.('<span class="glyphicon glyphicon-sunglasses " aria-hidden="true"></span>');
			} else {
				echo '<td class="text-left">', '<a href="/problem/', $problem['id'], '">', $problem['title'], '</a>';
			}
			// AD1024 end
			if (isset($_COOKIE['show_tags_mode'])) {
				foreach (queryProblemTags($problem['id']) as $tag) {
					echo '<a class="uoj-problem-tag">', '<span class="badge">', HTML::escape($tag), '</span>', '</a>';
				}
			}
			echo '</td>';
			if (isset($_COOKIE['show_submit_mode'])) {
				$perc = $problem['submit_num'] > 0 ? round(100 * $problem['ac_num'] / $problem['submit_num']) : 0;
				echo <<<EOD
				<td><a href="/submissions?problem_id={$problem['id']}&min_score=100&max_score=100">&times;{$problem['ac_num']}</a></td>
				<td><a href="/submissions?problem_id={$problem['id']}">&times;{$problem['submit_num']}</a></td>
				<td>
					<div class="progress bot-buffer-no">
						<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="$perc" aria-valuemin="0" aria-valuemax="100" style="width: $perc%; min-width: 20px;">{$perc}%</div>
					</div>
				</td>
EOD;
			}
			echo '<td class="text-left">', getClickZanBlock('P', $problem['id'], $problem['zan']), '</td>';
			echo '</tr>';
		}
	}
	
	$cond = array();
	
	$search_tag = null;
	$search_name = null;

	$cur_tab = isset($_GET['tab']) ? $_GET['tab'] : 'all';
	
	// AD1024 Begin
	if ($cur_tab == 'template') {
		$search_tag = "模板题";
	}else if ($cur_tab == 'basic') {
		$search_tag = "基础篇";
		$level_cur_tab = 'basic';
		$cur_tab = 'basic';
	}else if ($cur_tab == "harder") {
		$search_tag = "提高篇";
		$level_cur_tab = 'harder';
		$cur_tab = 'harder';
	}else if ($cur_tab == "enhance") {
		$search_tag = "进阶篇";
		$level_cur_tab = 'enhance';
		$cur_tab = 'enhance';
	}
	// AD1024 End
	// Skqliao begin
	if(isset($_GET['search'])) {
		$search_name = $_GET['search'];
		$search_name = str_replace("'","",$search_name);
		$search_name = str_replace("\\","",$search_name);
		$search_name = str_replace("%27","",$search_name);
		if($search_name) {
			if(validateUInt($_GET['search']) && queryProblemBrief($_GET['search'])) {
				$url= "/problem/".$_GET['search']; 
				Header("HTTP/1.1 303 See Other"); 
				Header("Location: $url"); 
				exit;
			} else {
				$cond[] = "problems.title Like'%".DB::escape($search_name)."%'";
			}
		} // Skqliao end
	} else {
		if (isset($_GET['tag'])) {
			$search_tag = $_GET['tag'];
			//dhxh begin
			$search_tag = str_replace("'","",$search_tag);
			$search_tag = str_replace("\\","",$search_tag);
			$search_tag = str_replace("%27","",$search_tag);
			//dhxh end
		}
		if ($search_tag) {
			$cond[] = "'".DB::escape($search_tag)."' in (select tag from problems_tags where problems_tags.problem_id = problems.id)";
		}
	}
	
	if ($cond) {
		$cond = join($cond, ' and ');
	} else {
		$cond = '1';
	}
	
	$header = '<tr>';
	$header .= '<th class="text-center" style="width:5em;">ID</th>';
	$header .= '<th>'.UOJLocale::get('problems::problem').'</th>';
	if (isset($_COOKIE['show_submit_mode'])) {
		$header .= '<th class="text-center" style="width:5em;">'.UOJLocale::get('problems::ac').'</th>';
		$header .= '<th class="text-center" style="width:5em;">'.UOJLocale::get('problems::submit').'</th>';
		$header .= '<th class="text-center" style="width:150px;">'.UOJLocale::get('problems::ac ratio').'</th>';
	}
	$header .= '<th class="text-center" style="width:180px;">'.UOJLocale::get('appraisal').'</th>';
	$header .= '</tr>';
	
	$tabs_info = array(
		'all' => array(
			'name' => UOJLocale::get('problems::all problems'),
			'url' => "/problems"
		),
		'template' => array(
			'name' => UOJLocale::get('problems::template problems'),
			'url' => "/problems/template"
		),
		'remoteoj' => array(
			'name' => UOJLocale::get('problems::remote oj'),
			'url' => "/remoteoj"
		)
	);
	// AD1024 Begin
	/*
	* Func: Render data for level spliting
	* */
	$level_tab_info = array(
		'basic' => array(
			'name' => UOJLocale::get('problems::level_basic'),
			'url' => "/problems/basic"
		),
		'harder' => array(
			'name' => UOJLocale::get('problems::level_promote'),
			'url' => "/problems/harder"
		),
		'enhance' => array(
			'name' => UOJLocale::get('problems::level_enhance'),
			'url' => "/problems/enhance"
		)
	);
	// AD1024 End
	
	/*
	<?php
	echoLongTable(array('*'),
		"problems left join best_ac_submissions on best_ac_submissions.submitter = '{$myUser['username']}' and problems.id = best_ac_submissions.problem_id", $cond, 'order by id asc',
		$header,
		'echoProblem',
		array('page_len' => 3,
			'table_classes' => array('table', 'table-bordered', 'table-hover', 'table-striped'),
			'print_after_table' => function() {
				global $myUser;
				if (isSuperUser($myUser)) {
					global $new_problem_form;
					$new_problem_form->printHTML();
				}
			},
			'head_pagination' => true
		)
	);
?>*/

	$pag_config = array('page_len' => 100);
	$pag_config['col_names'] = array('*');
	$pag_config['table_name'] = "problems left join best_ac_submissions on best_ac_submissions.submitter = '{$myUser['username']}' and problems.id = best_ac_submissions.problem_id";
	$pag_config['cond'] = $cond;
	$pag_config['tail'] = "order by id asc";
	$pag = new Paginator($pag_config);

	$div_classes = array('table-responsive');
	$table_classes = array('table', 'table-bordered', 'table-hover', 'table-striped');
?>
<?php echoUOJPageHeader(UOJLocale::get('problems')) ?>

<?php // Skqliao begin ?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">标签云</h4>
            </div>
            <div class="modal-body">
		    	<div class="row">
					<form class="form-inline text-center"><input type="text" name="tag" class="form-control" /><button class="btn btn-primary" type="submit">搜索</button></form>
				</div>
				<?php 
				$sql = mysql_query("SELECT DISTINCT tag FROM problems_tags ORDER BY tag ASC");
				$cur = 0;
				?>
				<div style="text-align:center">
				<h2>标签一览表</h2>
				<table width="95%" border="2" align="center">
				<tr>
					<th>名称</th><th>题目数量</th><th>跳转</th>
					<th>名称</th><th>题目数量</th><th>跳转</th>
				</tr>
				<tr>
				<?php
				while($tag = mysql_fetch_array($sql)) {
					$count = DB::selectCount("select count(*) from problems_tags WHERE tag = '".$tag['tag']."'");
					if($cur % 2 == 0) {
						echo '<tr>';
					} ?>
					<td><?=$tag['tag']?></td><td><?=$count?></td><td><a href="/problems?tag=<?=$tag['tag']?>">GO</a></td>
				<?php
					if($cur % 2 == 1) {
						echo '</tr>';
					}
					$cur = $cur + 1;
				}
				?>
				</tr>
				</table>
			</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<div class="row">
	<div class="col-sm-4">
		<?php // Skqliao begin ?>
		<button class="btn btn-primary" data-toggle="modal" data-target="#myModal">选择标签</button>
		<?php // Skqliao end ?>
	</div>
	<div class="col-sm-4 text-center">
	<?php echo $pag->pagination(); ?>
	</div>
	<div class="col-sm-4 checkbox text-right">
		<label class="checkbox-inline" for="input-show_tags_mode"><input type="checkbox" id="input-show_tags_mode" <?= isset($_COOKIE['show_tags_mode']) ? 'checked="checked" ': ''?>/> <?= UOJLocale::get('problems::show tags') ?></label>
		<label class="checkbox-inline" for="input-show_submit_mode"><input type="checkbox" id="input-show_submit_mode" <?= isset($_COOKIE['show_submit_mode']) ? 'checked="checked" ': ''?>/> <?= UOJLocale::get('problems::show statistics') ?></label>
	</div>
</div>

<div class="row">
	<div class="col-sm-4">
		<?= HTML::tablist($tabs_info, $cur_tab, 'nav-pills') ?>
	</div>
	<div class="col-sm-4">
        <form class="form-inline text-center"><input type="text" name="search" class="form-control" /><button class="btn btn-primary" type="submit">搜索</button></form>
	</div>
	<div class="col-sm-4">
	<?php
		if (isSuperUser($myUser)) {
			$new_problem_form->printHTML();
		}
	?>
	</div>
</div>

<?php // Skqliao end ?>

<div class="top-buffer-sm"></div>
<script type="text/javascript">
$('#input-show_tags_mode').click(function() {
	if (this.checked) {
		$.cookie('show_tags_mode', '', {path: '/problems'});
	} else {
		$.removeCookie('show_tags_mode', {path: '/problems'});
	}
	location.reload();
});
$('#input-show_submit_mode').click(function() {
	if (this.checked) {
		$.cookie('show_submit_mode', '', {path: '/problems'});
	} else {
		$.removeCookie('show_submit_mode', {path: '/problems'});
	}
	location.reload();
});
</script>
<?php
	echo '<div class="', join($div_classes, ' '), '">';
	echo '<table class="', join($table_classes, ' '), '">';
	echo '<thead>';
	echo $header;
	echo '</thead>';
	echo '<tbody>';

	foreach ($pag->get() as $idx => $row) {
		echoProblem($row);
	}
	
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
	
	if (isSuperUser($myUser)) {
		$new_problem_form->printHTML();
	}

	echo $pag->pagination();
?>
<?php echoUOJPageFooter() ?>