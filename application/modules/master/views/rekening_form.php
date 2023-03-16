<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Form Rekening</h1>

<form method="post" action="<?php echo $action_url; ?>">
<input type="hidden" name="id" value="<?php if ($data != null) echo $data['id']; ?>">
<div class="row m-0">
	<div class="col-8">
	<div class="row">
			<div class="col-6">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0"><span class="text-danger">*</span> Nama Bank </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="bank" value="<?php if ($data != null) echo $data['bank']; ?>">
						<?php if (isset($errors)) echo $errors['bank']; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0"><span class="text-danger">*</span> No Rekening </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="no_rekening" value="<?php if ($data != null) echo $data['no_rekening']; ?>">
						<?php if (isset($errors)) echo $errors['no_rekening']; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<button class="btn btn-primary">
		<i class="ti ti-save"></i> Simpan
	</button>
	
	<a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('master/rekening') ?>">
		<i class="ti ti-na"></i> Batalkan
	</a>
</div>

</form>