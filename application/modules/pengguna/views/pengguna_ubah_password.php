<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<div class="col-sm-4 offset-sm-4 p-0">
	<div class="card">
		<div class="card-header">Ubah Password</div>
		<div class="card-body">
			<?php if ($this->session->flashdata('post_status') == 'ok'): ?>
			<div class="alert alert-success">Password berhasil diubah.</div>
			<?php endif; ?>
			
			<form method="post" action="<?php echo site_url('pengguna/autentikasi/new-password'); ?>">
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
				<button type="submit" class="btn btn-primary">
					<i class="ti ti-save"></i> Ubah Password
				</button>
			</form>
		</div>
		<div class="card-footer credit"><?php echo $this->config->item('credit'); ?></div>
	</div>
</div>