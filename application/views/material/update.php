<div class="col-md-12">
	<h2><?php echo $title;?></h2>
</div>

<div class="col-md-12">
	<div id="alert-transaksi"></div>
</div>

<div class="col-md-12">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<form id="form-update-material" method="POST">
			<input type="hidden" name="id_user" value="<?php echo $this->session->userdata('id_user');?>">
			<input type="hidden" name="id_material" value="">
			<div class="form-group">
				<label class="text-uppercase">Nama Item Material</label>
				<input type="text" class="form-control" name="material" id="material" value="" readonly>
			</div>
			<div class="form-group">
				<label class="text-uppercase">Jumlah <span id="stok"></span></label>
                <input type="number" step="0.01" class="form-control" name="jumlah" id="jumlah" value="" onchange="checkInput()">
			</div>
			<div class="col-md-12" style="margin-top:10px;">
				<div class="text-center">
					<a href="../main/material" class="btn btn-default">Kembali</a>
					<button type="sbumit" class="btn btn-primary">Simpan</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-4"></div>
</div>

<script type="text/javascript">
$(document).ready(function(){ 
    $.ajax({
        url: "../material/dataMaterialById",
        type: "GET",
        data: {id: <?php echo $_GET['id'];?>},
        dataType: "JSON",
        success: function(data){
            $('input[name="material"]').val(data.nama_item);
            $('input[name="jumlah"]').val(data.jumlah);
            $('input[name="id_material"]').val(<?php echo $_GET['id'];?>);
			getItem(data.kd_item);
        }
    });
});

function getItem(kditm){ 
	var data = {
		kditm: kditm
	};
    $.ajax({
        url: "../material/getDataItem",
        type: "GET",
        data: data,
        dataType: "JSON",
        success: function(data){
            $('#stok').html('<label class="label label-primary">QTY '+data[0].jumlah+'</label>');
		}
	});
}
</script>

<script type="text/javascript">

function presubmitForm(){
	$.confirm({
	    title: 'Konfirmasi!',
	    content: 'Apakah Anda yakin menyimpan data ini ?',
	    icon: 'fa fa-exclamation',
	    animation: 'scale',
	    closeAnimation: 'zoom',
      	buttons: {
	        Simpan: {
		        //text: '',
		        btnClass: 'btn-blue',
		        action: function(){
		        	submitForm();
		        }
	        },
	        Tidak: function(){
	            
	        }
      	}
  	});
}

function submitForm(){
    var formData = new FormData($('#form-update-material')[0]);
    $.ajax({
	    url: "../material/updateMaterial",
	    type: "POST",
	    data : formData,
	    processData: false,
	    contentType: false,
		beforeSend:function(){
			$('#alert-transaksi').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> Menyimpan data...</div>');
		},success:function(data){
			$('#alert-transaksi').html(data);
			setTimeout(function(){
			 	location.reload(1);
			},3000);
		},error:function(xhr){
			$('#alert-transaksi').html(xhr.responseText);
		}
	});
}

$("#form-update-material").submit(function(e){
	e.preventDefault();

	presubmitForm();
});
</script>