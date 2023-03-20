<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Ubah Password</h1>

<form method="post" action="<?php echo site_url('pengguna/autentikasi/new-password'); ?>">

<div class="row m-0">
	<div class="col-4">

		<?php if ($this->session->flashdata('post_status') == 'ok'): ?>
			<div class="alert alert-success">Password berhasil diubah.</div>
		<?php endif; ?>

		<div class="form-group">
			<label>Password Sekarang</label>
			<input type="text" class="form-control" name="current_password">
			<?php if (isset($errors)) echo $errors['current_password']; ?>
		</div>
		<div class="form-group">
			<label>Password Baru</label>
			<input type="text" class="form-control" name="new_password">
			<?php if (isset($errors)) echo $errors['new_password']; ?>
		</div>
		
	</div>
</div>

<div class="actionbar fixed-bottom">
	<button type="submit" class="btn btn-primary">
		<i class="ti ti-save"></i> Simpan
	</button>
</div>

</form>