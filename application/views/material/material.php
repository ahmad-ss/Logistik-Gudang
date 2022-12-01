<div class="col-md-12">
<h2>Daftar Material Item</h2>
</div>

<div class="col-md-12">
<div class="col-md-12">
    <div id="alert_trs"></div>	
</div>

<div class="col-md-12">
    <a class="btn btn-default" href="../main/materialAdd">Tambah <i class="glyphicon glyphicon-plus"></i></a>
</div>

<input type="hidden" id="id_user" value="<?php echo $this->session->userdata('id_user');?>">

<div class="col-md-12" style="margin-top:10px;">
    <div class="table-responsive">
        <table class="table table-bordered" id="tbl_mtr" style="font-size:12px;"> 
            <thead>
                <tr>
                    <th class="text-uppercase text-center">No.</th>
                    <th class="text-uppercase text-center">Barang</th>
                    <th class="text-uppercase text-center">Material</th>
                    <th class="text-uppercase text-center">Jumlah</th>
                    <th class="text-uppercase text-center">Satuan</th>
                    <th class="text-uppercase text-center">Update</th>
                    <th class="text-uppercase text-center">Delete</th>
                </tr>
            </thead>
            <tbody>
                <!-- Tempat datatabel data -->
            </tbody>
        </table>
    </div>
</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    var collapsedGroups = {};
    var table = $('#tbl_mtr').DataTable({
        "processing": true,
        "ajaxSource": '../material/dataMaterial',
        "lengthChange": false,
        "displayLength":  10 ,
        "order": [[ 1, 'asc' ]],
        "columnDefs": [
            { "bVisible": false, "aTargets": [ 1 ] },
            { "bSortable": false, "aTargets": [ 0,2,3,4,5 ] },
            { "aTargets": [ 5,6 ], "sClass": "text-center" },
            { "aTargets": [ 0,3 ], "sClass": "text-right" }
        ],
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                // print group barang
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr data-name="'+group+'" class="group collapsed group-start"><th colspan="5">'+group+'</th></tr>'
                    );
                }
                // kondisi jika group di collapse
                var collapsed = !!collapsedGroups[group];
                if ( collapsed ) {
                    $(rows).eq( i ).hide();
                }else{
                    $(rows).eq( i ).show();
                }
                last = group;
            } );
        },
    });
    $('#tbl_mtr').on('click', 'tr.group-start', function() {
            var name = $(this).data('name');
            console.log(name);
            collapsedGroups[name] = !collapsedGroups[name];
            table.draw(false);
    });
} );
</script>

<script type="text/javascript">

function preHps(id){
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
		        	hpsData(id);
		        }
	        },
	        Tidak: function(){
	            
	        }
      	}
  	});
}

function hpsData(id){
	var d = {
		id:id
	};
	
	$.ajax({
		url:'../material/deleteMaterial',
		type:'POST',
		data:d,
		beforeSend:function(){
			$('#alert_trs').html('<div class="alert alert-warning">Menghapus data...</div>');
		},
		success:function(data){
			$('#alert_trs').html(data);
			reloadDataTable();
		},
		error:function(xhr){
			$('#alert_trs').html(xhr.responseText);
		}
	});
}
function reloadDataTable(){
    $('#tbl_mtr').DataTable().ajax.reload();
}
</script>