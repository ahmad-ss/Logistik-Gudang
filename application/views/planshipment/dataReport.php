<table class="table table-bordered" id="tbl_plreport" style="width:100%;font-size:12px;"> 
    <thead>
        <tr>
            <th class="text-uppercase" style="font-size:10pt;">#</th>
            <!-- data matrial DESKRIPTION as head -->
            <?php
                foreach($matrial as $m){ ?>
                    <th class="text-uppercase text-center" style="font-size:10pt;"><?= $m->DESKRIPTION ?></th>
            <?php } ?>
            <th class="text-uppercase" style="font-size:10pt;">Total</th>
        </tr>
    </thead>
    <tbody>
        <!-- Tempat datatabel data -->
        <?php $last = count($planshipment); $j = 0; 
            $kditml = null; foreach($planshipment as $p): $kditm = $p->kd_item;
            if($kditm != $kditml):
        ?>
            <tr>
                <td><?= $p->nama_item ?></td>
            <?php $i=0; $TBtotal = 0; foreach($matrial as $m): $isi=0;?>
                    <?php foreach($planshipment as $pl): 
                        if($m->KD_ITMBYR == $pl->KD_ITMBYR && $pl->kd_item == $kditm):
                        $isi++;
                    ?>
                    <td><?= $pl->Btotal ?></td>
                    <?php $TBtotal = $TBtotal + $pl->Btotal; ?>
                    <?php endif; endforeach;  $i++; ?>
                    <?php if($isi == 0): ?>
                        <td></td>
                    <?php endif; if($i == count($matrial)): ?>
                        <td><?= $TBtotal ?></td>
                    <?php endif;?>
            <?php endforeach;  endif; ?>
            </tr>
            <?php if(++$j == $last):?>
                <tr><td>Jumlah Kirim</td>
                <?php $i=0; $TKirim = 0; $kditmbyrL = null; 
                    foreach($matrial as $m2): $Kirim = 0; $idPlnsL= null;?>
                    <?php foreach($planshipment as $pl2): ?>
                        <?php if($pl2->KD_ITMBYR == $m2->KD_ITMBYR): ?>
                            <?php if($pl2->id_PalinShipment != $idPlnsL){ $Kirim = $Kirim + $pl2->Jumlah_Shipment; }?>
                        <?php $idPlnsL = $pl2->id_PalinShipment; endif;
                        endforeach; $i++;?>
                        <td><?= $Kirim ?></td>
                        <?php $TKirim = $TKirim + $Kirim; ?>
                        <?php if($i == count($matrial)): ?>
                            <td><?= $TKirim ?></td>
                        <?php endif;?>
            <?php $kditmbyrL = $m2->KD_ITMBYR; endforeach;  endif;?>
            </tr>
        <?php $kditml = $kditm; endforeach; ?>
    </tbody>
</table>

<script type="text/javascript">
$(document).ready(function(){
    var collapsedGroups = {};
    var top = '';
    var table = $('#tbl_plreport').DataTable({
        "lengthChange": false,
		"dom": 'Bfrtip',
        "paging" : false,
        "buttons" : [
            {
                extend: "print",
                orientation: "landscape",
            },
            {
                extend: "pdf",
                orientation: "landscape",
            },
            {
                extend: "excel",
            },
        ],
        "ordering": false,
    });
    
});
</script>