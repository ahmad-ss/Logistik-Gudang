<table class="table table-bordered" style="width:100%;font-size:12px;" id="tbl_data">
	<thead>
		<tr>
			<th colspan="7" class="text-center text-uppercase">Laporan Logistik <?php echo $saldoawal['nama_item'];?></th>
		</tr>
		<tr>
			<th colspan="7" class="text-center text-uppercase"><?php echo date('d F Y',strtotime($tgl));?></th>
		</tr>
		<tr>
			<th class="text-uppercase text-center" style="width:50px;">NO</th>
			<th class="text-uppercase text-center" style="width:80px;">Tanggal</th>
			<th class="text-uppercase text-center">ITEM</th>
			<th class="text-uppercase text-center">KETERANGAN</th>
			<th class="text-uppercase text-center">QTY MASUK</th>
			<th class="text-uppercase text-center">QTY KELUAR</th>
			<th class="text-uppercase text-center">SATUAN</th>
			<th class="text-uppercase text-center">JUMLAH</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no=1;
			$saw = $saldoawal['jumlah'];
			$debet = 0;
			$kredit = 0;

			foreach($transaksi_old as $trs_old){
				// Hitung debet atau kredit
				if($trs_old->qty_masuk > 0){
					$debet = $trs_old->qty_masuk;
					$saw = $saw+$debet;
				}

				if($trs_old->qty_keluar > 0){
					$kredit = $trs_old->qty_keluar;
					$saw = $saw-$kredit;
				}
			}
		?>
		<?php if($saw > 0):?>
		<tr>
			<td class="text-right"><?php echo $no;?></td>
			<td class="text-center"><?php if($saldoawal['tanggal'] != null){echo date('d/m/y',strtotime($saldoawal['tanggal']));}?></td>
			<td><?php echo $saldoawal['nama_item'];?></td>
			<td>SALDO AWAL</td>
			<td></td>
			<td></td>
			<td><?php echo $saldoawal['nama_satuan'];?></td>
			<td class="text-right">
				<?php 
					echo number_format($saw,0,',','.');
				?>
			</td>
		</tr>
		<?php endif;?>
		<?php foreach($transaksi as $trs):?>
			<?php
				$no++;

				// Hitung debet atau kredit
				if($trs->qty_masuk > 0){
					$debet = $trs->qty_masuk;
					$saw = $saw+$debet;
				}

				if($trs->qty_keluar > 0){
					$kredit = $trs->qty_keluar;
					$saw = $saw-$kredit;
				}
			?>
			<tr>
				<td class="text-right"><?php echo $no;?></td>
				<td class="text-center"><?php echo date('d/m/y',strtotime($trs->tanggal));?></td>
				<td><?php echo $trs->nama_item;?></td>
				<td class="text-uppercase"><?php echo $trs->keterangan;?></td>
				<td class="text-right"><?php echo number_format($trs->qty_masuk,0,',','.');?></td>
				<td class="text-right"><?php echo number_format($trs->qty_keluar,0,',','.');?></td>
				<td class="text-uppercase"><?php echo $trs->nama_satuan;?></td>
				<td class="text-right"><?php echo number_format($saw,0,',','.');?></td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>

<script type="text/javascript">
$(document).ready(function(){
	$('#tbl_data').DataTable({
		"ordering":false,
		"dom": 'Bfrtip',
		"buttons": [
			{
				extend:'print',
				title: function () { return 'Laporan Kas <?php echo $saldoawal['nama_item'];?>'; },
				messageTop: "Periode <?php echo date('d F Y',strtotime($tgl));?>"
			},
			{
				extend:'excelHtml5',
				title: function () { return 'Laporan Kas <?php echo $saldoawal['nama_item'];?>'; },
				messageTop: "Periode <?php echo date('d F Y',strtotime($tgl));?>"
				// exportOptions:{
				// 	columns: ':visible'
				// }
			}
		]
	});
});
</script>