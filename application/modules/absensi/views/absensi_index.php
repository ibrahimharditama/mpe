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
						<?php foreach ($data['header'] as $h => $header): ?>

							<th class="font-weight-bold"><?= $header; ?></th>

						<?php endforeach; ?>
						<th class="font-weight-bold">Total Absen</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($data['body'] as $b => $row): ?>
						<tr>
							<?php $total = 0; foreach ($row as $v => $value): ?>
								
								<?php 
									$id_pengguna = $row->id_pengguna;
									$dataAbsen = $id_pengguna.'#'.$value.'#'.$v;
									$absen = '<a href="javascript:void(0)" class="text-danger" data-absen="'.$dataAbsen.'" onclick="absen(this)"><i class="ti-pencil-alt"><i></a>';

									if ($v == 'id_pengguna') continue; 

									if ($v != 'nama') {
										if ($value == '1') {
											$total += 1;
											$absen = '<a href="javascript:void(0)" class="text-success" data-absen="'.$dataAbsen.'" onclick="absen(this)"><i class="ti-check-box"><i></a>';
										}
										if ($value == '0') $absen = '<a href="javascript:void(0)" class="text-primary" data-absen="'.$dataAbsen.'" onclick="absen(this)"><i class="ti-thumb-up"><i></a>';
										
									}
								?>

								<td><?= $v == 'nama' ? $value : $absen; ?></td>
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
		var status = data[1];
		if(data[1] != '-') {
			status = data[1] == '1' ? 0 : 1;
		}
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
			if(status == 1 || status == '-'){
				totalAbsen += 1;
				clas = 'text-success';
				html = '<i class="ti-check-box"><i>'

				if(status == '-') {
					status = 1;
				}

			} else {
				totalAbsen -= 1;
				clas = 'text-primary';
				html = '<i class="ti-thumb-up"><i>'
			}

			total.html(totalAbsen);

			$(ini).attr('class', clas);
			$(ini).attr('data-absen', id_pengguna + '#' + status + '#' + tgl);
			$(ini).html(html);
		});
	}

</script>