<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Ubah No. Transaksi</h1>

<form method="post" action="<?php echo $action_url; ?>">
<input type="hidden" name="id" value="<?php echo $data['id']; ?>">

<div class="row m-0">
	<div class="col-4">
		<div class="form-group">
			<label>Nama Transaksi</label>
			<input type="text" class="form-control" name="nama" value="<?php echo $data['nama']; ?>" readonly>
		</div>
		<div class="row">
			<div class="form-group col-8">
				<label>Format Nomor</label>
				<input type="text" class="form-control" name="format" value="<?php echo $data['format']; ?>">
				<?php if (isset($errors)) echo $errors['format']; ?>
			</div>
			<div class="form-group col-4">
				<label>Digit Serial</label>
				<select class="select2-no-clear" name="digit_serial" style="width:100%">
					<?php echo modules::run('options/number', 3, 6, $data['digit_serial']); ?>
				</select>
				<?php if (isset($errors)) echo $errors['digit_serial']; ?>
			</div>
		</div>
		<div class="form-group">
			<div class="form-group form-check-inline">
				<input type="checkbox" class="form-check-input" id="check-reset" name="is_reset_serial" value="1" <?php echo isset($data['is_reset_serial']) && $data['is_reset_serial'] == 1 ? 'checked' : ''; ?>>
				<label class="form-check-label" for="check-reset">Apakah serial di-reset?</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="reset_serial" id="reset-tahunan" value="tahunan" <?php echo $data['reset_serial'] == 'tahunan' ? 'checked' : ''; ?>>
				<label class="form-check-label" for="reset-tahunan">Tahunan</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="reset_serial" id="reset-bulanan" value="bulanan" <?php echo $data['reset_serial'] == 'bulanan' ? 'checked' : ''; ?>>
				<label class="form-check-label" for="reset-bulanan">Bulanan</label>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-6">
				<label>Periode Sekarang</label>
				<input type="text" class="form-control datepicker" name="periode" value="<?php echo $data['tahun_sekarang'].'-'.$data['bulan_sekarang']; ?>">
			</div>
			<div class="form-group col-6">
				<label>Serial Berikutnya</label>
				<input type="text" class="form-control" name="serial_berikutnya" value="<?php echo $data['serial_berikutnya']; ?>">
				<?php if (isset($errors)) echo $errors['serial_berikutnya']; ?>
			</div>
		</div>
		<button type="reset" class="btn btn-secondary">Reset</button>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<button type="submit" class="btn btn-primary">
		<i class="ti ti-save"></i> Simpan
	</button>
	
	<a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('pengaturan/no-transaksi') ?>">
		<i class="ti ti-na"></i> Batalkan
	</a>
</div>

</form>



<script>
$().ready(function() {
	
	$('.datepicker').Zebra_DatePicker({
		format: 'Y-m',
		offset: [-200, -12]
	});
	
});
</script>