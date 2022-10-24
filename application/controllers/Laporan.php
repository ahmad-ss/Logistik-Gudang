<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	public function laporanPeriode(){
		$form = $this->form_valid;
		$input = $this->input;

		// $form->set_rules('kd_item','<b>Item</b>','required');
		$form->set_rules('tglawal','<b>Tanggal Periode Awal</b>','required');
		$form->set_rules('tglakhir','<b>Tanggal Periode Akhir</b>','required');

		$form_check = $form->run();
		if($form_check == FALSE){
			echo '<div class="alert alert-warning">'.validation_errors().'</div>';
		}else{
			$kd_item = $input->post('kd_item');
			
			$tglawal = date('Y-m-d',strtotime($input->post('tglawal')));
			$tglakhir = date('Y-m-d',strtotime($input->post('tglakhir')));

			// Saldo awal == AND a.kd_item = "'.$kd_item.'" 
			$sql_saldoawal = '
				SELECT *
				FROM logistik_saldoawal a
				WHERE a.tanggal <= "'.$tglawal.'"
				OR a.tanggal <= "'.$tglakhir.'"
				ORDER BY a.tanggal DESC
			';

			$q_saldoawal = $this->crud->getDataQuery($sql_saldoawal)->result();

			// Saldo Real diambil dari logistik_item
			$sql_saldoitem = '
				SELECT * 
				FROM logistik_item a
				ORDER BY a.kd_item ASC';

			$q_saldoitem = $this->crud->getDataQuery($sql_saldoitem)->result();

			// Semua transaksi
			$sql_trs_all = '
				SELECT * 
				FROM logistik_transaksi a
				ORDER BY a.id_transaksi ASC';

			$q_trs_all = $this->crud->getDataQuery($sql_trs_all)->result();

			// Transaksi sebelum tgl awal //WHERE a.kd_item="'.$kd_item.'" 
			$sql_trs_old = '
				SELECT *
				FROM logistik_transaksi a
				WHERE a.tanggal < "'.$tglawal.'"
				ORDER BY a.tanggal, a.id_transaksi ASC 
			';

			$q_trs_old = $this->crud->getDataQuery($sql_trs_old)->result();

			// Saldo awal between //WHERE a.kd_item = "'.$kd_item.'"
			// $sql_saldoawal = '
			// 	SELECT *
			// 	FROM logistik_saldoawal a
			// 	WHERE a.tanggal BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'"
			// 	ORDER BY a.tanggal DESC
			// ';

			// $q_saldoawal = $this->crud->getDataQuery($sql_saldoawal)->result();

			// Transaksi //WHERE a.kd_item="'.$kd_item.'"
			$sql_trs = '
				SELECT *
				FROM logistik_transaksi a
				WHERE a.tanggal BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'"
				ORDER BY a.tanggal ASC
			';

			$q_trs = $this->crud->getDataQuery($sql_trs)->result();

			// View table
			$data['saldoawal'] = $q_saldoawal;
			$data['transaksi_old'] = $q_trs_old;
			$data['transaksi_all'] = $q_trs_all;
			$data['saldoitem'] = $q_saldoitem;
			//$data['saldoawal_between'] = $q_saldoawal_between;
			$data['transaksi'] = $q_trs;
			$data['tglawal'] = $tglawal;
			$data['tglakhir'] = $tglakhir;
			$this->load->view('laporan/logistik_periode_data3',$data);
		}
	}

	public function laporanHarian(){
		$form = $this->form_valid;
		$input = $this->input;

		//$form->set_rules('kd_item','<b>Item</b>','required');
		$form->set_rules('tgl','<b>Tanggal</b>','required');

		$form_check = $form->run();
		if($form_check == FALSE){
			echo '<div class="alert alert-warning">'.validation_errors().'</div>';
		}else{
			//$kd_item = $input->post('kd_item');

			$tgl = date('Y-m-d',strtotime($input->post('tgl')));

			// Saldo awal //AND a.tanggal <= "'.$tgl.'" WHERE a.kd_item = "'.$kd_item.'"
			$sql_saldoawal = '
				SELECT *
				FROM logistik_saldoawal a
				WHERE a.tanggal <= "'.$tgl.'"
				ORDER BY a.tanggal DESC
				LIMIT 1
			';

			$q_saldoawal = $this->crud->getDataQuery($sql_saldoawal)->row_array();

			// Transaksi sebelum tgl awal WHERE a.kd_item="'.$kd_item.'"
			$sql_trs_old = '
				SELECT *
				FROM logistik_transaksi a
				WHERE a.tanggal < "'.$tgl.'"
				ORDER BY a.tanggal, a.id_transaksi ASC 
			';

			$q_trs_old = $this->crud->getDataQuery($sql_trs_old)->result();

			// Transaksi // WHERE a.kd_item="'.$kd_item.'"
			$sql_trs = '
				SELECT *
				FROM logistik_transaksi a
				WHERE a.tanggal = "'.$tgl.'"
				ORDER BY a.tanggal, a.id_transaksi ASC
			';

			$q_trs = $this->crud->getDataQuery($sql_trs)->result();

			// View table
			$data['saldoawal'] = $q_saldoawal;
			$data['transaksi'] = $q_trs;
			$data['tgl'] = $tgl;
			$data['transaksi_old'] = $q_trs_old;
			$this->load->view('laporan/logistik_harian_data',$data);
		}
	}
	public function laporanLogistikHarian(){
		$form = $this->form_valid;
		$input = $this->input;

		$form->set_rules('kd_item','<b>Item</b>','required');
		$form->set_rules('tglawl','<b>Tanggal</b>','required');
		$form->set_rules('tglakr','<b>Tanggal</b>','required');

		$form_check = $form->run();
		if($form_check == FALSE){
			echo '<div class="alert alert-warning">'.validation_errors().'</div>';
		}else{
			$tglawl = date('Y-m-d',strtotime($input->post('tglawl')));
			$tglakr = date('Y-m-d',strtotime($input->post('tglakr')));
			$kditm = $input->post('kd_item');
			
			//Qty Awal //AND a.tanggal <= "'.$tglawl.'"
			$sql_saldoawal = '
				SELECT *
				FROM logistik_saldoawal a
				WHERE a.kd_item = "'.$kditm.'"
				AND a.tanggal <= "'.$tglawl.'"
				ORDER BY a.tanggal ASC
				LIMIT 1
			';
			
			/*
				jika tgl <= tgl saldo awal yg terload saldoawal da
			*/

			$q_saldoawal = $this->crud->getDataQuery($sql_saldoawal);

			// Saldo awal between //
			$sql_saldoawal_between = '
				SELECT *
				FROM logistik_saldoawal a
				WHERE a.kd_item = "'.$kditm.'"
				AND a.tanggal BETWEEN "'.$tglawl.'" AND "'.$tglakr.'"
				ORDER BY a.tanggal ASC
				LIMIT 1
			';

			$q_saldoawal_between = $this->crud->getDataQuery($sql_saldoawal_between);

			// Transaksi sebelum tgl awal WHERE a.kd_item="'.$kd_item.'"
			$sql_trs_old = '
				SELECT *
				FROM logistik_transaksi a
				WHERE a.kd_item = "'.$input->post('kd_item').'"
				AND a.tanggal < "'.$tglawl.'"
				ORDER BY a.tanggal, a.id_transaksi ASC 
			';

			$q_trs_old = $this->crud->getDataQuery($sql_trs_old)->result();

			// Transaksi // WHERE a.kd_item="'.$kd_item.'"
			$sql_trs = '
				SELECT *
				FROM logistik_transaksi a
				WHERE a.kd_item = "'.$input->post('kd_item').'"
				AND a.tanggal BETWEEN "'.$tglawl.'" AND "'.$tglakr.'"
				ORDER BY a.tanggal, a.id_transaksi ASC
			';

			$q_trs = $this->crud->getDataQuery($sql_trs)->result();

			//ITEM INFO
			if($q_saldoawal->result() == null){
				$saldoawal=$q_saldoawal_between;
				$infsa = $q_saldoawal_between->row_array();
			}else{
				$saldoawal=$q_saldoawal;
				$infsa = $q_saldoawal->row_array();
			}
			$saw = 0;
			foreach($saldoawal->result() as $sa) { 
				//saldoawal->result() != null){
					$saw = $sa->jumlah;
					foreach($q_trs_old as $trs_old){
						// Hitung debet atau kredit
						if($sa->kd_item == $trs_old->kd_item){
							if($trs_old->qty_masuk > 0){
								$debet = $trs_old->qty_masuk;
								$saw = $saw+$debet;
							}
			
							if($trs_old->qty_keluar > 0){
								$kredit = $trs_old->qty_keluar;
								$saw = $saw-$kredit;
							}
						}
					}
				//}
			}
			
			$sldawl = array(
				'kd_item' => $infsa['kd_item'],
				'nm_item' => $infsa['nama_item'],
				'jumlah'=> $saw,
				'satuan'=> $infsa['nama_satuan']
			);

			// View table
			$data['saldoawal'] = $saldoawal->result();
			$data['sldawl'] = $sldawl;
			$data['transaksi'] = $q_trs;
			$data['tgl'] = $tglawl;
			$data['tglakr'] = $tglakr;
			$data['transaksi_old'] = $q_trs_old;
			$data['kdinp'] = $kditm;
			$this->load->view('laporan/logistik_harian_data2',$data);
		}
	}

}