<div class="col-md-12">
	<h2><?php echo $title;?></h2>
</div>

<div class="col-md-12">
	<form id="form-laporan">
		<!-- <div class="col-md-3">
			<select class="form-control" name="kd_item" required>
				<option value="">(Pilih Item)</option>
				<?php foreach($item as $itm):?>
					<option value="<?php echo $itm->kd_item;?>"><?php echo $itm->kd_item;?> | <?php echo $itm->nama_item;?></option>
				<?php endforeach;?>
			</select>
		</div> -->
		<div class="col-md-3">
			<input type="text" class="form-control datepicker" name="tglawal" placeholder="(Tanggal Periode Awal)" autocomplete="off" required>
		</div>
		<div class="col-md-3">
			<input type="text" class="form-control datepicker" name="tglakhir" placeholder="(Tanggal Periode Akhir)" autocomplete="off" required>
		</div>
		<div class="col-md-3">
			<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
			<!-- <button class="btn btn-primary" type="button" onclick="printDiv('tbl_laporan');"><i class="glyphicon glyphicon-print"></i></button> -->
		</div>
	</form>
</div>

<div class="col-md-12" style="margin-top:10px;">
	<div class="table-responsive">
		<div id="tbl_laporan"></div>
	</div>
</div>

<script type="text/javascript">
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;

     //location.reload();
}

$(document).ready(function(){

	var form = $('#form-laporan');
	form.submit(function(e){
		e.preventDefault();
		$.ajax({
			url:'../laporan/laporanPeriode',
			type:'POST',
			data:form.serialize(),
			beforeSend:function(){
				$('#tbl_laporan').html('<div class="alert alert-warning"><p>Loading data...</p></div>');
			},
			success:function(data){
				$('#tbl_laporan').html(data);
			},
			error:function(xhr){
				$('#tbl_laporan').html(xhr.responseText);
			}
		});
	});

});
</script>