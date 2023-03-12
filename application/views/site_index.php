<h2 class="login-heading mb-5 text-center">
	Makmur Permai Elektronik
</h2>

<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<div class="alert alert-danger">
	Email, Username, atau Password salah.
</div>
<?php endif; ?>

<form method="post" action="<?php echo site_url('pengguna/autentikasi/login'); ?>">
	<div class="form-label-group">
		<input type="text" name="email" id="email" class="form-control" placeholder="Email" required autofocus>
		<label for="email">Email atau Username</label>
	</div>
	<div class="form-label-group">
		<input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
		<label for="password">Password</label>
	</div>
	<div class="custom-control custom-checkbox mb-3 ml-2">
		<input type="checkbox" class="custom-control-input" name="remember" id="remember" value="1">
		<label class="custom-control-label" for="remember">Ingat Saya</label>
	</div>
	<button class="btn btn-lg btn-warning btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">
		Login Sekarang
	</button>
	<div class="ml-2">
		<a class="small" href="<?php echo site_url('pengguna/autentikasi/lupa-password'); ?>">Lupa Password</a>
	</div>
</form>