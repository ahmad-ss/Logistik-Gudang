<div class="col-md-12">
	<h2>Transaksi</h2>
</div>

<div class="col-md-12">
	<div class="col-md-12">
		<div id="alert_trs"></div>	
	</div>

	<div class="col-md-12">
		<a class="btn btn-default" href="../main/transaksiadd">Tambah</a>
	</div>

	<input type="hidden" id="id_user" value="<?php echo $this->session->userdata('id_user');?>">

	<div class="col-md-12" style="margin-top:10px;">
		<div class="table-responsive">
			<table class="table table-bordered" id="tbl_trs" style="font-size:12px;"> 
				<thead>
					<tr>
						<th class="text-uppercase text-center">No Transaksi</th>
						<th class="text-uppercase text-center">Tanggal</th>
						<th class="text-uppercase text-center">Keterangan</th>
						<th class="text-uppercase text-center">No. Bukti</th>
						<th class="text-uppercase text-center">Nama Item</th>
						<th class="text-uppercase text-center">Qty Masuk</th>
						<th class="text-uppercase text-center">Qty Keluar</th>
						<th class="text-uppercase text-center">Satuan</th>
						<th class="text-uppercase text-center">#</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
function dataTable(){
	$('#tbl_trs').DataTable({ 
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
            "url": "../transaksi/datatableTransaksi",
            "type": "POST"
            //"type": "PUT",
			//"headers": {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}
        },
        "columnDefs":[
        	{
        		"targets":[5,6],
        		"className":"text-right"
        	}
        ]
    });

    var t=$('#tbl_trs').DataTable();

    $('#tbl_trs input').on('input',function(){
        t.search(this.value).draw();
    });
}

function reloadDataTable(){
    $('#tbl_trs').DataTable().ajax.reload();
}

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
		id:id,
		id_user:$('#id_user').val()
	};
	
	$.ajax({
		url:'../transaksi/hpsData',
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

$(document).ready(function(){

	dataTable();

});
</script>