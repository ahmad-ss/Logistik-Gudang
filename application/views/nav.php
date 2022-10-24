<nav class="navbar navbar-inverse navbar-fixed">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="<?php echo base_url();?>main/dashboard">Logistik BA/LB</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <!-- <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Saldo <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo base_url();?>main/saldoawal">Saldo Awal</a></li>
            <li><a href="<?php echo base_url();?>main/saldo">Saldo</a></li>
          </ul>
        </li> -->
        <li><a href="<?php echo base_url();?>main/saldoawal">QTY Awal</a></li>
        <li><a href="<?php echo base_url();?>main/transaksi">Transaksi</a></li>
        <?php 
          $user_nav = $crud->getDataWhere('users',array('id_user'=>$this->session->userdata('id_user')))->row_array();
          if($user_nav['hak_akses'] == 'su'){
            echo '<li><a href="'.base_url().'main/logTransaksi">Log Transaksi</a></li>';
          }
        ?>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Laporan <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo base_url();?>main/laporanlogistikperiode">Stock Barang</a></li>
            <li><a href="<?php echo base_url();?>main/laporanlogistikharian">Kartu Stock Barang</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $this->session->userdata('username_user');?></a></li>
        <li><a href="<?php echo base_url();?>login/getlogout"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>
      </ul>
    </div>
  </div>
</nav>

<script type="text/javascript">
var url=window.location;
$('.navbar-nav a').each(function(e){
  var link = $(this).attr('href');
  if(link==url){
      $(this).parent('li').addClass('active');
      $(this).closest('.treeview').addClass('active');
  }
});
</script>