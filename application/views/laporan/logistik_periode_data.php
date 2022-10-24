<table class="table table-bordered" style="width:100%;font-size:12px;" id="tbl_data">
	<thead>
		<tr>
			<th colspan="7" class="text-center text-uppercase">Laporan Logistik <?php //echo $saldoawal['nama_item'];?></th>
		</tr>
		<tr>
			<th colspan="7" class="text-center text-uppercase">Periode <?php echo date('d F Y',strtotime($tglawal));?> S/D <?php echo date('d F Y',strtotime($tglakhir));?></th>
		</tr>
		<tr>
			<th class="text-uppercase text-center" style="width:50px;">NO</th>
			<th class="text-uppercase text-center" style="width:80px;">Tanggal</th>
			<th class="text-uppercase text-center">KETERANGAN</th>
			<th class="text-uppercase text-center">Qty Masuk</th>
			<th class="text-uppercase text-center">Qty Keluar</th>
			<th class="text-uppercase text-center">Satuan</th>
			<th class="text-uppercase text-center">Jumlah</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no=0;
			$saw = 0;
			$debet = 0;
			$kredit = 0;
		?>
		<?php //&& count($transaksi_old) > 0
		if(count($saldoawal) > 0):
		$no=$no+1;
		$saw = $saldoawal['jumlah'];
		foreach($transaksi_old as $trs_old){
			if($trs_old->debet > 0){
				$debet = $trs_old->qty_masuk;
				$saw = $saw+$debet;
			}

			if($trs_old->kredit > 0){
				$kredit = $trs_old->qty_keluar;
				$saw = $saw-$kredit;
			}
		}?>
		<tr>
			<td class="text-right"><?php echo $no;?></td>
			<td class="text-center"><?php if($saldoawal['tanggal'] != null){echo date('d/m/y',strtotime($saldoawal['tanggal']));}?></td>
			<td>JUMLAH AWAL</td>
			<td></td>
			<td></td>
			<td></td>
			<td class="text-right">
				<?php 
					echo number_format($saw,0,',','.');
				?>
			</td>
		</tr>
		<?php ?>
		<?php endif;?>
		<?php if($saw == 0):
			if(count($saldoawal_between) > 0){
			foreach($saldoawal_between as $qsb){
				$no++;
				$saw = $qsb->jumlah;
			}
		?>
		<!-- <tr>
			<td class="text-right"><?php echo $no;?></td>
			<td class="text-center"><?php 
				if(isset($qsb->tanggal)){
					date('d/m/y',strtotime($qsb->tanggal));
				}?>
			</td>
			<td class="text-uppercase"><?php echo 'SETUP QTY AWAL';?></td>
			<td class="text-right"><?php echo number_format($saw,0,',','.');?></td>
			<td class="text-right"><?php echo number_format(0,0,',','.');?></td>
			<td class="text-uppercase"><?php 
				if(isset($qsb->nama_satuan)){
					echo $qsb->nama_satuan;
				}?>
			</td>
			<td class="text-right"><?php echo number_format($saw,0,',','.');?></td>
		</tr> -->
		<?php } endif;?>
		<?php foreach($transaksi as $trs):
			$no++;
		?>
			<?php
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
				title: function () { return 'Laporan Kas'; },
				messageTop: "Periode <?php echo date('d F Y',strtotime($tglawal));?> S/D <?php echo date('d F Y',strtotime($tglakhir));?>"
			},
			{
				extend:'excelHtml5',
				title: function () { return 'Laporan Kas'; },
				messageTop: "Periode <?php echo date('d F Y',strtotime($tglawal));?> S/D <?php echo date('d F Y',strtotime($tglakhir));?>"
				// exportOptions:{
				// 	columns: ':visible'
				// }
			}
		]
	});
});
</script>