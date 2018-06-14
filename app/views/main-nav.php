<div class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?= HTML::url('/') ?>">BNDSOJ</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><a href="/contests"><?= UOJLocale::get('contests') ?></a></li>
				<li><a href="/problems"><?= UOJLocale::get('problems') ?></a></li>
				<li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= UOJLocale::get('submissions') ?></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/submissions"><?= UOJLocale::get('local submissions') ?></a></li>
						<li><a href="/remoteoj/submissions"><?= UOJLocale::get('remote submissions') ?></a></li>
					</ul>
				</li>
				<li><a href="/hacks"><?= UOJLocale::get('hacks') ?></a></li>
				<li><a href="/homework"><?= UOJLocale::get('homework') ?></a></li>
				<?php /*<li><a href="/the_pantheon"><?= UOJLocale::get('the pantheon') ?></a></li> */ ?>
				<?php /*<li><a href="/materials">课堂资料</a></li> */ ?>
				<li><a href="/materials"><?= UOJLocale::get('blogs') ?></a></li>
				<li><a href="/faq"><?= UOJLocale::get('help') ?></a></li>
				<?php /*<li><a href="http://bnds.tech">BNDSOJ for Contest</a></li>*/?>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</div>
