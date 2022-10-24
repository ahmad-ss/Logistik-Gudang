<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('get_login') != 1){
			redirect('Welcome');
		}
		$this->load->helper(array('form', 'url'));
	}

# Dashboard
	public function dashboard(){
		$data['title'] = 'Dashboard';
		$this->template->view('dashboard',$data);
	}

# Saldo Awal
	public function saldoawal(){
		$data['title'] = 'Saldo Awal';
		$this->template->view('saldoawal/index',$data);	
	}

	public function saldoawalAdd(){
		$data['title'] = 'Tambah Saldo Awal';

		$satuan = $this->crud->selectAllOrderby('logistik_satuan', 'id_satuan', 'asc');
		$data['satuan'] = $satuan->result();
		
		$this->template->view('saldoawal/tambah',$data);	
	}

	public function saldoawalDetail(){
		$id = $this->input->get('id');
		$lgs_saldoawal = $this->crud->getDataWhere('logistik_saldoawal',array('id_saldoawal'=>$id))->row_array();

		$data['title'] = 'Detail Saldo Awal';
		//$data['cabang'] = $this->crud->selectAllOrderby('hrd_cabang','id','asc')->result();
		$data['lgs_saldoawal'] = $lgs_saldoawal;
		$data['crud'] = $this->crud;
		$this->template->view('saldoawal/detail',$data);
	}

# Saldo
	public function saldo(){
		$data['title'] = 'Saldo';
		$this->template->view('saldo/index',$data);
	}

# Transaksi
	public function transaksi(){
		$data['title'] = 'Transaksi';
		$this->template->view('transaksi/index',$data);
	}

	public function transaksiadd(){
		$data['title'] = 'Tambah Transaksi';
		$item = $this->crud->selectAllOrderby('logistik_item', 'id_item', 'asc');

		$data['item'] = $item->result();
		$this->template->view('transaksi/tambahv2',$data);
	}

	public function transaksiupd(){
		$id = $this->input->get('id');
		$trs = $this->crud->getDataWhere('logistik_transaksi',array('id_transaksi'=>$id))->row_array();

		$data['title'] = 'Update Transaksi '.$trs['no_lgs'];
		$data['lgs_transaksi'] = $trs;
		$this->template->view('transaksi/update',$data);
	}

# Log transaksi
	public function logTransaksi(){
		$data['title'] = 'Log Transaksi';
		$sql_log = '
			SELECT *, 
			(SELECT nama
			FROM users
			WHERE id_user=a.id_user) as nama_user
			FROM logistik_log_trs a
			ORDER BY a.tanggalwaktu DESC
		';
		$data['log'] = $this->crud->getDataQuery($sql_log)->result();
		$this->template->view('transaksi/log',$data);
	}

# Laporan
	public function laporanLogistikPeriode(){
		$data['title'] = 'Laporan Stock Barang';
		$sql_item = '
			SELECT *
			FROM logistik_item
		';
		$data['item'] = $this->crud->getDataQuery($sql_item)->result();
		$this->template->view('laporan/logistik_periode',$data);
	}

	public function laporanLogistikHarian(){
		$data['title'] = 'Laporan Kartu Stock Barang';
		$sql_cabang = '
			SELECT *
			FROM logistik_item
		';
		$data['item'] = $this->crud->getDataQuery($sql_cabang)->result();
		$this->template->view('laporan/logistik_harian',$data);
	}
}