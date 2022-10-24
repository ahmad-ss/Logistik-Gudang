<div class="col-md-12">
	<h2>Dashboard Logistik</h2>
</div>

<div class="col-md-12">
	<div class="table-responsive">
		<table class="table table-bordered" id="tbl_saldo" style="font-size:12px;">
			<thead>
				<tr>
					<th class="text-uppercase text-center">Kode Item</th>
					<th class="text-uppercase text-center">Item</th>
					<th class="text-uppercase text-center">Jumlah</th>
					<th class="text-uppercase text-center">Satuan</th>
					<th class="text-uppercase text-center">History Input</th>
					<th class="text-uppercase text-center">History Update</th>
					<!-- <th class="text-uppercase text-center">History Update</th> -->
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
function dataTable(){
	$('#tbl_saldo').DataTable({ 
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
            "url": "../dashboard/dataLogistikItem",
            "type": "POST"
            //"type": "PUT",
			//"headers": {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}
        },
        "columnDefs":[
        	{
        		"targets":[2],
        		"className":"text-right"
        	}
        ]
    });

    var t=$('#tbl_saldo').DataTable();

    $('#tbl_saldo input').on('input',function(){
        t.search(this.value).draw();
    });
}

function reloadDataTable(){
    $('#tbl_saldo').DataTable().ajax.reload();
}

$(document).ready(function(){

	dataTable();

});
</script>