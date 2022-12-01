
<table class="table table-bordered" id="tbl_plan" style="width:100%;font-size:12px;"> 
    <thead>
        <tr>
            <th class="text-uppercase text-center">No.</th>
            <th class="text-uppercase text-center">Tanggal Pengiriman</th>
            <th class="text-uppercase text-center">Barang</th>
            <th class="text-uppercase text-center">Material</th>
            <th class="text-uppercase text-center">Jumlah</th>
            <th class="text-uppercase text-center">Jumlah Kirim</th>
            <th class="text-uppercase text-center">Sub Total</th>
            <th class="text-uppercase text-center">Satuan</th>
            <th class="text-uppercase text-center">Delete</th>
            <th class="text-uppercase text-center">KD_ITMBYR</th>
        </tr>
    </thead>
    <tbody>
        <!-- Tempat datatabel data -->
        <?php $no=1;?>
        <?php foreach($d_planshipment as $dplan): ?>
                <?php
                    if(!isset($barang) || $barang != $dplan->DESKRIPTION || !isset($tgl) || $tgl != $dplan->Tgl_Shipment){
                        $no=1;
                    }
                ?>
            <tr>
                <td class="text-right"><?= $no++ ?></td>
                <td><?= $dplan->Tgl_Shipment ?></td>
                <td><?= $dplan->DESKRIPTION ?></td>
                <td><?= $dplan->nama_item ?></td>
                <td class="text-right"><?= $dplan->jumlah_Material ?></td>
                <td class="text-left"><?= $dplan->Jumlah_Shipment ?></td>
                <td class="text-right"><?= $dplan->Sub_Total ?></td>
                <td><?= $dplan->nama_satuan ?></td>
                <td><?= $dplan->id_PalinShipment ?></td>
                <td><?= $dplan->KD_ITMBYR ?></td>
                <!-- <button class="btn btn-danger btn-sm" onclick="preHps(  )"><i class="glyphicon glyphicon-trash"></i></button> -->
            </tr>
        <?php $tgl = $dplan->Tgl_Shipment; $barang = $dplan->DESKRIPTION; endforeach; ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function(){
    var collapsedGroups = {};

    var table = $('#tbl_plan').DataTable({
        "lengthChange": false,
        "paging" : false,
        "columnDefs": [
            { "visible": false, "targets": [1,2,7,8,5,9] },
            { "orderable": false, "targets": [0,3,4,5,6,9] }
        ],
        "order": [[ 1, 'asc' ]],
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            var last3=null;
            var no = 1;
            var lastId = null;
 
            api.column(8, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        `<tr class="group group-start"><th colspan="3">Tanggal Pengiriman : `+api.rows(i).data()[0][1]+`</th>
                        <th class="text-center"><button class="btn btn-danger btn-sm" onclick="preHps(`+group+`)"><i class="glyphicon glyphicon-trash"></i></button></th></tr>`
                    );
                }
                last = group;
            } );
            
            api.column(2, {page:'current'} ).data().each( function ( group, i ) {
                // kondisi jika id pengiriman tidak sama
                if(lastId !== api.rows(i).data()[0][8] && lastId !== null){
                    last3 = null;
                }
                // print group barang
                if ( last3 !== group ) {
                    $(rows).eq( i ).before(
                        '<tr data-name="'+group+'" class="group collapsed group-start"><th>'+(no++)+'</th><th data-name="'+group+'" class="col-group">'+group+'</th><th>Jumlah Kirim : '+api.rows(i).data()[0][5]+'</th><th><a class="btn btn-danger btn-sm" onclick="confDelItem('+api.rows(i).data()[0][8]+',`'+api.rows(i).data()[0][9]+'`)"><i class="glyphicon glyphicon-trash"></i></a></th></tr>'
                    );
                    lastId = api.rows(i).data()[0][8];
                }
                // kondisi jika group di collapse
                var collapsed = !!collapsedGroups[group];
                if ( collapsed ) {
                    $(rows).eq( i ).show();
                }else{
                    $(rows).eq( i ).hide();
                }
                last3 = group;
            } );
        }
    } );

        $('#tbl_plan tbody').on('click', 'th.col-group', function() {
            var name = $(this).data('name');
            collapsedGroups[name] = !collapsedGroups[name];
            table.draw(false);
        });
    });
    function confDelItem(id,kd){
        $.confirm({
            title: 'Konfirmasi!',
            content: 'Apakah Anda yakin akan menghapus data ini ?',
            icon: 'fa fa-exclamation',
            animation: 'scale',
            closeAnimation: 'zoom',
            buttons: {
                Hapus: {
                    //text: '',
                    btnClass: 'btn-red',
                    action: function(){
                        delItem(id,kd);
                    }
                },
                Tidak: function(){
                    
                }
            }
  	    });
    }
    function delItem(id,kd){
        var d = {
		id:id,
        kd_itmbyr:kd
        };
        $.ajax({
            url:'../planshipment/deleteItemPlanshipment',
            type:'POST',
            data:d,
            beforeSend:function(){
                $('#alert_trs').html('<div class="alert alert-warning">Menghapus data...</div>');
            },
            success:function(data){
                var d = JSON.parse(data);
                console.log(d);
                $('#alert_trs').html('<div class="alert alert-warning">'+d.message+'</div>');
                window.scrollTo(0, 0);
                setTimeout(() => {
                    location.reload(1);
                }, 2000);
            },
            error:function(xhr){
                $('#alert_trs').html(xhr.responseText);
            }
        });
    }
</script>