<h1 class="my-header">Absensi</h1>

<div class="row m-0">
	<div class="col-12">
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr>
					<th width="5px">No.</th>
					<th width="5px"></th>
					<th>Pegawai</th>
					<th>Tgl.</th>
					<th>Masuk</th>
					<th>Keluar</th>
					<th>Yg Buat</th>
					<th>Yg Ubah</th>
					<th>Yg Approve</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
function init_datatable()
{
	datatable = $('#datatable').DataTable ({
		'bInfo': true,
		'pageLength': 25,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': '<?php echo site_url('/absensi/datatable'); ?>',
		'stateSave': true,
		'order': [[ 3, 'desc' ]],
		'fixedHeader': true,
		'columns': [
			{ data: 'nomor', orderable: false },
			
			{
                orderable: false,
                render: function(data, type, row, meta) {
					if(row.yg_approve) return '';
                    return '<a class="btn_approve" data-id="' + row.id +'" href="javascript:void(0)"><img src="<?php echo base_url(); ?>assets/img/check.png" style="width:16px"></a>';
                }
            },
			{
				data: 'pegawai'
			},
			{ data: 'tgl' , className: 'dt-center'},
			{ data: 'masuk', className: 'dt-center'  },
			{ data: 'keluar', className: 'dt-center' },
			{ data: 'yg_buat' },
			{ data: 'yg_ubah' },
			{ data: 'yg_approve' },	
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
	$(document).on("click",".btn_approve",function(){
		const t = $(this);
		if(confirm("Anda Yakin Ingin Approve Absensi Ini ? ") == true){
			window.location.href = site_url + 'absensi/approve/' + t.data('id');
		}
	})
});
</script>