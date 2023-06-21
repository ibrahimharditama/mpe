<style>
	.verticaltext {
		text-orientation: upright;
		writing-mode: vertical-lr;
	}
</style>
<div class="my-header pb-0 d-flex">
	<p class="mr-3 mt-1">Absensi</p> 
    <div style="width: 130px;">
        <input type="text" class="form-control datepicker-year-month" value="<?= $periode; ?>" readonly>
    </div>
</div>



<div class="row m-0">
	<div class="col-12">
		<div class="table-responsive">

		
			<table class="table table-sm table-bordered table-hover text-center" id="datatable">
				<thead>	
					<tr class="table-secondary font-weight-bold">
						<?php foreach ($data['headerhari'] as $h => $headerhari): ?>

							<th class="font-weight-bold"><?= strtoupper($headerhari); ?></th>

						<?php endforeach; ?>
						<th class="font-weight-bold"></th>
					</tr>
					<tr class="table-secondary font-weight-bold">
						<th class="font-weight-bold">Pegawai</th>

						<?php 
							foreach ($data['header'] as $h => $header): 
							$hl = strtolower(date('D',strtotime($header)));
						?>
							

							<?php if($hl == 'sun'): ?>

								<th class="font-weight-bold"><?= date('j', strtotime($header)); ?></th>

							<?php else: ?>

								<th class="font-weight-bold">
									<a href="javascript:void(0)" class="text-dark" 
										data-tgl="<?= $header; ?>" 
										data-libur="<?= ($ke = array_search($header, $data['libur'])) !== FALSE ? 'yes' : 'no'; ?>" 
										onclick="libur(this)"> 
										<?= date('j', strtotime($header)); ?>
									</a>
								</th>

							<?php endif; ?>
							
							

						<?php endforeach; ?>
						<th class="font-weight-bold">Total Absen</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($data['body'] as $b => $row): ?>
						<tr>
							<?php $total = 0; foreach ($row as $v => $value): ?>
								
								<?php 
									$colorcell = '';

									$hari = strtolower(date('D',strtotime($v)));

									if($hari == 'sun') {
										$colorcell = 'table-warning';
									}

									if(($k = array_search($v, $data['libur'])) !== FALSE) {
										$colorcell = 'table-warning';
									} 

									$id_pengguna = $row->id_pengguna;
									$dataAbsen = $id_pengguna.'#'.$value.'#'.$v;

									if ($v == 'id_pengguna') continue; 

									if ($v != 'nama') {
										if ($value == '1') {
											$total += 1;
											$absen = '<a href="javascript:void(0)" class="text-success tgl-'.$v.'" data-tgl="'.$v.'" data-absen="'.$dataAbsen.'" onclick="absen(this)">1</a>';
										} else {
											$absen = '<a href="javascript:void(0)" class="text-danger tgl-'.$v.'" data-tgl="'.$v.'" data-absen="'.$dataAbsen.'" onclick="absen(this)">A</a>';
										}
										
									}
								?>

								<td class="<?= $colorcell; ?>"><?= $v == 'nama' ? $value : $absen; ?></td>
							<?php endforeach; ?>
							<td class="totalAbsen_<?= $id_pengguna; ?>"><?= $total; ?></td>
						</tr>
					

					<?php endforeach; ?>
				</tbody>
			</table>

		</div>
	</div>
</div>

<script>

	$().ready(function() {
        $('.datepicker-year-month').Zebra_DatePicker({
            offset: [-203, 280], 
            format: 'Y-m',
            show_clear_date: false, 
            view: 'months',
            onSelect: function() {
                var url = site_url + 'absensi/index/' + $(this).val();
                window.location.href = url;
            }

        });
    });

	function absen(ini) {
		var absen = $(ini).attr('data-absen');
		var data = absen.split('#');

		var id_pengguna = data[0];
		var status = data[1] == '1' ? 0 : 1;
		var tgl = data[2];

		$.post(site_url + 'absensi/approve', {
			'id_pengguna': id_pengguna,
			'status': status,
			'tgl': tgl
		}, function(res) {

			var total = $('.totalAbsen_' + id_pengguna);
			var totalAbsen = parseInt(total.html());
			var html = '';
			var clas = '';
			if(status == 1){
				totalAbsen += 1;
				clas = 'text-success';
				html = '1'

				if(status == '-') {
					status = 1;
				}

			} else {
				totalAbsen -= 1;
				clas = 'text-danger';
				html = 'A'
			}

			total.html(totalAbsen);

			$(ini).attr('class', clas);
			$(ini).attr('data-absen', id_pengguna + '#' + status + '#' + tgl);
			$(ini).html(html);
		});
	}

	function libur(ini) {
		var tgl = $(ini).data('tgl');
		var is_libur = $(ini).data('libur');

		$.post(site_url + 'absensi/ins_libur', {
			tgl: tgl,
			is_libur: is_libur,
		}, function(result) {
			console.log(result);
			$(ini).data('libur', result == 'true' ? 'yes' : 'no');

			if(result === 'true') {
				$('.tgl-' + tgl).parent().addClass('table-warning');
			} else {
				$('.tgl-' + tgl).parent().removeClass('table-warning');
			}
			
		});
	}

</script>