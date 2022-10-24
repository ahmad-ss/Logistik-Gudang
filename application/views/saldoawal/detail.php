<div class="col-md-12">
	<div id="alert-saldoawal"></div>
</div>

<div class="col-md-12">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<form id="form-tambah-saldoawal">
			<input type="hidden" name="id_saldoawal" value="<?php echo $lgs_saldoawal['id_saldoawal'];?>">
			<input type="hidden" name="id_user" value="<?php echo $this->session->userdata('id_user');?>">
			<div class="col-md-12">
				<div class="form-group">
					<label class="text-uppercase">Nama Item</label>
					<input type="text" class="form-control" name="namaitm" value="<?php echo $lgs_saldoawal['nama_item'];?>" required>
				</div>
				<div class="form-group">
					<label class="text-uppercase">Tanggal</label>
					<input type="text" class="form-control datepicker" name="tanggal" autocomplete="off" <?php if($lgs_saldoawal['tanggal'] != null){echo 'value="'.date('d-m-Y',strtotime($lgs_saldoawal['tanggal'])).'"';}?> required>
				</div>
				<div class="form-group">
					<label class="text-uppercase">Jumlah</label>
					<input type="text" class="form-control" name="saldoawal" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="<?php echo $lgs_saldoawal['jumlah'];?>" required>
				</div>
				<div class="form-group">
					<label class="text-uppercase">Satuan</label>
					<input type="text" class="form-control" name="satuan" value="<?php echo $lgs_saldoawal['nama_satuan'];?>" required>
				</div>
			</div>
			<div class="col-md-12" style="margin-top:10px;">
				<div class="text-center">
					<a href="../main/saldoawal" class="btn btn-default">Kembali</a>
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-4"></div>
</div>

<script type="text/javascript">
$(document).ready(function(){

});

function getCabang(){
	var d = {kd_cabang:$('select[name="kd_cabang"]').val()};
	$.ajax({
		url:'../saldoawal/getcabang',
		type:'POST',
		data:d,
		dataType:'JSON',
		success:function(result){
			var r = result;
			for(var i=0;i<r.length;i++){
				$('input[name="cabang"]').val(r[i].Cabang);
				$('textarea[name="alamat"]').text(r[i].Alamat);
			}
		},error:function(xhr){
			$.alert(xhr.responseText);
		}
	});
}

$("#form-tambah-saldoawal").submit(function(e){
	e.preventDefault();

	$.ajax({
		url:'../saldoawal/updateSaldoAwal',
		type:'POST',
		data:$("#form-tambah-saldoawal").serialize(),
		beforeSend:function(){
			$('#alert-saldoawal').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> Menyimpan data...</div>');
		},success:function(data){
			$('#alert-saldoawal').html(data);
			setTimeout(function(){
				location.reload(1);
			},3000);
		},error:function(xhr){
			$('#alert-saldoawal').html(xhr.responseText);
		}
	});
});
</script>