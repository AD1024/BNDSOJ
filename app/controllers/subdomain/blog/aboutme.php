<?php requirePHPLib('form') ?>
<?php echoUOJPageHeader('关于我') ?>

<?php if (UOJContext::user()['username'] == 'teacherone'): ?>
<h3>博主是个超级大神犇！伟大领袖汪老师!</h3>
<?php else: ?>
	<?php if (UOJContext::user()['username']!='AD1024'):?>
		<h3>博主是个超级大神犇!</h3>
	<?php else: ?>
		<h3>博主太弱了！</h3>
	<?php endif ?>
<?php endif ?>

（好吧目前暂时不支持定制此页，我错了我会加这个功能的 T_T……）

<?php echoUOJPageFooter() ?>
