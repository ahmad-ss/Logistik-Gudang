<div class="col-md-12">
	<h2>Tambah Transaksi</h2>
</div>

<div class="col-md-12">
	<div id="alert-transaksi"></div>
	<!-- <br><p id="par"></p> -->
</div>

<div class="col-md-12">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<form id="form-tambah-transaksi" method="POST">
			<input type="hidden" name="id_user" value="<?php echo $this->session->userdata('id_user');?>">
			<div class="jumbotron">
				<h5># Transaksi</h5>
			<div class="">
				<div class="form-group">
					<label class="text-uppercase">Tanggal</label>
					<input type="text" class="form-control datepicker" name="tanggal" value="" autocomplete="off"  required>
				</div>
				<div class="form-group">
					<label class="text-uppercase">Keterangan</label>
					<textarea class="form-control" name="keterangan" style="height:100px;resize:none;"></textarea>
				</div>
				<div class="form-group">
					<label class="text-uppercase">Nomor Bukti</label>
					<input type="text" class="form-control" name="no_bukti" value="" >
					<input type="file" class="form-control" name="foto_bukti" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps">
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12">
							<label class="text-uppercase">Nama Team</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
						<select class="form-control" name="team" onchange="autoVal();" required>
							<option value="">(Pilih)</option>
							<option value="CPWM">Team CPWM</option>
							<option value="Alfonso">Team Alfonso</option>
							<option value="Santo">Team Santo</option>
							<option value="Jogja">Team Jogja</option>
							<option value="LBA">Team LB Atas</option>
							<option value="Supplier">Supplier</option>
							<option value="TBM">TBM</option>
							<option value="Umum">Umum</option>
							<option value="Bengkel">Bengkel</option>
							<option value="Sample">Sample</option>
						</select>
						</div>
						<div class="col-md-6">
							<select class="form-control" name="debet_kredit" onchange="checkSaldoAwal(0);" readonly>
								<option value="">(Pilih)</option>
								<option value="qtymsk">Qty Masuk</option>
								<option value="qtyklr">Qty Keluar</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			</div>
			<div id="contain">
				<div class="jumbotron" id="isi0">
					<h5 id="header-isi"><b>#1</b></h5>
					<input id="rmv" onclick="removeisi(0)" type="button" class="btn btn-danger" value="Hapus" disabled>
					<div class="form-group">
						<label class="text-uppercase">Nama Item</label>
						<select class="selectpicker form-control" id="kditm" name="namaitm[]" onchange="setSaldoAwal(0);" data-live-search="true" required>
							<option value="0">(Pilih)</option>
								<?php foreach($item as $itm):?>
							<option value="<?php echo $itm->kd_item;?>"><?php echo $itm->kd_item.' | '.$itm->nama_item;?></option>
							<?php endforeach;?>
						</select>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-12">
								<label class="text-uppercase">Jumlah <span id="saldoawal_debet_kredit"></span></label>
							</div>
						</div>
						<div id="nomdok" class="row">
							<div class="col-md-6">
								<input id="nominal" type="text" class="form-control" name="nom_debet_kredit[]" value="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" onchange="checkSaldoAwal(0);" required>
							</div>
							<small id="small_debet_kredit"></small>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12" style="margin-top:10px;">
				<div class="text-left">
					<input onclick="tambahtrs(1)" type="button" id="tmbh" name="tmbh" value="Tambah Transaksi" class="btn btn-default"></input>
				</div>
			</div>
			<div class="col-md-12" style="margin-top:10px;">
				<div class="text-center" name="tombol">
					<a href="../main/transaksi" class="btn btn-default">Kembali</a>
					<input onclick="saveTemp()" type="button" class="btn btn-primary" value="Simpan">
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-2"></div>
</div>
<!-- <div class="row" style="height: 50px;"></div> -->
<br><p id="par"></p>

<script type="text/javascript">

function removeisi(i) {
	$('#isi'+i).remove();

	//first div id = isi0
	//$('#contain > div:first-child').attr('id', 'isi0');

	var numOfChild = $('#contain > div').length;
	if(numOfChild == 1){
		$('#contain > div:nth-child('+ (numOfChild) +') input:first-of-type').attr('disabled', true);
	}
	//rubah index onchange selectitm nominal dan ubah h5
	for(var j=0; j<numOfChild; j++){
		//rubah h5 header isi
		$('#contain > div:nth-child('+ (j+1) +') > h5').html('<b>#'+(j+1)+'</b>');
		//rubah id div child #contain
		$('#contain > div:nth-child('+ (j+1) +')').attr('id', 'isi'+j);
		//rubah onchange selectitm
		$('#isi'+j+' #kditm').attr('onchange', 'setSaldoAwal('+j+')');
		//rubah onchange nominal
		$('#isi'+j+' #nominal').attr('onchange', 'checkSaldoAwal('+j+')');
		//rubah onclick remove
		$("#isi"+j+" > #rmv").attr('onclick', 'removeisi('+j+')');
	}
	$("#tmbh").attr('onclick', 'tambahtrs('+numOfChild+')');
	
	//ujicoba
	//$('#par').html(numOfChild);
}

function autoVal(){

	var team = document.getElementsByName('team')[0];
	if(team.value == "Supplier"){
		document.getElementsByName('debet_kredit')[0].value = "qtymsk";
		//$('#par').html('MASUK');
	}else if(team.value != 'Supplier' && team.value != ''){
		document.getElementsByName('debet_kredit')[0].value = "qtyklr";
		//$('#par').html(team.value);
	}else{
		document.getElementsByName('debet_kredit')[0].value = "";
	}

}

//var i = 1;
function tambahtrs(i){
	var selectitm = '<label class="text-uppercase">Nama Item</label><select class="selectpicker form-control iterm" data-live-search="true" id="kditm" name="namaitm[]" onchange="setSaldoAwal('+i+');" required><option value="0">(Pilih)</option><?php foreach($item as $itm):?><option value="<?php echo $itm->kd_item;?>"><?php echo $itm->kd_item.' | '.$itm->nama_item;?></option><?php endforeach;?></select>';
	var nomdok = "<div class='col-md-6'><input id='nominal' type='text' class='form-control' name='nom_debet_kredit[]'' value='0' oninput='this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');' onchange='checkSaldoAwal("+i+");'' required></div><small id='small_debet_kredit'></small>";
	const box = document.getElementById('isi0');
	const clone = box.cloneNode(true);
		if(i < 14){
			clone.id = 'isi'+i;
			//$("#isi").clone().appendTo("#contain");
			$("#contain").append(clone);
			$("#isi"+i+" > div:first-of-type").html(selectitm);
			$("#isi"+i+" #nomdok").html(nomdok);
			$("#isi"+i+" > h5").html("<b>#"+ (i+1) +"</b>");
			$("#isi"+i+" > #rmv").attr('disabled', false);
			$("#isi"+i+" > #rmv").attr('onclick', 'removeisi('+i+')');
			i++;
		}else{
			alert("Maksimal Transaksi Dalan Satu Waktu Adalah 14");
		}
		$('.selectpicker').selectpicker('refresh');
		$('#isi0 input:first-of-type').attr('disabled', false);
		$("#tmbh").attr('onclick', 'tambahtrs('+i+')');
}

function saveTemp(){
	var formData = new FormData($("#form-tambah-transaksi")[0]);
	//$("#form-tambah-transaksi").serialize()
	$.ajax({
		url:'../transaksi/tempSave',
		type:'POST',
		data : formData,
	    processData: false,
	    contentType: false,
		dataType:'json',
		beforeSend:function(){
			$('#alert-transaksi').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> Menyimpan data...</div>');
		},success:function(result){
			//console.log(result);
			var r = result;
			if(r.code == 0){
				$('#alert-transaksi').html('<div class="alert alert-success">'+r.message+'</div>');
				$('input, textarea, select').attr("readonly", true);
				$('.selectpicker').attr("disabled", true);
				$('div[name="tombol"]').html("<input type='button' class='btn btn-default' onclick='Cancelsimpan()' value='Cancel'><button class='btn btn-primary' type='submit'>Simpan</button>");
			}else{
				$('#alert-transaksi').html('<div class="alert alert-warning">'+r.message+'</div>');
			}
		//}			
		},error:function(xhr){
			$("#alert-transaksi").html("Apakah Anda Yakin Data Sudah Benar?");
			$('#alert-transaksi').html(xhr.responseText["message"]);
			$('input, textarea, select').attr("readonly", true);
			$('.selectpicker').attr("disabled", true);
			$('div[name="tombol"]').html("<input type='button' class='btn btn-default' onclick='Cancelsimpan()' value='Cancel'><button class='btn btn-primary' type='submit'>Simpan</button>");
		}
	});
}

function setSaldoAwal(v){
	var nmitm = document.getElementsByName('namaitm[]');
	//var index = $( "select" ).index( this );
	//for(var x = 0; x < nmitm.length; x++){
			var d = {
				tanggal:$('input[name="tanggal"]').val(),
				namaitm:nmitm[v].value,
				x:v
			};
	//}
	//$("#par").html(index);
		$.ajax({
			url:'../transaksi/setSaldoAwal',
			type:'POST',
			data:d,
			dataType:'JSON',
			success:function(result){
				$('#isi'+(d.x)+' #saldoawal_debet_kredit').html(result.nom);
			},error:function(xhr){
				$.alert(xhr.responseText);
			}
		});
	//}
}

function checkSaldoAwal(index){
	var nmitm = document.getElementsByName('namaitm[]');
	var dok = document.getElementsByName('debet_kredit[]');
	var ndok = document.getElementsByName('nom_debet_kredit[]');
	//for(var x = 0; x < ndok.length; x++){
		var d = {
			debet_kredit:dok[index].value,
			nom_debet_kredit:ndok[index].value,
			namaitm:nmitm[index].value,
			tanggal:$('input[name="tanggal"]').val(),
			x:index
		};
	//}
	//$("#par").html(d.x +"--"+ d.nom_debet_kredit);
	$.ajax({
		url:'../transaksi/checkSaldoAwal',
		type:'POST',
		data:d,
		dataType:'JSON',
		success:function(result){
			if(result.code != 0){
				$('#alert-transaksi').html(result.message);
				$('#isi'+(d.x)+' #small_debet_kredit').html(result.message);
				$('#isi'+(d.x)+' input[name="nom_debet_kredit[]"]').val('');
			}
		},error:function(xhr){
			$.alert(xhr.responseText);
		}
	});
}

function presubmitForm(){
	$('.selectpicker').attr("disabled", false);
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
		url:'../transaksi/saveTransaksi',
		type:'POST',
		data : formData,
	    processData: false,
	    contentType: false,
		dataType:'json',
		beforeSend:function(){
			$('#alert-transaksi').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> Menyimpan data...</div>');
		},success:function(result){
			//console.log(result);
			var r = result;

			if(r.code == 0){
				$('#alert-transaksi').html('<div class="alert alert-success">'+r.message+'</div>');
				setTimeout(function(){
				 	location.reload(1);
					// $('#alert-transaksi').html('');
					// $('input, textarea, select').val('');
					// $('input, textarea, select').attr('readonlay', false);
					// $('.selectpicker').attr('disabled', false);
					// $('div[name="tombol"]').html('<a href="../main/transaksi" class="btn btn-default">Kembali</a><input onclick="saveTemp()" type="button" class="btn btn-primary" value="Simpan">');
				},3000);
			}else{
				$('#alert-transaksi').html('<div class="alert alert-warning">'+r.message+'</div>');
			}
			
		},error:function(xhr){
			$('#alert-transaksi').html(xhr.responseText);
		}
	});
}

function Cancelsimpan(){
	$("#alert-transaksi").html(" ");
	$('input, textarea, select').attr("readonly", false);
	$('.selectpicker').attr("disabled", false);
	$('div[name="tombol"]').html('<a href="../main/transaksi" class="btn btn-default">Kembali</a><input onclick="saveTemp()" type="button" class="btn btn-primary" value="Simpan">');
}

// function submitForm(){
// 	$.ajax({
// 		url:'../transaksi/saveTransaksi',
// 		type:'POST',
// 		data:$("#form-tambah-transaksi").serialize(),
// 		dataType:'JSON',
// 		beforeSend:function(){
// 			$('#alert-transaksi').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> Menyimpan data...</div>');
// 		},success:function(result){
// 			//console.log(result);
// 			var r = result;

// 			if(r.code == 0){
// 				$('#alert-transaksi').html('<div class="alert alert-success">'+r.message+'</div>');
// 				setTimeout(function(){
// 					location.href='<?php echo base_url();?>main/transaksi';
// 				},3000);
// 			}else{
// 				$('#alert-transaksi').html('<div class="alert alert-warning">'+r.message+'</div>');
// 			}
			
// 		},error:function(xhr){
// 			$('#alert-transaksi').html(xhr.responseText);
// 		}
// 	});
// }
// var k = "The respective values are :";
//             var input = document.getElementsByName('namaitm[]');
  
//             for (var i = 0; i < input.length; i++) {
//                 var a = input[i];
//                 k = k + "array[" + i + "].value= "
//                                    + a.value + " ";
//             }
// 			$("#par").html(k);

$("#form-tambah-transaksi").submit(function(e){
	e.preventDefault();

	presubmitForm();
});
</script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/javascript">
            function isi_otomatis(){
                var nim = $("#nim").val();
                $.ajax({
                    url: 'ajax.php',
                    data:"nim="+nim ,
                }).success(function (data) {
                    var json = data,
                    obj = JSON.parse(json);
                    $('#nama').val(obj.nama);
                    $('#jeniskelamin').val(obj.jeniskelamin);
                    $('#jurusan').val(obj.jurusan);
                    $('#notelp').val(obj.notelp);
                    $('#email').val(obj.email);
                    $('#alamat').val(obj.alamat);
                });
            }
        </script>   -->