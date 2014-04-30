<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo isset($title) ? $title : 'MediaBox'; ?></title>

	<?php
		echo $this->Html->meta('icon');
		echo $this->fetch('meta');

		// CSS
		echo $this->Html->css('bootstrap.css');
		echo $this->Html->css('font-awesome.min.css');
		echo $this->fetch('css');

		echo $this->Html->css('style.css');

		// Scripts
		echo $this->Html->script('jquery.min.js');
		echo $this->Html->script('bootstrap.min.js');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div class="navbar-default">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo $this->Html->url('/'); ?>">MediaBox</a>
		</div>
		<div class="navbar-collapse collapse navbar-responsive-collapse">
			<ul class="nav navbar-nav">

				<li class="dropdown">
					<a href="<?php echo $this->Html->url('/movies'); ?>" class="dropdown-toggle" data-toggle="dropdown">Movies <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo $this->Html->url('/movies'); ?>">Movie List</a></li>
						<li><a href="<?php echo $this->Html->url('/movies/upcoming'); ?>">Upcoming Movies</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo $this->Html->url('/requests/movie'); ?>">Request Movie</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="<?php echo $this->Html->url('/tv'); ?>" class="dropdown-toggle" data-toggle="dropdown">TV Shows <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo $this->Html->url('/tv'); ?>">TV Show List</a></li>
						<li><a href="<?php echo $this->Html->url('/tv/schedule'); ?>">TV Show Schedule</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo $this->Html->url('/requests/tv'); ?>">Request TV Show</a></li>
						<li><a href="<?php echo $this->Html->url('/requests/season'); ?>">Request Season</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="<?php echo $this->Html->url('/information'); ?>" class="dropdown-toggle" data-toggle="dropdown">Information <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo $this->Html->url('/information/history'); ?>">Media History</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo $this->Html->url('/information/stats'); ?>">MediaBox Stats</a></li>
						<li><a href="<?php echo $this->Html->url('/information'); ?>">MediaBox Information</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="<?php echo $this->Html->url('/requests'); ?>" class="dropdown-toggle" data-toggle="dropdown">Requests <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo $this->Html->url('/requests/recent'); ?>">Recent Requests</a></li>
						<li><a href="<?php echo $this->Html->url('/requests/history'); ?>">Request History</a></li>
					</ul>
				</li>

				<li class="dropdown">
					<a href="<?php echo $this->Html->url('/settings'); ?>" class="dropdown-toggle" data-toggle="dropdown">Settings <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo $this->Html->url('/settings'); ?>">MediaBox Config</a></li>
						<li><a href="<?php echo $this->Html->url('/settings/users'); ?>">User Access List</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo $this->Html->url('/settings/logs'); ?>">Logs</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php
				if ( $logged_in ) {
					echo $this->element('navbar/userinfo');
				} else {
					echo $this->element('navbar/login');
				}
				?>
			</ul>
		</div>
	</div>

	<div class="container">
		<?php echo $this->fetch('content'); ?>
	</div>

	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
