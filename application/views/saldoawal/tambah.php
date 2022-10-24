<div class="col-md-12">
	<h2>Tambah Item</h2>
</div>

<div class="col-md-12">
	<div id="alert-saldoawal"></div>
</div>

<div class="col-md-12">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<form id="form-tambah-saldoawal">
			<input type="hidden" name="id_user" value="<?php echo $this->session->userdata('id_user');?>">
			<!-- <div class="col-md-12">
				<div class="form-group">
					<label class="text-uppercase">Kode Cabang</label>
					<select class="form-control" name="kd_cabang" onchange="getCabang();" required>
						<option value="">(Pilih)</option>
						<?php foreach($cabang as $cab):?>
						<option value="<?php echo $cab->Kd_Cabang;?>"><?php echo $cab->Kd_Cabang.' | '.$cab->Cabang;?></option>
						<?php endforeach;?>
					</select>
				</div> -->
				<div class="form-group">
					<label class="text-uppercase">Nama Item</label>
					<input type="text" class="form-control" name="namaitm" onchange="checkItem();" required>
				</div>
				<div class="form-group">
					<label class="text-uppercase">Tanggal</label>
					<input type="text" class="form-control datepicker" name="tanggal" autocomplete="off" required>
				</div>
				<div class="form-group">
					<label class="text-uppercase">Jumlah</label>
					<input type="text" class="form-control" name="saldoawal" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
				</div>
				<div class="form-group">
					<label class="text-uppercase">Satuan</label>
					<input type="text" class="form-control" name="satuan" list="satuan" autocomplete="off" required>
					<datalist id="satuan" name="satuan">
						<?php foreach($satuan as $sat):?>
						<option value="<?php echo $sat->nama_satuan;?>">
						<?php endforeach;?>
					</datalist>
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
		url:'../saldoawal/getsatuan',
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
		url:'../saldoawal/saveSaldoAwal',
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

function checkItem(){
	var d = {
		nama_item:$('input[name="namaitm"]').val(),
	};

	$.ajax({
		url:'../saldoawal/cekItem',
		type:'POST',
		data:d,
		dataType:'JSON',
		success:function(result){
			if(result.code != 0){
				$('#alert-saldoawal').html(result.message);
				$('input[name="namaitm"]').val('');
			}
		},error:function(xhr){
			$.alert(xhr.responseText);
		}
	});
}
</script>