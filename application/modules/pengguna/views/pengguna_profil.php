<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Profil Saya</h1>

<form method="post" action="<?php echo site_url('pengguna/update-profile'); ?>">

<div class="row m-0">
	<div class="col-4">
		<div class="form-group row">
			<label class="col-sm-4 col-form-label pr-0">Nama Lengkap</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="nama" value="<?php echo $data['nama']; ?>">
				<?php if (isset($errors)) echo $errors['nama']; ?>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-4 col-form-label pr-0">Email</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="email" value="<?php echo $data['email']; ?>" readonly>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-4 col-form-label pr-0">Username</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="username" value="<?php echo $data['username']; ?>">
				<?php if (isset($errors)) echo $errors['username']; ?>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-4 col-form-label pr-0">Grup Pengguna</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="grup_pengguna" value="<?php echo $data['grup_pengguna']; ?>" readonly>
			</div>
		</div>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<button type="submit" class="btn btn-primary">
		<i class="ti ti-save"></i> Simpan
	</button>
</div>

</form>