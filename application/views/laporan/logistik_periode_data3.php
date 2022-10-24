<table class="table table-bordered" style="width:100%;font-size:12px;" id="tbl_data">
	<thead>
		<tr>
			<th colspan="8" class="text-center text-uppercase">Laporan Logistik Stock Barang<?php //echo $saldoawal['nama_item'];?></th>
		</tr>
		<tr>
            <th colspan="8" class="text-center text-uppercase">Periode <?php echo date('d F Y',strtotime($tglawal));?> S/D <?php echo date('d F Y',strtotime($tglakhir));?></th>
		</tr>
		<tr>
			<th class="text-uppercase text-center" style="width:50px;">NO</th>
			<th class="text-uppercase text-center">KODE/ITEM</th>
			<th class="text-uppercase text-center">SATUAN</th>
			<th class="text-uppercase text-center">SA(awal)</th>
            <th class="text-uppercase text-center">MASUK</th>
			<th class="text-uppercase text-center">KELUAR</th>
			<th class="text-uppercase text-center">SA(akhir)</th>
		</tr>
	</thead>
	<tbody>
		<?php
        if(count($saldoawal) > 0):
			$no= 0;
            $saw = 0;
			
            foreach($saldoawal as $sa) {               
                $qtyklr = 0;
                $qtymsk = 0;
                $kredit_all = 0;
                $debet_all = 0;
                $Rsa = 0;
                $debet = 0;
                $kredit = 0;
                $sld_item = 0;

                $saw = $sa->jumlah;

                // Verifikasi saldoawal dengan data transaksi
                foreach($transaksi_all as $trs_all){
                    if($sa->kd_item == $trs_all->kd_item){
                        if($trs_all->qty_masuk > 0){
                            $debet_all = $debet_all+$trs_all->qty_masuk;
                        }
        
                        if($trs_all->qty_keluar > 0){
                            $kredit_all = $kredit_all+$trs_all->qty_keluar;
                        }
                    }
                }
                foreach($saldoitem as $saitm){
                    if($sa->kd_item == $saitm->kd_item){
                        $sld_item = $saitm->jumlah;
                    }
                }
                $Rsa = ($sld_item+$kredit_all)-$debet_all;
                //Jika Rsa = 0 maka nilainya dikembalikan ke saw
                if($Rsa == 0 || $Rsa < $saw){
                    $Rsa = $saw;
                }

                // Hitung transaksi sebelum tanggal
                foreach($transaksi_old as $trs_old){
                    // Hitung debet atau kredit
                    if($sa->kd_item == $trs_old->kd_item){
                        if($trs_old->qty_masuk > 0){
                            $debet = $debet+$trs_old->qty_masuk;
                        }
        
                        if($trs_old->qty_keluar > 0){
                            $kredit = $kredit+$trs_old->qty_keluar;
                        }
                    }
                }
                $saw = $Rsa + $debet - $kredit;
                
                //Hitung kredit debet berdasarkan tanggal
                foreach($transaksi as $trs): if($sa->kd_item == $trs->kd_item):
                    $qtymsk = $qtymsk + $trs->qty_masuk;
                    $qtyklr = $qtyklr + $trs->qty_keluar;
                
                    //$saw = $saw+$trs->qty_masuk-$trs->qty_keluar;
                      
                endif; endforeach;
                
                //if($saw > 0 || ($saw == 0 && $qtymsk > 0)): ?>
                <tr class="table-danger">
                    <td class="text-right"><?php $no++; echo $no;?></td>
                    <td><?php echo $sa->nama_item." / ".$sa->kd_item;?></td>
                    <td><?php echo $sa->nama_satuan;?></td>
                    <td>
                        <?php 
                            echo $saw;
                        ?>
                    </td>
                    <td><?php echo $qtymsk; ?></td>
                    <td><?php echo $qtyklr; ?></td>
                    <td>
                        <?php 
                            echo $saw = $saw + $qtymsk - $qtyklr;
                        ?>
                    </td>
                </tr>
            <?php //endif;?>
                    
            <?php } endif;?>
	</tbody>
</table>

<script type="text/javascript">
$(document).ready(function(){
	$('#tbl_data').DataTable({
		"ordering":false,
		"dom": 'Bfrtip',
        "paging" : false,
		"buttons": [
			{
				extend:'print',
				title: function () { return 'Laporan Logistik Barang Masuk-Keluar'; },
				messageTop: "Periode <?php echo date('d F Y',strtotime($tglawal));?> S/D <?php echo date('d F Y',strtotime($tglakhir));?>"
			},
			{
				extend:'excelHtml5',
				title: function () { return 'Laporan Logistik Barang Masuk-Keluar'; },
				messageTop: "Periode <?php echo date('d F Y',strtotime($tglawal));?> S/D <?php echo date('d F Y',strtotime($tglakhir));?>"
				// exportOptions:{
				// 	columns: ':visible'
				// }
			}
		]
	});
});
</script>