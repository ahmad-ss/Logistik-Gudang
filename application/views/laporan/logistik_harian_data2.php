<table class="table table-bordered" style="width:100%;font-size:12px;" id="tbl_data">
	<thead>
		<tr>
			<th colspan="8" class="text-center text-uppercase">KARTU STOCK<?php echo $kdinp;?></th>
		</tr>
		<tr>
			<th colspan="8" class="text-left text-uppercase">PERIODE : <?php echo date('d F Y',strtotime($tgl)) . " - ".date('d F Y',strtotime($tglakr));?></th>
		</tr>
        <tr>
			<th colspan="8" class="text-left text-uppercase"><?php echo "KODE : ".$sldawl['kd_item']."  | ITEM : ".$sldawl['nm_item']."  SALDO :".$sldawl['jumlah']." ".$sldawl['satuan']; ?></th>
		</tr>
        <!-- <tr>
			<th colspan="8" class="text-right text-uppercase">
                <?php
                    foreach($saldoawal as $sa){
                        echo $sa->kd_item."-".$sa->nama_item;
                    }
                ?>
            </th>
		</tr> -->
		<tr>
			<th class="text-uppercase text-center" style="width:50px;">NO</th>
			<th class="text-uppercase text-center" style="width:80px;">Tanggal</th>
			<!-- <th class="text-uppercase text-center">ITEM</th> -->
			<th class="text-uppercase text-center">KETERANGAN</th>
			<th class="text-uppercase text-center">QTY MASUK</th>
			<th class="text-uppercase text-center">QTY KELUAR</th>
			<!-- <th class="text-uppercase text-center">SATUAN</th> -->
			<th class="text-uppercase text-center">SALDO</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no= 0;
			$debet = 0;
			$kredit = 0;
            foreach($saldoawal as $sa) { if($saldoawal != null):
                $saw = $sa->jumlah;
                foreach($transaksi_old as $trs_old){
                    // Hitung debet atau kredit
                    if($sa->kd_item == $trs_old->kd_item){
                        if($trs_old->qty_masuk > 0){
                            $debet = $trs_old->qty_masuk;
                            $saw = $saw+$debet;
                        }
        
                        if($trs_old->qty_keluar > 0){
                            $kredit = $trs_old->qty_keluar;
                            $saw = $saw-$kredit;
                        }
                    }
                }
                //if($saw > 0): $no++;?>
                <!-- <tr class="table-dark">
                    <td class="text-right"><?php //echo $no;?></td>
                    <td class="text-center"><?php //if($sa->tanggal != null){echo date('d/m/y',strtotime($sa->tanggal));}?></td>
                    <td><?php //echo $sa->nama_item;?></td>
                    <td>SETUP QTY BARANG AWAL</td>
                    <td></td>
                    <td></td>
                    <td><?php //echo $sa->nama_satuan;?></td>
                    <td class="text-right">
                        <?php 
                            //echo number_format($saw,0,',','.');
                        ?>
                    </td>
                </tr> -->
            <?php //endif;?>                
                <?php foreach($transaksi as $trs): if($sa->kd_item == $trs->kd_item): $no++;?>
                    <tr class="table-dark">
                        <td class="text-right"><?php echo $no;?></td>
                        <td class="text-center"><?php if($trs->tanggal != null){echo date('d/m/y',strtotime($trs->tanggal));}?></td>
                        <!-- <td><?php //echo $trs->nama_item;?></td> -->
                        <td><?php echo $trs->keterangan;?></td>
                        <td><?php echo $trs->qty_masuk;?></td>
                        <td><?php echo $trs->qty_keluar;?></td>
                        <!-- <td><?php //echo $sa->nama_satuan;?></td> -->
                        <td class="text-right">
                            <?php 
                                echo $saw=$saw+$trs->qty_masuk-$trs->qty_keluar;
                            ?>
                        </td>
                    </tr>
                <?php endif; endforeach;?>
            <?php endif; } ?>
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
				title: function () { return 'Laporan Logistik Barang Masuk-Keluar'; },
				messageTop: "Tanggal <?php echo date('d F Y',strtotime($tgl));?>"
			},
			{
				extend:'excelHtml5',
				title: function () { return 'Laporan Logistik Barang Masuk-Keluar'; },
				messageTop: "Tanggal <?php echo date('d F Y',strtotime($tgl));?>"
				// exportOptions:{
				// 	columns: ':visible'
				// }
			}
		]
	});
});
</script>