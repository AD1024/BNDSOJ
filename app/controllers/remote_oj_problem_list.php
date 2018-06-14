<?php
/*
	Created by dhxh
*/
	requirePHPLib('form');
	requirePHPLib('judger');
	requirePHPLib('svn');

	if ($myUser == null) {
		redirectToLogin();
	}

	include "remote_oj/main.php";

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
	$cur_tab = 'remoteoj';

	$remote_ojs = array(
		'poj' => array(
			'name' => 'POJ',
			'url' => "/remoteoj?ojname=poj"
		),
		'codeforces' => array(
			'name' => 'Codeforces',
			'url' => "/remoteoj?ojname=codeforces"
		),
		'hdu' => array(
			'name' => 'HDU',
			'url' => "/remoteoj?ojname=hdu"
		)
	);
	

	if($_GET['ojname']){
		$oj_name = $_GET['ojname'];
	}else{
		$oj_name = "hdu";
	}

	$roj_cur_tab = $oj_name;
	
	$oj_action = "list";
	if($_GET['page']){
		$oj_page_id = $_GET['page'];
	}else{
		$oj_page_id = 1;
	}
	
	$oj_problem_list = getojinfo($oj_name, $oj_action, $oj_problem_id, $oj_page_id, $oj_code);

	if (!$oj_problem_list["isfound"]) {
		become404Page();
	}

	$header = '<tr>';
	$header .= '<th class="text-center" style="width:5em;">ID</th>';
	$header .= '<th>'.UOJLocale::get('problems::problem').'</th>';
	$header .= '</tr>';

	$div_classes = array('table-responsive');
	$table_classes = array('table', 'table-bordered', 'table-hover', 'table-striped');
?>

<?php echoUOJPageHeader(UOJLocale::get('problems')) ?>
<div class="row">
	<div class="col-sm-4">
		<?= HTML::tablist($tabs_info, $cur_tab, 'nav-pills') ?>
	</div>
	<div class="col-sm-4 col-sm-push-4">
		<?= HTML::tablist($remote_ojs, $roj_cur_tab, 'nav-pills') ?>
	</div>
	<div class="col-sm-4 col-sm-pull-4">
	<?php //echo $pag->pagination(); ?>
		<div class="text-center">
			<ul class="pagination top-buffer-no bot-buffer-sm">
				<li<?php if($oj_page_id==1)echo ' class="disabled"'; ?>>
					<?php echo '<a href="/remoteoj?ojname='.$oj_name.'&page='.($oj_page_id-1).'"';?>>
						<span class="glyphicon glyphicon glyphicon-backward"></span>
					</a>
				</li>
				<?php
				$j = $oj_page_id - 2;
				if($j<1)$j = 1;
				for($i=1;$i<=5;$i++){
					if($j==$oj_page_id){
						echo '<li class="active">';
					}else{
						echo '<li>';
					}
					echo '<a href="/remoteoj?ojname='.$oj_name.'&page='.$j.'">'.$j.'</a></li>';
					$j++;
				}
				
				?>
				<li>
					<?php echo '<a href="/remoteoj?ojname='.$oj_name.'&page='.($oj_page_id+1).'"';?>>
						<span class="glyphicon glyphicon glyphicon-forward"></span>
					</a>
				</li>
			</ul>
		</div>
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
	
	$i = 0;

	while($oj_problem_list["id"][$i]){
		echo '<tr class="text-center">';
		$ac_cnt = DB::selectCount("select count(*) from remote_oj_submissions where oj_name = '".$oj_name."' and problem_id = '".$oj_problem_list["id"][$i]."' and submitter ='".$myUser['username']."' and result ='Accepted';");
		if ($ac_cnt > 0) {
			echo '<td class="success">';
		} else {
			echo '<td>';
		}
		echo '#', trim($oj_problem_list["id"][$i]), '</td>';
		echo '<td class="text-left">', '<a href="/remoteoj/problem?ojname=', $oj_name, '&problemid=', trim($oj_problem_list["id"][$i]), '">', trim($oj_problem_list["title"][$i]), '</a>';
		echo '</tr>';
		$i++;
	}
	
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
	
	//echo $pag->pagination();
?>

		<div class="text-center">
			<ul class="pagination top-buffer-no bot-buffer-sm">
				<li<?php if($oj_page_id==1)echo ' class="disabled"'; ?>>
					<?php echo '<a href="/remoteoj?ojname='.$oj_name.'&page='.($oj_page_id-1).'"';?>>
						<span class="glyphicon glyphicon glyphicon-backward"></span>
					</a>
				</li>
				<?php
				$j = $oj_page_id - 2;
				if($j<1)$j = 1;
				for($i=1;$i<=5;$i++){
					if($j==$oj_page_id){
						echo '<li class="active">';
					}else{
						echo '<li>';
					}
					echo '<a href="/remoteoj?ojname='.$oj_name.'&page='.$j.'">'.$j.'</a></li>';
					$j++;
				}
				
				?>
				<li>
					<?php echo '<a href="/remoteoj?ojname='.$oj_name.'&page='.($oj_page_id+1).'"';?>>
						<span class="glyphicon glyphicon glyphicon-forward"></span>
					</a>
				</li>
			</ul>
		</div>
<?php echoUOJPageFooter() ?>
