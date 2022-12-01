<table class="table table-bordered" id="tbl_plreport" style="width:100%;font-size:12px;"> 
    <thead>
        <tr>
            <th class="text-uppercase">No.</th>
            <th class="text-uppercase text-center">Tanggal Pengiriman</th>
            <th class="text-uppercase text-center">Barang</th>
            <th class="text-uppercase text-center">Material</th>
            <th class="text-uppercase text-center">Jumlah</th>
            <th class="text-uppercase text-center">Sub Total</th>
            <th class="text-uppercase text-center">Satuan</th>
        </tr>
    </thead>
    <tbody>
        <!-- Tempat datatabel data -->
        <?php $no=1; ?>
            <?php foreach($d_planshipment as $dplan): ?>
            <tr>
                <?php
                    if(!isset($barang) || $barang != $dplan->DESKRIPTION || !isset($tgl) || $tgl != $dplan->Tgl_Shipment){
                        $no=1;
                    }
                ?>
                <td class="text-right"><?= $no++ ?></td>
                <td><?= $dplan->Tgl_Shipment ?></td>
                <td><?= $dplan->DESKRIPTION ?></td>
                <td><?= $dplan->nama_item ?></td>
                <td class="text-right"><?= $dplan->jumlah_Material ?></td>
                <td class="text-right"><?= $dplan->Sub_Total ?></td>
                <td><?= $dplan->nama_satuan ?></td>
            </tr>
            <?php $tgl = $dplan->Tgl_Shipment; $barang = $dplan->DESKRIPTION; endforeach; ?>
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
        "columnDefs": [
            { "visible": false, "targets": [1,2] },
            { "orderable": false, "targets": [0,3,4,5,6]}
        ],
        "order": [[ 1, 'asc' ]],
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            var last2=null;
            var no = 1;
            var lastTgl = null;

            //console.log(api.rows(1).data()[0][0]);
 
            api.column( 1, {page:'current'} ).data().each( function ( group, i) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><th colspan="4">Tanggal Pengiriman : '+group+'</th></tr>'
                    );
                }
                last = group;
            } );
            
            api.column(2, {page:'current'} ).data().each( function ( group, i ) {
                // kondisi jika tanggal pengiriman tidak sama
                if(lastTgl !== api.rows(i).data()[0][1] && lastTgl !== null){
                    last2 = null;
                }
                // print group barang
                if ( last2 !== group ) {
                    $(rows).eq( i ).before(
                        '<tr data-name="'+group+'" class="group collapsed group-start"><th>'+(no++)+'</th><th colspan="3">'+group+'</th></tr>'
                    );
                    lastTgl = api.rows(i).data()[0][1];
                }
                // kondisi jika group di collapse
                var collapsed = !!collapsedGroups[group];
                if ( collapsed ) {
                    $(rows).eq( i ).hide();
                }else{
                    $(rows).eq( i ).show();
                }
                last2 = group;
            } );
        }
    } );
        $('#tbl_plreport tbody').on('click', 'tr.group-start', function() {
            var name = $(this).data('name');
            collapsedGroups[name] = !collapsedGroups[name];
            table.draw(false);
        });
});
</script>