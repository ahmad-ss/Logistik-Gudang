<div class="col-md-12">
	<h2><?php echo $title;?></h2>
</div>

<div class="col-md-12">
	<div id="alert-transaksi"></div>
</div>

<div class="col-md-12">
	<div class="row">
		<form id="form-tambah-planshipment" method="POST">
			<input type="hidden" name="id_user" value="<?php echo $this->session->userdata('id_user');?>">
			
            <div class="col-md-12">
            <div class="form-group col-md-3">
                <label for="tglshipmt">Tanggal</label>
                <input type="text" class="form-control datepicker" name="tglshipmt" placeholder="(Tanggal Periode Awal)" autocomplete="off" required>
            </div>
            <div class="form-group col-md-3">
                <label for="keterangan">Keterangan</label>
                <input type="text" class="form-control" name="keterangan" placeholder="(Keterangan)" autocomplete="off" required>
            </div>
            </div>
            
            <div id="detailBarang" class="col-md12">
            <div id="barangByr" class="form-group col-md-6">
                <div class="col-md-6" id="selectkdbrg">
                    <label class="text-uppercase">Barang Buyer</label>
                    <select class="selectpicker form-control" id="kdbrg" name="kdbrg[]" data-live-search="true" onchange="getBrg(this)" required>
                        <option value="0">(Pilih)</option>
                            <?php $cr_kd; foreach($brgByr as $brg):if($cr_kd != $brg->KD_ITMBYR):?>
                        <option value="<?php echo $brg->KD_ITMBYR;?>"><?php echo $brg->KD_ITMBYR.' | '.$brg->DESKRIPTION;?></option>
                        <?php $cr_kd=$brg->KD_ITMBYR; endif; endforeach;?>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="text-uppercase">Jumlah Kirim</label>
                        </div>
                        <div class="col-md-4">
                            <!-- button hapus xs -->
                            <button id="rmv" type="button" class="btn btn-danger btn-xs" onclick="hapusBarang(this)" disabled>Hapus</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="number" class="form-control" id="jumlahkirim" name="jumlahkirim[]" value="">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nmstn" name="nmstn[]" value="" readonly>
                        </div>
                    </div>
                </div>
                <div>
                    <input type="hidden" class="form-control" name="idBrg[]" id="idBrg" value="">
                    <input type="hidden" class="form-control" name="kditmvdr[]" id="kditmvdr" value="">
                    <input type="hidden" class="form-control" name="kditmspl[]" id="kditmspl" value="">
                    <input type="hidden" class="form-control" name="dimension[]" id="dimension" value="">
                    <input type="hidden" class="form-control" name="kdstnMst[]" id="kdstnMst" value="">
                    <input type="hidden" class="form-control" name="satuan[]" id="satuan" value="">
                    <input type="hidden" class="form-control" name="desc[]" id="desc" value="">
                </div>
            </div>
            </div>

                <div class="col-md-12" style="margin-top:10px; margin-bottom:20px">
                    <div class="text-center">
                        <input type="button" class="btn btn-success" value="Tambah Barang" onclick="tambahBarang()" />
                        <a href="../main/planshipment" class="btn btn-default">Kembali</a>
                        <button type="sbumit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
		</form>
	</div>
    <div class="row" id="detail"></div>
</div>

<script type="text/javascript">
function tambahBarang(){
    // button hapus remove attr disabled
    $('#rmv:first').removeAttr('disabled');
    // clone div barang
    var clone = $('#barangByr:first').clone();
    // get last index of div barang
    var index = $('#barangByr:last-of-type').index();
    var newi = index + 1;
    // set new select
    var selectBrg = '<label class="text-uppercase">Barang Buyer</label><select class="selectpicker form-control iterm" data-live-search="true" id="kdbrg" name="kdbrg[]" onchange="getBrg(this);" required><option value="0">(Pilih)</option><?php foreach($brgByr as $Brg):?><option value="<?php echo $Brg->KD_ITMBYR;?>"><?php echo $Brg->KD_ITMBYR.' | '.$Brg->DESKRIPTION;?></option><?php endforeach;?></select>';
    // set new input maksimal 20
    if(newi < 20){
        // insert clone after last div barang
        $('#barangByr:last-of-type').after(clone);
        // set last div barang select selectBrg
        $('#barangByr:last-of-type #selectkdbrg').html(selectBrg);
        // reset last div barang input
        $('#barangByr:last-of-type #jumlahkirim').val('');
        $('#barangByr:last-of-type #nmstn').val('');
        $('#barangByr:last-of-type #idBrg').val('');
        $('#barangByr:last-of-type #kditmvdr').val('');
        $('#barangByr:last-of-type #kditmspl').val('');
        $('#barangByr:last-of-type #dimension').val('');
        $('#barangByr:last-of-type #kdstnMst').val('');
        $('#barangByr:last-of-type #satuan').val('');
        $('#barangByr:last-of-type #desc').val('');
        // set last div barang selectpicker
        $('#barangByr:last-of-type .selectpicker').selectpicker('refresh');
    }else{
        alert('Maksimal 20 barang');
    }
}
function hapusBarang(obj){
    // get index of div barangByr
    var index = $(obj).parent().parent().parent().parent().index();
    var rIndex = index+1;
    // remove div barang
    $('#barangByr:nth-of-type('+rIndex+')').remove();
    // jika index last barangByr = 0 maka button hapus disabled
    var lastBrg = $('#barangByr:last-of-type').index();
    if(lastBrg == 0){
        $('#rmv').attr('disabled','disabled');
    }
}

function getBrg(obj){
    // get index of div barangByr
    var index = $(obj).parent().parent().parent().index();
    var rIndex = index+1;
    var kditmbyr = document.getElementsByName('kdbrg[]');
    // jika kditmbyr ada yang sama set val to 0
    for(var i=0; i<kditmbyr.length; i++){
        if(kditmbyr[index].value == kditmbyr[i].value && index != i){
            $('#barangByr:nth-of-type('+rIndex+') #kdbrg').selectpicker('val',0);
            $('#barangByr:nth-of-type('+rIndex+') #jumlahkirim').val('');
            alert('Barang sudah ada');
        }
    }
    var data = {
        'kditmbyr' : kditmbyr[index].value
    }
    $.ajax({
        url : '<?php echo base_url();?>index.php/material/getDataBarang',
        type : 'GET',
        data : data,
        dataType : 'json',
        success : function(data){
            $('#barangByr:nth-of-type('+rIndex+') #idBrg').val(data.barang[0].id_Brg);
            $('#barangByr:nth-of-type('+rIndex+') #kditmvdr').val(data.barang[0].KD_ITEMVDR);
            $('#barangByr:nth-of-type('+rIndex+') #kditmspl').val(data.barang[0].KD_ITEMSPL);
            $('#barangByr:nth-of-type('+rIndex+') #dimension').val(data.barang[0].DIMENSION);
            $('#barangByr:nth-of-type('+rIndex+') #kdstnMst').val(data.barang[0].Kd_Satuan_Mst);
            $('#barangByr:nth-of-type('+rIndex+') #satuan').val(data.barang[0].Satuan);
            $('#barangByr:nth-of-type('+rIndex+') #desc').val(data.barang[0].DESKRIPTION);
            $('#barangByr:nth-of-type('+rIndex+') #nmstn').val(data.barang[0].Satuan);
            detailBarang(data.detail);
        }
    });
}

function detailBarang(data){
    // buat tabel dari data
    var table = '<table id="detailBarang" class="table table-bordered table-striped table-hover">';
    table += '<thead><tr></th><th>Material</th><th>Jumlah</th><th>satuan</th></tr></thead>';
    table += '<tbody>';
    if(data.length > 0){
        for(var i=0; i<data.length; i++){
            table += '<tr>';
            table += '<td>'+data[i].nama_item+'</td>';
            table += '<td>'+data[i].jumlah+'</td>';
            table += '<td>'+data[i].nama_satuan+'</td>';
            table += '</tr>';
        }
    }else{
        table += '<tr><td colspan="3" class="text-center">Tidak ada data</td></tr>';
    }
    table += '</tbody>';
    table += '</table>';
    $('#detail').html(table);
}
</script>

<script type="text/javascript">
$('#form-tambah-planshipment').submit(function(e){
    e.preventDefault();
    
    preSubmit();
});

function preSubmit(){
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
    var formData = new FormData($('#form-tambah-planshipment')[0]);
    $.ajax({
        url: '../planshipment/addPlanshipment',
        type: 'POST',
        data: formData,
        processData: false,
	    contentType: false,
        beforeSend:function(){
			$('#alert-transaksi').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> Menyimpan data...</div>');
		},success:function(data){
            var d = JSON.parse(data);
            if(d.code == 0){
                $('#alert-transaksi').html('<div class="alert alert-success alert-dismissible" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                    '<strong>Success!</strong> '+d.message+'</div>');
                //$('#form-tambah-material')[0].reset();
            }else{
                $('#alert-transaksi').html('<div class="alert alert-danger alert-dismissible" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                    '<strong>Error!</strong> '+d.message+'</div>');
            }
            setTimeout(() => {
                //window.location = '../main/planshipment';
                location.reload();
            }, 3000);
        },error:function(xhr){
			//$('#alert-transaksi').html(xhr.responseText);
            $('#alert-transaksi').html("Gagal menyimpan data! server error");
            setTimeout(() => {
                $('#alert-transaksi').html('');
            }, 4000);
		}
    });
}

</script>