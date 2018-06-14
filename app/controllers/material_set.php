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
			mysql_query("insert into materials (title, is_hidden) values ('New Material', 1)");
			$id = mysql_insert_id();
			global $ran_id;
			$ran_id = $id;
			mysql_query("insert into materials_contents (id, statement, statement_md) values ($id, '', '')");
		};
		$new_problem_form->submit_button_config['align'] = 'right';
		$new_problem_form->submit_button_config['class_str'] = 'btn btn-primary';
		$new_problem_form->submit_button_config['text'] = '添加新资料';
		$new_problem_form->submit_button_config['smart_confirm'] = '';
		
		$new_problem_form->runAtServer();
	}
	
	function echoProblem($problem) {
		global $myUser;

		if($problem['is_hidden'] == 0 or isSuperUser($myUser)){
			echo '<tr class="text-center">';
			echo '<td>';
			echo '#', $problem['id'], '</td>';
			echo '<td class="text-left">', '<a href="/material/', $problem['id'], '">', $problem['title'], '</a>';
			echo '</td>';
			echo '</tr>';
		}
	}
		
	$cond = '1';
	
	$header = '<tr>';
	$header .= '<th class="text-center" style="width:5em;">ID</th>';
	$header .= '<th>标题</th>';
	$header .= '</tr>';
	
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
	$pag_config['table_name'] = "materials";
	$pag_config['cond'] = $cond;
	$pag_config['tail'] = "order by id asc";
	$pag = new Paginator($pag_config);

	$div_classes = array('table-responsive');
	$table_classes = array('table', 'table-bordered', 'table-hover', 'table-striped');
?>
<?php echoUOJPageHeader(UOJLocale::get('problems')) ?>
<div class="row">
	<div class="col-sm-4"></div>
	<div class="col-sm-4 col-sm-push-4 checkbox text-right"></div>
	<div class="col-sm-4 col-sm-pull-4">
	<?php echo $pag->pagination(); ?>
	</div>
</div>
<div class="top-buffer-sm"></div>
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
