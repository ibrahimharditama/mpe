<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Data Perusahaan</h1>

<form method="post" action="<?php echo site_url('pengaturan/perusahaan/update'); ?>">
<div class="row m-0">
	<div class="col-4">

		<?php if ($this->session->flashdata('post_status') == 'ok'): ?>
			<div class="alert alert-success">Data perusahaan berhasil diubah.</div>
		<?php endif; ?>

		<div class="form-group">
			<label>Nama Usaha <font color="Crimson">*</font></label>
			<input type="text" class="form-control" name="nama" value="<?php echo $data['nama']; ?>">
			<?php if (isset($errors)) echo $errors['nama']; ?>
		</div>
		<div class="form-group">
			<label>Alamat</label>
			<textarea class="form-control" name="alamat"><?php echo $data['alamat']; ?></textarea>
		</div>
		<div class="form-group">
			<label>No. Telp.</label>
			<input type="text" class="form-control" name="no_telp" value="<?php echo $data['no_telp']; ?>">
		</div>
		<div class="form-group">
			<label>Email</label>
			<input type="text" class="form-control" name="email" value="<?php echo $data['email']; ?>">
			<?php if (isset($errors)) echo $errors['email']; ?>
		</div>
		<div class="form-group">
			<label>Website</label>
			<input type="text" class="form-control" name="website" value="<?php echo $data['website']; ?>">
		</div>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<button type="submit" class="btn btn-primary">
		<i class="ti ti-save"></i> Ubah Profil Usaha
	</button>
</div>
</form>