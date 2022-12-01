<div class="col-md-12">
<h2>Planning Report</h2>
</div>

<div class="col-md-12">
	<form id="form-plreport">
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

<div class="col-md-12">
<div class="col-md-12">
    <div id="alert_trs"></div>	
</div>

<div class="col-md-12" style="margin-top:10px;">
    <div id="tabel" class="table-responsive">
        <!-- Bagian data datatable -->
    </div>
</div>
</div>

<script type="text/javascript">
$("#form-plreport").submit(function(e){
	e.preventDefault();

	Report();
});
function Report(){
    var data = {
        tglawal : $("[name='tglawal']").val(),
        tglakhir : $("[name='tglakhir']").val()
    }
    $(document).ready(function(){
        $.ajax({
            url: "../planshipment/dataReport",
            type: "POST",
            data: data,
            success: function(data){
                $('#tabel').html(data);
            },error:function(xhr){
			    $('#alert_trs').html(xhr.responseText);
            }
        });
    });
}
</script>
