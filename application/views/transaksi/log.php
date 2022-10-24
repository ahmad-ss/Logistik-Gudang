<div class="col-md-12">
	<h2>Log Transaksi</h2>
</div>

<div class="col-md-12">
	<div class="table-responsive">
		<table class="table table-bordered" id="tbl_log" style="font-size:12px;">
			<thead>
				<tr>
					<th class="text-uppercase text-center">No Transaksi</th>
					<th class="text-uppercase text-center">Tanggal</th>
					<th class="text-uppercase text-center">Qty Masuk</th>
					<th class="text-uppercase text-center">Qty Keluar</th>
					<th class="text-uppercase text-center">User</th>
					<th class="text-uppercase text-center">Status</th>
					<th class="text-uppercase text-center">Tanggal Waktu</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($log as $log):?>
				<tr>
					<td><?php echo $log->no_lgs;?></td>
					<td><?php echo $log->tanggal;?></td>
					<td><?php echo $log->qty_masuk;?></td>
					<td><?php echo $log->qty_keluar;?></td>
					<td><?php echo $log->nama_user;?></td>
					<td class="text-center">
						<?php 
						if($log->sts_aksi == 'S'){
							echo '<label class="label label-primary">Save</label>';
						}

						if($log->sts_aksi == 'D'){
							echo '<label class="label label-danger">Delete</label>';
						}						
						?>
					</td>
					<td><?php echo $log->tanggalwaktu;?></td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#tbl_log').DataTable({
		"ordering":false,
		"dom": 'Bfrtip',
		"buttons": [
			//'copy', 'csv', 'excel', 'pdf', 'print'
			{
				extend:'copyHtml5',
				exportOptions:{
					columns: ':visible'
				}
			},
			{
				extend:'excelHtml5',
				exportOptions:{
					columns: ':visible'
				}
			},
			{
				extend:'pdfHtml5',
				exportOptions:{
					columns: ':visible'
				}
			}
		],
		"columnDefs":[
        	{
        		"targets":[2,3],
        		"className":"text-right"
        	}
        ]
	});
});
</script>