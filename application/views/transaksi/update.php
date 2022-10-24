<div class="col-md-12">
	<h2><?php echo $title;?></h2>
</div>

<div class="col-md-12">
	<div id="alert-transaksi"></div>
</div>

<div class="col-md-12">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<form id="form-tambah-transaksi" method="POST">
			<input type="hidden" name="id_user" value="<?php echo $this->session->userdata('id_user');?>">
			<input type="hidden" name="id_transaksi" value="<?php echo $lgs_transaksi['id_transaksi'];?>">
			<div class="form-group">
				<label class="text-uppercase">Keterangan</label>
				<textarea class="form-control" name="keterangan" style="height:100px;resize:none;"><?php echo $lgs_transaksi['keterangan'];?></textarea>
			</div>
			<div class="form-group">
				<label class="text-uppercase">Nomor Bukti</label>
				<input type="text" class="form-control" name="no_bukti" value="<?php echo $lgs_transaksi['no_bukti'];?>">
				<?php if($lgs_transaksi['foto_bukti'] != null):?>
					<img src="<?php echo base_url();?>assets/foto_bukti/<?php echo $lgs_transaksi['foto_bukti'];?>" style="width:30%;" />
				<?php endif;?>
				<input type="file" class="form-control" name="foto_bukti" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps">
			</div>
			<div class="col-md-12" style="margin-top:10px;">
				<div class="text-center">
					<a href="../main/transaksi" class="btn btn-default">Kembali</a>
					<button type="submit" class="btn btn-primary">Simpan</button>
					<button type="button" class="btn btn-danger" onclick="preHps(<?php echo $lgs_transaksi['id_transaksi'];?>);"><i class="glyphicon glyphicon-trash"></i> Hapus</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-4"></div>
</div>

<script type="text/javascript">
$(document).ready(function(){

});

function setSaldoAwal(){
	var d = {
		tanggal:$('input[name="tanggal"]').val(),
		cabang:$('select[name="kd_cabang"]').val()
	};

	$.ajax({
		url:'../transaksi/setSaldoAwal',
		type:'POST',
		data:d,
		dataType:'JSON',
		success:function(result){
			
			$('#saldoawal_debet_kredit').html(result.nom);
			
		},error:function(xhr){
			$.alert(xhr.responseText);
		}
	});
}

function checkSaldoAwal(){
	var d = {
		debet_kredit:$('select[name="debet_kredit"]').val(),
		nom_debet_kredit:$('input[name="nom_debet_kredit"]').val(),
		cabang:$('select[name="kd_cabang"]').val(),
		tanggal:$('input[name="tanggal"]').val()
	};

	$.ajax({
		url:'../transaksi/checkSaldoAwal',
		type:'POST',
		data:d,
		dataType:'JSON',
		success:function(result){
			if(result.code != 0){
				$('#alert-transaksi').html(result.message);
				$('#small_debet_kredit').html(result.message);
				$('input[name="nom_debet_kredit"]').val('');
			}
		},error:function(xhr){
			$.alert(xhr.responseText);
		}
	});
}

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
	var formData = new FormData($("#form-tambah-transaksi")[0]);
	$.ajax({
	    url: "../transaksi/updTransaksi",
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
			},5000);
		},error:function(xhr){
			$('#alert-transaksi').html(xhr.responseText);
		}
	});
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
		id_user:$('input[name="id_user"]').val()
	};
	
	$.ajax({
		url:'../transaksi/hpsData',
		type:'POST',
		data:d,
		beforeSend:function(){
			$('#alert-transaksi').html('<div class="alert alert-warning">Menghapus data...</div>');
		},
		success:function(data){
			$('#alert-transaksi').html(data);
			setTimeout(function(){location.href="../main/transaksi";},3000);
		},
		error:function(xhr){
			$('#alert-transaksi').html(xhr.responseText);
		}
	});
}
// // function submitForm(){
// // 	//var formData = new FormData($("#form-tambah-transaksi")[0]);
// // 	$.ajax({
// // 	    url: "../transaksi/updTransaksi",
// // 	    type: "POST",
// // 	    data : $("#form-tambah-transaksi").serialize(),
// // 	    processData: false,
// // 	    contentType: false,
// // 	    dataType:'JSON',
// // 		beforeSend:function(){
// // 			$('#alert-transaksi').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> Menyimpan data...</div>');
// // 		},success:function(data){
// // 			$('#alert-transaksi').html(data);
// // 			setTimeout(function(){
// // 				location.reload(1);
// // 			},3000);
			
// // 		},error:function(xhr){
// // 			$('#alert-transaksi').html(xhr.responseText);
// // 		}
// // 	});
// // }
// $("#form-tambah-transaksi").submit(function(e){
// 	e.preventDefault();

// 	$.ajax({
// 		url:'../transaksi/updTransaksi',
// 		type:'POST',
// 		data:$("#form-tambah-transaksi").serialize(),
// 		beforeSend:function(){
// 			$('#alert-transaksi').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> Menyimpan data...</div>');
// 		},success:function(data){
// 			$('#alert-saldoawal').html(data);
// 			setTimeout(function(){
// 				location.reload(1);
// 			},3000);
// 		},error:function(xhr){
// 			$('#alert-transaksi').html(xhr.responseText);
// 		}
// 	});
// });
$("#form-tambah-transaksi").submit(function(e){
	e.preventDefault();

	presubmitForm();
});
</script>