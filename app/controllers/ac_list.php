<?php
	$username = $_GET['username'];
	requirePHPLib('form');
	requirePHPLib('judger');
	
	if ($myUser == null || !isSuperUser($myUser)) {
		become403Page();
	}
	
	$cond = '1';
	
	if (validateUsername($username) && ($user = queryUser($username))){
	
	$esc_realname = HTML::escape($user['realname']);
	if($esc_realname){
		$esc_realname = '('.$esc_realname.')';
	}
	
	function echoProblem($problem) {
		global $username;
		if (isProblemVisibleToUser($problem, $username)) {
			echo '<tr class="text-center">';
			if ($problem['submission_id']) {
				echo '<td style="background-color:#38b44a">';
			} else {
				echo '<td>';
			}
			echo '#', $problem['id'], '</td>';
			echo '<td class="text-left">', '<a href="/problem/', $problem['id'], '">', $problem['title'], '</a>';
			if (isset($_COOKIE['show_tags_mode'])) {
				foreach (queryProblemTags($problem['id']) as $tag) {
					echo '<a class="uoj-problem-tag">', '<span class="badge">', HTML::escape($tag), '</span>', '</a>';
				}
			}
			echo '</td>';
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
			echo '</tr>';
		}
	}
	
	$header = '<tr>';
	$header .= '<th class="text-center" style="width:5em;">ID</th>';
	$header .= '<th>'.UOJLocale::get('problems::problem').'</th>';
	$header .= '<th class="text-center" style="width:5em;">'.UOJLocale::get('problems::ac').'</th>';
	$header .= '<th class="text-center" style="width:5em;">'.UOJLocale::get('problems::submit').'</th>';
	$header .= '<th class="text-center" style="width:150px;">'.UOJLocale::get('problems::ac ratio').'</th>';
	$header .= '</tr>';
	
	$pag_config = array('page_len' => 100);
	$pag_config['col_names'] = array('*');
	$pag_config['table_name'] = "problems left join best_ac_submissions on best_ac_submissions.submitter = '{$username}' and problems.id = best_ac_submissions.problem_id";
	$pag_config['cond'] = $cond;
	$pag_config['tail'] = "order by id asc";
	$pag = new Paginator($pag_config);

	$div_classes = array('table-responsive');
	$table_classes = array('table', 'table-bordered', 'table-hover', 'table-striped');

?>
<?php echoUOJPageHeader('AC列表') ?>

<?php  ?>

<div align="center"><h2><span class="uoj-honor" data-rating="<?= $user['rating'] ?>"><?= $user['username'].$esc_realname ?></span></strong></span>的AC列表</h2></div>

<div class="row">
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
	
	echo $pag->pagination();
?>

<?php }else{ ?>
	<?php echoUOJPageHeader('不存在该用户' . ' - 用户信息') ?>
	<div class="panel panel-danger">
		<div class="panel-heading">用户信息</div>
		<div class="panel-body">
		<h4>不存在该用户</h4>
		</div>
	</div>
<?php } ?>

<?php echoUOJPageFooter() ?>
