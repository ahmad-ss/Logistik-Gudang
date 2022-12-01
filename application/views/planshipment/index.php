<div class="col-md-12">
<h2>Daftar Planning Pengiriman</h2>
</div>
<div>
	<!-- load datatable rowgroup -->
	<!-- <link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>

    <link href="https://nightly.datatables.net/rowgroup/css/rowGroup.dataTables.css?_=bc3763029fa6dfaf4c947ef25f079107.css" rel="stylesheet" type="text/css" />
    <script src="https://nightly.datatables.net/rowgroup/js/dataTables.rowGroup.js?_=bc3763029fa6dfaf4c947ef25f079107"></script> -->
</div>

<div class="col-md-12">
<div class="col-md-12">
    <div id="alert_trs"></div>	
</div>

<div class="col-md-12">
    <a class="btn btn-default" href="../main/planshipmentAdd">Tambah <i class="glyphicon glyphicon-plus"></i></a>
</div>

<div class="col-md-12" style="margin-top:10px; margin-bottom: 10px;">
    <div class="table-responsive">
        <div id="tabel">
        <!-- tempat tabel planshipment -->
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    // Load getDataPlan
    $.ajax({
        url : '../planshipment/getDataPlan',
        type : 'GET',
        success: function(data){
            $('#tabel').html(data);
        }
    });
});

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
		url:'../planshipment/deletePlanshipment',
		type:'POST',
		data:d,
		beforeSend:function(){
			$('#alert_trs').html('<div class="alert alert-warning">Menghapus data...</div>');
		},
		success:function(data){
            var d = JSON.parse(data);
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