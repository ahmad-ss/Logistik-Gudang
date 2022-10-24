<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

	public function checkSaldoAwal(){
		$input = $this->input;
		$kd_item = $input->post('namaitm');
		$qty_masuk_keluar = $input->post('debet_kredit');
		$tanggal = $input->post('tanggal');

		$tgl = '';
		if($tanggal != null){
			$tgl = date('Y-m-d',strtotime($tanggal));
		}

		$qtymasuk = 0;
		$qtykeluar = 0;
		if($qty_masuk_keluar == 'qtymsk'){
			$qtymasuk = $input->post('nom_debet_kredit');
		}

		if($qty_masuk_keluar == 'qtyklr'){
			$qtykeluar = $input->post('nom_debet_kredit');
		}

		$sql_saldoawal = '
			SELECT *
			FROM logistik_item a
			WHERE a.kd_item = "'.$kd_item.'"
			ORDER BY a.id_item ASC
			LIMIT 1
		';
		$saldoawal = $this->crud->getDataQuery($sql_saldoawal)->row_array();

		// Hitung saldo
		$sa = $saldoawal['jumlah']+$qtymasuk-$qtykeluar;

		// Jika hasil negatip
		if($sa < 0){
			$respon = array(
				'code'=>1,
				'message'=>'<div class="alert alert-warning">Nominal tidak boleh 0 atau kurang dari 0</div>'
			);
		}else{
			$respon = array(
				'code'=>0,
				'message'=>'<div class="alert alert-success">Nominal valid</div>'
			);
		}

		echo json_encode($respon);
	}

	public function setSaldoAwal(){
		$item = $this->input->post('namaitm');

		$tgl = '';
		if($this->input->post('tanggal') != null){
			$tgl = date('Y-m-d',strtotime($this->input->post('tanggal')));
		}

		$sql_sa = '
			SELECT *
			FROM logistik_item a
			WHERE a.kd_item="'.$item.'"
			ORDER BY a.id_item ASC
			LIMIT 1
		';

		$q_sa = $this->crud->getDataQuery($sql_sa)->row_array();

		$respon = array(
			'nom'=>'<label class="label label-primary">(QTY '.$q_sa['jumlah'].')</label>'
		);

		echo json_encode($respon);
	}

	public function saveTransaksi(){
		$input = $this->input;
		$id_user = $input->post('id_user');

		$form = $this->form_valid;
		$form->set_rules('tanggal','<b class="text-uppercase">Tanggal</b>','required');
		$form->set_rules('keterangan','<b class="text-uppercase">Keterangan</b>','required');
		$form->set_rules('nom_debet_kredit[]','<b>Jumlah</b>','required');
		$form->set_rules('namaitm[]','<b>Nama Item</b>','required');

		if($form->run() == FALSE){
			$respon = array(
				'code'=>1,
				'message'=>validation_errors()
			);
		}else{
			// Item
			//$kodeitem = $this->crud->getDataWhere('logistik_item',array('kd_item'=>$input->post('namaitm')))->row_array();

			// Tgl
			$tgl = '';
			$tgl0 = '';
			if($input->post('tanggal') != null){
				$tgl = date('Y-m-d',strtotime($input->post('tanggal')));
				$tgl0 = date('ymd',strtotime($input->post('tanggal')));
			}

			// Set nomor ks
			$sql_nolgs = '
				SELECT *
				FROM logistik_transaksi a
				WHERE a.no_lgs LIKE "'.$tgl0.'%"
				ORDER BY a.no_lgs DESC
				LIMIT 1
			';

			$q_nolgs = $this->crud->getDataQuery($sql_nolgs)->row_array();

			// Nomor
			$get_nolgs = substr($q_nolgs['no_lgs'], 6);
			$nolgs = $get_nolgs+1;
			$nomor_lgs = $tgl0.sprintf('%04d',$nolgs);

			$Iloop = count($input->post('namaitm'));
			
			for($i = 0; $i<$Iloop; $i++){
				$debet = 0;
				$kredit = 0;
				if($input->post('debet_kredit') == 'qtymsk'){
					$debet = $input->post('nom_debet_kredit')[$i];
				}

				if($input->post('debet_kredit') == 'qtyklr'){
					$kredit = $input->post('nom_debet_kredit')[$i];
				}

				// Saldo Awal
				$sql_sa = '
					SELECT *
					FROM logistik_saldoawal a
					WHERE a.kd_item = "'.$input->post('namaitm')[$i].'"
					AND a.tanggal <= "'.$tgl.'"
					ORDER BY a.id_saldoawal DESC
					LIMIT 1
				';
				$q_sa = $this->crud->getDataQuery($sql_sa)->row_array();

				// Saldo
				// Saldo Akhir = S. Awal + Debet - Kredit
				// Saldo Awal = Saldo Akhir

				// Upload foto bukti
				$config['upload_path'] = './assets/foto_bukti/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
				$config['file_name'] = strtoupper($nomor_lgs);
				$config['max_size'] = 10000;
				$config['overwrite'] = true;

				$this->load->library('upload', $config);

				$file = NULL;
				if($this->upload->do_upload('foto_bukti')){
					$file = $this->upload->data('file_name');
				}

				// Data simpan ke lgs_transaksi
				//foreach($input->post('namitm') as $i){
					$kodeitem = $this->crud->getDataWhere('logistik_item',array('kd_item'=>$input->post('namaitm')[$i]))->row_array();
					$data = array(
						'tanggal'=>$tgl,
						'no_lgs'=>$nomor_lgs,
						'keterangan'=>htmlspecialchars($input->post('keterangan')),
						'no_bukti'=>$input->post('no_bukti'),
						'foto_bukti'=>$file,
						'qty_masuk'=>$debet,
						'qty_keluar'=>$kredit,
						'id_item'=>$kodeitem['id_item'],
						'kd_item'=>$kodeitem['kd_item'],
						'nama_item'=>$kodeitem['nama_item'],
						'nama_satuan'=>$kodeitem['nama_satuan'],
						'kd_satuan'=>$kodeitem['kd_satuan'],
						'team' => $input->post('team')
					);

					$respon = $this->crud->insertDataSave('logistik_transaksi',$data);
				//}

				// Data insert / update ke Saldo
				$whr_saldo = array(
					'kd_item'=>$data['kd_item'],
					'nama_item'=>$data['nama_item']
				);

				$cek_saldo = $this->crud->getDataWhere('logistik_item',$whr_saldo)->row_array();

				// Hitung saldo akhir
				$sa = $cek_saldo['jumlah']+$data['qty_masuk']-$data['qty_keluar'];

				if($cek_saldo > 0){ 
					$data_qty = array(
						'jumlah'=>$sa,
						'update_id_user'=>$id_user,
						'tanggal_update'=>date('Y-m-d H:i:s')
					);

					$this->crud->updData('logistik_item',$whr_saldo,$data_qty);
				}else{
					$data_saldo = array(
						'kd_item'=>$data['kd_item'],
						'nama_item'=>$data['nama_item'],
						'jumlah'=>$sa,
						'input_id_user'=>$id_user,
						'tanggal_input'=>date('Y-m-d H:i:s')
					);

					$this->crud->insertDataSave('logistik_item',$data_saldo);
				}

				// Simpan di log
				$data_log = array(
					'no_lgs'=>$data['no_lgs'],
					'tanggal'=>$data['tanggal'],
					'qty_masuk'=>$data['qty_masuk'],
					'qty_keluar'=>$data['qty_keluar'],
					'id_user'=>$id_user,
					'tanggalwaktu'=>date('Y-m-d H:i:s'),
					'sts_aksi'=>'S'
				);

				$this->crud->insertDataSave('logistik_log_trs',$data_log);
			}

			// JSON Response
			//echo json_encode($respon);

		}
		echo json_encode($respon);
	}

	public function datatableTransaksi(){
		$draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $query = $this->crud->selectAllOrderby('logistik_transaksi','id_transaksi','asc');

        $data = [];

        foreach($query->result() as $r) {
            $row = array();

            $tgl = '';
            if($r->tanggal != null){
            	$tgl = date('l, d F Y',strtotime($r->tanggal));
            }

            $row[] = '<a href="'.base_url().'main/transaksiupd?id='.$r->id_transaksi.'">'.$r->no_lgs.'</a>';
            $row[] = $tgl;
            $row[] = $r->keterangan;
            $row[] = $r->no_bukti;
			$row[] = $r->nama_item;
            $row[] = $r->qty_masuk;
            $row[] = $r->qty_keluar;
			$row[] = $r->nama_satuan;

            $btn = '<button class="btn btn-danger btn-sm" onclick="preHps('.$r->id_transaksi.');"><i class="glyphicon glyphicon-trash"></i></button>';
            $row[] = $btn;

            $data[] = $row;
        }

        $result = array(
            "draw" => $draw,
            "recordsTotal" => $query->num_rows(),
            "recordsFiltered" => $query->num_rows(),
            "data" => $data
        );

        header('Access-Control-Allow-Origin: *');
        echo json_encode($result);
	}

	public function hpsData(){
		$input = $this->input;
		$id = $input->post('id');
		$id_user = $input->post('id_user');

		$trs = $this->crud->getDataWhere('logistik_transaksi',array('id_transaksi'=>$id))->row_array();

		// Saldo Awal
		$sql_sa = '
			SELECT *
			FROM logistik_saldoawal a
			WHERE a.kd_item = "'.$trs['kd_item'].'"
			ORDER BY a.tanggal DESC
			LIMIT 1
		';
		$q_sa = $this->crud->getDataQuery($sql_sa)->row_array();

		// Update saldo
		$whr = array(
			'kd_item'=>$trs['kd_item']
		);

		$cek_saldo = $this->crud->getDataWhere('logistik_item',$whr)->row_array();

		// Hitung saldo akhir
		$sa = $cek_saldo['jumlah']-$trs['qty_masuk']+$trs['qty_keluar'];

		if(count($cek_saldo) == 0){
			// Insert data ke saldo
			$data_qty = array(
				'kd_item'=>$trs['kd_item'],
				'nama_item'=>$trs['nama_item'],
				'jumlah'=>$sa,
				'input_id_user'=>$id_user,
				'tanggal_input'=>date('Y-m-d H:i:s')
			);

			$this->crud->insertDataSave('logistik_item',$data_qty);
		}else{
			// Update saldo
			$data_qty = array(
				'jumlah'=>$sa,
				'update_id_user'=>$id_user,
				'tanggal_update'=>date('Y-m-d H:i:s')
			);

			$this->crud->updData('logistik_item',$whr,$data_qty);
		}

		// Simpan di log
		$data_log = array(
			'no_lgs'=>$trs['no_lgs'],
			'tanggal'=>$trs['tanggal'],
			'qty_masuk'=>$trs['qty_masuk'],
			'qty_keluar'=>$trs['qty_keluar'],
			'id_user'=>$id_user,
			'sts_aksi'=>'D'
		);

		$this->crud->insertDataSave('logistik_log_trs',$data_log);

		// Hapus data
		$this->crud->delData(array('id_transaksi'=>$id),'logistik_transaksi');

		// RESPONSE
		echo '<div class="alert alert-success">Saldo berhasil di hapus</div>';
	}

	public function updTransaksi(){
		$form = $this->form_valid;
		$input = $this->input;

		$form->set_rules('keterangan','<b class="text-uppercase">Keterangan</b>','required');
		$form->set_rules('no_bukti','<b class="text-uppercase">Nomor Bukti</b>','required');

		if($form->run() == FALSE){
			echo '<div class="alert alert-warning">'.validation_errors().'</div>';
		}else{
			$id_trs = $input->post('id_transaksi');
			$whr = array('id_transaksi'=>$id_trs);
			$trs = $this->crud->getDataWhere('logistik_transaksi',$whr)->row_array();

			// Upload foto bukti
	        $config['upload_path'] = './assets/foto_bukti/';
	        $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
	        $config['file_name'] = strtoupper($trs['no_lgs']);
	        $config['max_size'] = 10000;
	        $config['overwrite'] = true;

	        $this->load->library('upload', $config);

	        $file = NULL;
	        if($this->upload->do_upload('foto_bukti')){
	            $file = $this->upload->data('file_name');
	        }

	        if($file != NULL){
	        	$data = array(
					'keterangan'=>htmlspecialchars($input->post('keterangan')),
					'no_bukti'=>$input->post('no_bukti'),
					'foto_bukti'=>$file
				);
	        }else{
	        	$data = array(
					'keterangan'=>htmlspecialchars($input->post('keterangan')),
					'no_bukti'=>$input->post('no_bukti')
				);
	        }

	        $respon = $this->crud->updData('logistik_transaksi',$whr,$data);

	        if($respon['code'] == 0){
	        	echo '<div class="alert alert-success">'.$respon['message'].'</div>';
	        }else{
	        	echo '<div class="alert alert-warning">Tidak ada update</div>';
	        }
		}
	}
	public function tempSave()
	{
		$input = $this->input;
		$id_user = $input->post('id_user');
		$Iloop = count($input->post('namaitm[]'));

		$form = $this->form_valid;
		$form->set_rules('tanggal','<b class="text-uppercase">Tanggal</b>','required');
		$form->set_rules('keterangan','<b class="text-uppercase">Keterangan</b>','required');
		$form->set_rules('nom_debet_kredit[]','<b>Jumlah</b>','required');
		$form->set_rules('namaitm[]','<b>Nama Item</b>','required');

		if($form->run() == FALSE){
			$respon = array(
				"code" => 1,
				"message"=> validation_errors()
			);
		}else{
			// Item
			//$kodeitem = $this->crud->getDataWhere('logistik_item',array('kd_item'=>$input->post('namaitm')))->row_array();

			// Tgl
			$tgl = '';
			$tgl0 = '';
			if($input->post('tanggal') != null){
				$tgl = date('Y-m-d',strtotime($input->post('tanggal')));
				$tgl0 = date('ymd',strtotime($input->post('tanggal')));
			}

			//======MULAI LOOP SAVE KE Temp=========//
			// Set nomor ks
			$Iloop = count($input->post('namaitm'));
			
			for($i = 0; $i<$Iloop; $i++){
				$sql_nolgs = '
					SELECT *
					FROM logistik_temp_trs a
					WHERE a.no_lgs LIKE "'.$tgl0.'%"
					ORDER BY a.no_lgs DESC
					LIMIT 1
				';

				$q_nolgs = $this->crud->getDataQuery($sql_nolgs)->row_array();

				// Nomor
				$get_nolgs = substr($q_nolgs['no_lgs'], 6);
				$nolgs = $get_nolgs+1;
				$nomor_lgs = $tgl0.sprintf('%04d',$nolgs);

				// Upload foto bukti
				$config['upload_path'] = './assets/foto_bukti/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
				$config['file_name'] = strtoupper($nomor_lgs);
				$config['max_size'] = 10000;
				$config['overwrite'] = true;

				$this->load->library('upload', $config);

				$file = NULL;
				if($this->upload->do_upload('foto_bukti')){
					$file = $this->upload->data('file_name');
				}

				// Data simpan ke lgs_transaksi
				//foreach($input->post('namitm') as $i){
					$kodeitem = $this->crud->getDataWhere('logistik_item',array('kd_item'=>$i))->row_array();
					$data = array(
						'tanggal'=>$tgl,
						'no_lgs'=>$nomor_lgs,
						'keterangan'=>htmlspecialchars($input->post('keterangan')),
						'no_bukti'=>$input->post('no_bukti'),
						'foto_bukti'=>$file,
						'team' => $input->post('team')
					);
					$save = $this->crud->insertDataSave('logistik_temp_trs',$data);
				//}
			}
			//=======END LOOP=======//

			// JSON Response
			$respon = array(
				'code' => $save['code'],
				'message' => "Apakah Anda Yakin Ingin Menyimpan?",
				'last_id' => $save['last_id']
			);
		}
		echo json_encode($respon);
	}

}


// 	public function updTransaksi(){
// 		$form = $this->form_valid;
// 		$form->set_rules('keterangan','<b class="text-uppercase">Keterangan</b>','required');
// 		$form->set_rules('no_bukti','<b class="text-uppercase">Nomor Bukti</b>','required');
// 		//$form->set_rules('tanggal','<b class="text-uppercase">Tanggal</b>','required');

// 		if($form->run() == FALSE){
// 			// Jika ada yg kurang
// 			echo '<div class="alert alert-warning">';
// 			echo validation_errors();
// 			echo '</div>';
// 		}else{
// 			//load-set lib upload
// 			if (!is_dir('./assets/file_bukti/')) {
// 				mkdir('./assets/file_bukti/');
// 			}
			
// 			$config = array();
// 			$config['upload_path'] = './assets/file_bukti/';
// 			$config['allowed_types'] = 'gif|jpg|png|jpeg';
// 			//$config['max_size'] = '8000';
// 			$config['overwrite'] = true;
// 			$this->load->library('upload', $config);
			
// 			if(!$this->upload->do_upload('file_bukti')){
// 				$error = array('error' => $this->upload->display_errors());
// 				// echo "jancok";
// 			}else{
// 				$data = array('image_metadata' => $this->upload->data());
// 				$path = './assets/file_bukti/';
// 				$namafile = $data['image_metadata']['file_name'];
// 			}
			
// 			//init input
// 			$input = $this->input;

// 			//data
// 			$data = array(
// 				'keterangan' => $input->post('keterangan'),
// 				'no_bukti' => $input->post('no_bukti'),
// 				'foto_bukti' => $path . $namafile
// 			);

// 			//where
// 			$whr = array(
// 				'id_transaksi' => $input->post('id_transaksi')
// 			);

// 			//update
// 			$respon = $this->crud->updData('logistik_transaksi',$whr,$data);

// 			// Data respon alert
// 			if($respon['code'] == 0){
// 				echo '<div class="alert alert-success">';
// 				echo 'Data Keterangan <b>'.$data['keterangan'].'</b>, No Bukti <b>'.strtoupper($data['no_bukti']).'</b> berhasil di simpan';
// 				echo '</div>';
// 			}else{
// 				echo '<div class="alert alert-warning">';
// 				echo $respon['message'];
// 				echo '</div>';
// 			}
// 		}
// 	}
// }