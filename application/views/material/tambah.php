<div class="col-md-12">
	<h2><?php echo $title;?></h2>
</div>

<div class="col-md-12">
	<div id="alert-transaksi"></div>
</div>

<div class="col-md-12">
    <form id="form-tambah-material" method="POST">
        <input type="hidden" name="id_user" value="<?php echo $this->session->userdata('id_user');?>">
        <div class="col-md-8 form-group">
            <label class="text-uppercase">Kode/Nama Item</label>
                    <select class="selectpicker form-control" name="kdbrg" data-live-search="true" onchange="getBrg()" required>
                        <option value="0">(Pilih)</option>
                            <?php $cr_kd; foreach($brgByr as $brg):if($cr_kd != $brg->KD_ITMBYR):?>
                        <option value="<?php echo $brg->KD_ITMBYR;?>"><?php echo $brg->KD_ITMBYR.' | '.$brg->DESKRIPTION;?></option>
                        <?php $cr_kd=$brg->KD_ITMBYR; endif; endforeach;?>
                    </select>
        </div>
        <div class="col-md-4"></div>
        <div class="form-group">
            <input type="hidden" class="form-control" name="idBrg" value="">
            <input type="hidden" class="form-control" name="kditmvdr" value="">
            <input type="hidden" class="form-control" name="kditmspl" value="">
            <input type="hidden" class="form-control" name="dimension" value="">
            <input type="hidden" class="form-control" name="kdstnMst" value="">
            <input type="hidden" class="form-control" name="satuan" value="">
            <input type="hidden" class="form-control" name="desc" value="">
        </div>
        
        <div class="col-md-12 form-group" id="itemdetail">
            <div id="km" class="form-group col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <label class="text-uppercase">Kebutuhan Material<span id="stok"></span></label>
                    </div>
                    <!-- tombol - -->
                    <div class="col-md-6">
                        <input id="rmv" type="button" class="btn btn-danger btn-xs" onclick="removeKebutuhan(0)" value="Hapus" disabled></input>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6" id="selectitm">
                        <select class="selectpicker form-control" id="kditm" name="kditm[]" data-live-search="true" onchange="preCekItm(0)" required>
                            <option value="0">(Pilih)</option>
                                <?php foreach($item as $itm):?>
                            <option value="<?php echo $itm->kd_item;?>"><?php echo $itm->kd_item.' | '.$itm->nama_item.' | '.$itm->nama_satuan;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="0.01" class="form-control" id="jumlah" name="jumlah[]" value="" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" id="nmstn" name="nmstn[]" value="" readonly>
                    </div>
                </div>
                <div>
                    <input type="hidden" class="form-control" id="nmitm" name="nmitm[]" value="">
                    <input type="hidden" class="form-control" id="iditm" name="iditm[]" value="">
                    <input type="hidden" class="form-control" id="idstn" name="idstn[]" value="">
                    <input type="hidden" class="form-control" id="kdstn" name="kdstn[]" value="">
                </div>
            </div>
        </div>
        <div class="col-md-12" style="margin-top:10px; margin-bottom:20px">
            <div class="text-center">
                <input type="button" onclick="tambahMaterial()" class="btn btn-success" value="Tambah Material"></input>
                <a href="../main/material" class="btn btn-default">Kembali</a>
                <button type="sbumit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
    <div class="col-md-12 form-group">
        <!-- tempat detail -->
        <div class="row">
            <div id="ketBrg" class="col-md-12">
                <label class="text-uppercase">Kebutuhan Material</label>
            </div>
        </div>
        <div class="row" id="detail"></div>
    </div>
</div>

<script type="text/javascript">
function tambahMaterial(){
    // set remove button enable
    $('#rmv:first').removeAttr('disabled');
    // clone div id km first index
    var clone = $('#km:first').clone();
    // get index of div km
    var index = $('div #km:last-of-type').index();
    var newi = index + 1;
    // set new select
    var selectitm = '<select class="selectpicker form-control iterm" data-live-search="true" id="kditm" name="kditm[]" onchange="preCekItm('+newi+');" required><option value="0">(Pilih)</option><?php foreach($item as $itm):?><option value="<?php echo $itm->kd_item;?>"><?php echo $itm->kd_item.' | '.$itm->nama_item;?></option><?php endforeach;?></select>';
    // append to itemdetail
    if(index < 48){
        // insert after div km last index
        $('#km:last-of-type').after(clone);
        $('#km:last-of-type #rmv').attr('onclick','removeKebutuhan('+(newi)+')');
        $('#km:last-of-type #selectitm').html(selectitm);
        // reset value
        $('#km:last-of-type #jumlah').val('');
        $('#km:last-of-type #nmstn').val('');
        $('#km:last-of-type #nmitm').val('');
        $('#km:last-of-type #iditm').val('');
        $('#km:last-of-type #idstn').val('');
        $('#km:last-of-type #kdstn').val('');
        $('#km:last-of-type #stok').html('');
        $('#km:last-of-type .selectpicker').selectpicker('refresh');
    }else{
        alert('Maksimal 50 Kebutuhan Material');
    }// refresh selectpicker
    //$('.selectpicker').selectpicker('refresh');
}
function removeKebutuhan(x){
    // remove id km index x
    var j = x+1;
    $('#km:nth-of-type('+j+')').remove();
    // loop untuk set remove button atr onclick dari index 0 sampai index terakhir
    var index = $('div #km:last-of-type').index();
    for(var i=0; i<=index; i++){
        var change = i+1;
        $('#km:nth-of-type('+change+') #rmv').attr('onclick','removeKebutuhan('+i+')');
        // set id selectitm atr onchange dari index 0 sampai index terakhir
        $('#km:nth-of-type('+change+') #selectitm').attr('onchange','cekItm('+i+')');
    }
    // jika last index = 0 maka set remove button disable
    if(index == 0){
        $('#rmv:first').attr('disabled','disabled');
    }
}

function getBrg(){
    var data = {
        'kditmbyr' : $('select[name=kdbrg]').val()
    }
    // set #km input to 0
    var Iloop = document.getElementsByName('kditm[]');
    for(var i = 0; i < Iloop.length; i++){
        $('#km:nth-of-type('+(i+1)+') #jumlah').val('');
        $('#km:nth-of-type('+(i+1)+') #nmstn').val('');
        $('#km:nth-of-type('+(i+1)+') #nmitm').val('');
        $('#km:nth-of-type('+(i+1)+') #iditm').val('');
        $('#km:nth-of-type('+(i+1)+') #idstn').val('');
        $('#km:nth-of-type('+(i+1)+') #kdstn').val('');
        $('#km:nth-of-type('+(i+1)+') #stok').html('');
        $('#km:nth-of-type('+(i+1)+') #kditm').selectpicker('val','0');
    }
    // ajax get data barang
    $.ajax({
        url : '<?php echo base_url();?>index.php/material/getDataBarang',
        type : 'GET',
        data : data,
        dataType : 'json',
        success : function(data){
            //console.log(data.detail);
            $('input[name=idBrg]').val(data.barang[0].id_Brg);
            $('input[name=kditmvdr]').val(data.barang[0].KD_ITEMVDR);
            $('input[name=kditmspl]').val(data.barang[0].KD_ITEMSPL);
            $('input[name=dimension]').val(data.barang[0].DIMENSION);
            $('input[name=kdstnMst]').val(data.barang[0].Kd_Satuan_Mst);
            $('input[name=satuan]').val(data.barang[0].Satuan);
            $('input[name=desc]').val(data.barang[0].DESKRIPTION);
            $('#detail').html('');
            $('#ketBrg label').html(' ');
            $('#ketBrg label').html('<label class="text-uppercase">Kebutuhan Material '+data.barang[0].DESKRIPTION+'</label>');
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
function preCekItm(index){
    var kditm = document.getElementsByName('kditm[]');
    var cek= 0;
    // Jika kditm ada yang sama set kditm[index] = 0
    for(var i=0; i<kditm.length; i++){
        if(kditm[i].value == kditm[index].value && index != i && kditm[index].value != 0){
                alert('Material sudah ada, Pilih yg lain!');
                //$('#km:eq('+index+') #kditm').selectpicker('val','0');
                kditm[index].value = 0;
                cek++;
        }
    }
    if(cek == 0){
        cekItm(index);
    }
}
function cekItm(index){
    var kditm = document.getElementsByName('kditm[]');
    var rIndex = index+1;
    //var kdbrg = $('select[name=kdbrg]').val();
    var data = {
        'kditm' : kditm[index].value,
        'kdbyr' : $('select[name=kdbrg]').val()
    }
    // ajax untuk get dan cek data item
    $.ajax({
        url : '../material/cekDataItm',
        type : 'GET',
        data : data,
        dataType : 'json',
        success : function(data){
            if(data.code == 0){
                var jml = document.getElementsByName('jumlah[]');
                $('#alert-transaksi').html(' ');
                getItm(kditm[index].value,index);
            }else{
                $('#alert-transaksi').html('<div class="alert alert-success alert-dismissible" role="alert">'+
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                '<strong>'+data.message+'!</strong></div>');
                $('#km:nth-of-type('+rIndex+') #kditm').selectpicker('val','0');
                $('#km:nth-of-type('+rIndex+') #jumlah').val('');
                $('#km:nth-of-type('+rIndex+') #stok').html('');
                setTimeout(function(){
                    $('#alert-transaksi').html(' ');
                }, 1500);
            }
        }
        ,error:function(xhr){
			//$('#alert-transaksi').html(xhr.responseText);
		}
    });
}

function getItm(kditm,index){
    var data = {
        'kditm' : kditm
    }
    $('#km:eq('+index+') #stok').html(' ');
    var i = index+1;
    $.ajax({
        url : '<?php echo base_url();?>index.php/material/getDataItem',
        type : 'GET',
        data : data,
        dataType : 'json',
        success : function(data){
            $('#km:nth-of-type('+i+') #stok').html('<label class="label label-primary">(QTY '+data[0].jumlah+')</label>');
            $('#km:nth-of-type('+i+') #nmitm').val(data[0].nama_item);
            $('#km:nth-of-type('+i+') #iditm').val(data[0].id_item);
            $('#km:nth-of-type('+i+') #idstn').val(data[0].id_satuan);
            $('#km:nth-of-type('+i+') #kdstn').val(data[0].kd_satuan);
            $('#km:nth-of-type('+i+') #nmstn').val(data[0].nama_satuan);
        }
    });
}
</script>

<script type="text/javascript">
$('#form-tambah-material').submit(function(e){
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
    var formData = new FormData($('#form-tambah-material')[0]);
    $.ajax({
        url: '../material/addMaterial',
        type: 'POST',
        data: formData,
        processData: false,
	    contentType: false,
        beforeSend:function(){
			$('#alert-transaksi').html('<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> Menyimpan data...</div>');
		},success:function(data){
            var d = JSON.parse(data);
            //var d = data;
            //console.log(d);
            if(d.code == 0){
                $('#alert-transaksi').html('<div class="alert alert-success alert-dismissible" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                    '<strong>Success!</strong> '+d.message+'</div>');
                //$('#form-tambah-material')[0].reset();
                getBrg();
            }else{
                $('#alert-transaksi').html('<div class="alert alert-danger alert-dismissible" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                    '<strong>Error!</strong> '+d.message+'</div>');
            }
            getBrg();
            $('#form-tambah-material')[0].reset();
            // set class selectpicker ke tabindex null
            $('.selectpicker').selectpicker('val', '0');
        },error:function(xhr){
			$('#alert-transaksi').html(xhr.responseText);
		}
    });
}

</script>