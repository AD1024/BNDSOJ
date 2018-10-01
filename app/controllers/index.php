<?php
	$blogs = DB::selectAll("select blogs.id, title, poster, post_time from important_blogs, blogs where is_hidden = 0 and important_blogs.blog_id = blogs.id order by level desc, important_blogs.blog_id desc limit 5");
	$blogs2 = DB::selectAll("select blogs.id, title, poster, post_time from blogs where is_hidden = 0 and is_recommanded = 1 order by post_time desc limit 5");
?>

<?php echoUOJPageHeader('UOJ') ?>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-9 col-md-9">
				<table class="table">
					<thead>
						<tr>
							<th style="width:55%"><?= UOJLocale::get('announcements') ?></th>
							<th style="width:25%"></th>
							<th style="width:20%"></th>
						</tr>
					</thead>
				  	<tbody>
					<?php $now_cnt = 0; ?>
					<?php foreach ($blogs as $blog): ?>
						<?php
							$now_cnt++;
							$new_tag = '';
							if ((time() - strtotime($blog['post_time'])) / 3600 / 24 <= 7) {
								$new_tag = '<sup style="color:red">&nbsp;new</sup>';
							}
						?>
						<tr>
							<td><a href="/blog/<?= $blog['id'] ?>"><?= $blog['title'] ?></a><?= $new_tag ?></td>
							<td>by <?= getUserLink($blog['poster']) ?></td>
							<td><small><?= $blog['post_time'] ?></small></td>
						</tr>
					<?php endforeach ?>
					<?php for ($i = $now_cnt + 1; $i <= 5; $i++): ?>
						<tr><td colspan="233">&nbsp;</td></tr>
					<?php endfor ?>
						<tr><td class="text-right" colspan="233"><a href="/announcements"><?= UOJLocale::get('all the announcements') ?></a></td></tr>
					</tbody>
				</table>
			</div>
			<div class="col-sm-3 col-md-3">
				<table class="table">
					<thead>
						<tr>
							<th><?= 博客推荐 ?></th>
							<!--<th style="width:20%"></th>-->
							<!--<th style="width:20%"></th>-->
						</tr>
					</thead>
				  	<tbody>
					<?php $now_cnt = 0; ?>
					<?php foreach ($blogs2 as $blog): ?>
						<?php
							$now_cnt++;
							$new_tag = '';
							if ((time() - strtotime($blog['post_time'])) / 3600 / 24 <= 7) {
								$new_tag = '<sup style="color:red">&nbsp;new</sup>';
							}
						?>
						<tr>
							<td><a href="/blog/<?= $blog['id'] ?>"><?= $blog['title'] ?></a><?= $new_tag ?></td>
							<!--<td>by <?= getUserLink($blog['poster']) ?></td>-->
							<!--<td><small><?= $blog['post_time'] ?></small></td>-->
						</tr>
					<?php endforeach ?>
					<?php for ($i = $now_cnt + 1; $i <= 5; $i++): ?>
						<tr><td colspan="233">&nbsp;</td></tr>
					<?php endfor ?>
					</tbody>
				</table>
			</div>
			<!--<div class="col-xs-6 col-sm-4 col-md-3">
				<img class="media-object img-thumbnail" src="/pictures/UOJ.png" alt="UOJ logo" />
			</div>-->
		</div>
	</div>
</div>
<?php // Skqliao begin ?>
<?php
	if (isset($_GET['tab'])) {
		$cur_tab = $_GET['tab'];
	} else {
		$cur_tab = 'contest';
	}
?>
<?php if($cur_tab == "contest"): ?>
<div class="row">
	<div class="col-sm-12">
		<h3><?= UOJLocale::get('contest top rated') ?></h3>
		<div class="text-right">
			<a href="/index_problem"><?= 切换至做题排行榜 ?></a>
		</div>
		<?php echoContestRanklist(array('echo_full' => '', 'top10' => '')) ?>
		<div class="text-center">
			<a href="/contest_ranklist"><?= UOJLocale::get('view all') ?></a>
		</div>
	</div>
</div>
<?php endif; ?>
<?php if($cur_tab == "problem"): ?>
<div class="row">
	<div class="col-sm-12">
		<h3><?= UOJLocale::get('problem top rated') ?></h3>
		<div class="text-right">
			<a href="/index_contest"><?= 切换至比赛排行榜 ?></a>
		</div>
		<?php echoRanklist(array('echo_full' => '', 'top10' => '')) ?>
		<div class="text-center">
			<a href="/ranklist"><?= UOJLocale::get('view all') ?></a>
		</div>
	</div>
</div>
<?php endif; ?>
<?php // Skqliao end ?>
<?php echoUOJPageFooter() ?>
	