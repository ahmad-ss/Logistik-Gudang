<input type="hidden" id="id_user" value="<?php echo $this->session->userdata('id_user');?>">
<?php 
$user_akses = $crud->getDataWhere('users',array('id_user'=>$this->session->userdata('id_user')))->row_array();
?>
<input type="hidden" id="user_akses" value="<?php echo $user_akses['hak_akses'];?>">

<div class="col-md-12">
	<h2>Qty Awal Item</h2>
</div>

<div class="col-md-12">
	<a class="btn btn-default" href="<?php echo base_url();?>main/saldoawaladd">Tambah</a>
</div>

<div class="col-md-12" style="margin-top:10px;">
	<div class="table-responsive">
		<table class="table table-bordered" id="tbl_saldoawal" style="font-size:12px;">
			<thead>
				<tr>
					<th class="text-center text-uppercase">ID</th>
					<th class="text-center text-uppercase">Kode Item</th>
					<th class="text-center text-uppercase">Item</th>
					<th class="text-center text-uppercase">Jumlah</th>
					<th class="text-center text-uppercase">Satuan</th>
					<th class="text-center text-uppercase">Tanggal</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
// function dataTable(){
//     //datatables
//     var table = $('#tbl_saldoawal').DataTable({ 
    
// 		"processing": true, 
// 		"serverSide": true, 
// 		"order": [], 

// 		"ajax": {
// 		    "url": "../saldoawal/datatableSaldoawal",
// 		    "type": "POST"
// 		},

// 		"columnDefs": [
// 		    {
// 		        "targets": [4],
// 		        className: "text-right"
// 		    },
// 		    {
// 		        "targets": [0,1,2,3],
// 		        className: "text-center"
// 		    }
// 		],

//     });
// }

function dataTable(){
	$('#tbl_saldoawal').DataTable({ 
        "processing": true,  
        "ordering": false, 
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
        "ajax": {
            "url": "../saldoawal/datatableSaldoawal",
            "type": "POST"
            //"type": "PUT",
			//"headers": {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}
        },
        "columnDefs":[
        	{
                "targets": [0],
                "visible": false,
                "searchable": false
        	},
        	{
        		"targets":[3],
        		"className":"text-right"
        	}
        ]
    });

    var t=$('#tbl_saldoawal').DataTable();

    $('#tbl_saldoawal input').on('input',function(){
        t.search(this.value).draw();
    });
}

function dataTable0(){
	$('#tbl_saldoawal').DataTable({ 
        "processing": true,  
        "ordering": false, 
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
        "ajax": {
            "url": "../saldoawal/datatableSaldoawal0",
            "type": "POST"
            //"type": "PUT",
			//"headers": {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}
        },
        "columnDefs":[
        	{
                "targets": [0],
                "visible": false,
                "searchable": false
        	},
        	{
        		"targets":[3],
        		"className":"text-right"
        	}
        ]
    });

    var t=$('#tbl_saldoawal').DataTable();

    $('#tbl_saldoawal input').on('input',function(){
        t.search(this.value).draw();
    });
}

function reloadDataTable(){
    $('#tbl_saldoawal').DataTable().ajax.reload();
}

$(document).ready(function(){
	var user_akses = $('#user_akses').val();
	if(user_akses == 'su'){
		dataTable();
	}else{
		dataTable0();
	}

	// var table = $('#tbl_saldoawal').DataTable();
     
 //    $('#tbl_saldoawal tbody').on('click', 'tr', function () {
 //        var data = table.row( this ).data();
 //        //alert( 'You clicked on '+data[0]+'\'s row' );

 //        location.href="../main/saldoawaldetail?id="+data[0];
 //    } );
});
</script>