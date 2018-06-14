<?php
	requirePHPLib('form');
	requirePHPLib('judger');
	
	if (!validateUInt($_GET['id'])) {
		become404Page();
	}

	if ($myUser == null) {
		redirectToLogin();
	}

	if(DB::selectCount("select * from materials where id=".$_GET['id'].";") == 0){
		become404Page();
	}

	$material = mysql_fetch_array(mysql_query("select * from materials where id=".$_GET['id'].";"), MYSQL_ASSOC);

	if($material['is_hidden'] == 1 and (!isSuperUser($myUser))){
		become404Page();
	}

	//dhxh begin
	function queryMaterials($id) {
		return mysql_fetch_array(mysql_query("select * from materials_contents where id='$id'"), MYSQL_ASSOC);
	}
	
	$material_content = queryMaterials($material['id']);
	//dhxh end
?>
<?php
	$REQUIRE_LIB['mathjax'] = '';
	$REQUIRE_LIB['shjs'] = '';
?>
<?php echoUOJPageHeader(HTML::stripTags($material['title'])) ?>

<h1 class="page-header text-center">#<?= $material['id']?>. <?= $material['title'] ?></h1>

<ul class="nav nav-tabs" role="tablist">
	<li class="active"><a href="#tab-statement" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-book"></span> <?= UOJLocale::get('problems::statement') ?></a></li>
	<?php if (isSuperUser($myUser)): ?>
	<li><a href="/material/<?= $material['id'] ?>/manage/statement" role="tab"><?= UOJLocale::get('problems::manage') ?></a></li>
	<?php endif ?>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tab-statement">
		<article class="top-buffer-md"><?= $material_content['statement'] ?></article>
	</div>
</div>
<?php echoUOJPageFooter() ?>
