<?php
if ( isset($failed_login) && $failed_login ) {
	$alert = "<p>Sorry, I was unable to log you in with that username or password.</p>".
	"<p>Note, authentication could also fail because the server owner has not granted you access to this system yet.</p>".
	"<p>If you believe you should have access, please contact the server owner.</p>";
} else {
	$alert = "";
}
?>
<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
	<div class="panel panel-info" >
		<div class="panel-heading">
			<div class="panel-title">Log In</div>
		</div>

		<div style="padding-top:30px" class="panel-body">
			<div style="<?php echo (empty($alert) ? 'display:none' : ''); ?>" id="login-alert" class="alert alert-danger col-sm-12">
				<?php echo $alert; ?>
			</div>
			<form action="<?php echo $this->Html->url('/users/login'); ?>" method="post" id="loginform" class="form-horizontal" role="form">
				<div style="margin-bottom: 25px" class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
					<input id="login-username" type="text" class="form-control" name="username" value="<?php echo $username != 'Guest' ? $username : null; ?>" placeholder="Username">
				</div>
				
				<div style="margin-bottom: 25px" class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
					<input id="login-password" type="password" class="form-control" name="password" placeholder="Password">
				</div>

				<div style="margin-top:10px" class="form-group">
					<div class="col-sm-12 controls">
						<button class="btn btn-success" type="submit">Login</button>
					</div>
				</div>  
			</form>
		</div>                     
	</div>
</div>