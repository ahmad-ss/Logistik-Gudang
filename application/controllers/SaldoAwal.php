<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SaldoAwal extends CI_Controller {
	public function datatablesaldoawal(){
		$draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $query = $this->crud->selectAllOrderby('logistik_saldoawal','id_saldoawal','asc');

        $data = [];

        foreach($query->result() as $r) {
            $row = array();
            $row[] = $r->id_saldoawal;
            $row[] = '<a href="'.base_url().'main/saldoawaldetail?id='.$r->id_saldoawal.'">'.$r->kd_item.'</a>';
            $row[] = $r->nama_item;
            $row[] = $r->jumlah;
            $row[] = $r->nama_satuan;

            $tgl = '';
            if($r->tanggal != null){
            	$tgl = date('l, d F Y',strtotime($r->tanggal));
            }

            $row[] = $tgl;

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

	public function datatablesaldoawal0(){
		$draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $query = $this->crud->selectAllOrderby('logistik_saldoawal','id_saldoawal','asc');

        $data = [];

        foreach($query->result() as $r) {
            $row = array();
            $row[] = $r->id_saldoawal;
            $row[] = $r->kd_item;
            $row[] = $r->nama_item;
            $row[] = $r->jumlah;
            $row[] = $r->nama_satuan;

            $tgl = '';
            if($r->tanggal != null){
            	$tgl = date('l, d F Y',strtotime($r->tanggal));
            }

            $row[] = $tgl;

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

	public function getSatuan(){
		$id = $this->input->post('kd_cabang');
		$q = $this->crud->getDataWhere('hrd_cabang',array('Kd_Cabang'=>$id))->result();
		echo json_encode($q);
	}

	public function cekItem(){
		$input = $this->input;
		//CEK NAMA ITEM//
		$where = array(
			'nama_item' => $input->post('nama_item')
		);
		$cek_item = $this->crud->getDataWhere('logistik_saldoawal', $where)->num_rows();
		if($cek_item > 0){
			$respon = array(
				'code'=>1,
				'message'=>'<div class="alert alert-warning">Item Sudah Ada</div>'
			);
		}else{
			$respon = array(
				'code'=>0,
				'message'=>'<div class="alert alert-success">Item Belum Ada</div>'
			);
		}

		echo json_encode($respon);
	}

	public function saveSaldoawal(){
		// Form validation
		$form = $this->form_valid;
		//$form->set_rules('kd_cabang','<b class="text-uppercase">Kode Cabang</b>','required|is_unique[ks_saldoawal.kd_cabang]');
		$form->set_rules('namaitm','<b class="text-uppercase">Nama Item</b>','required');
		$form->set_rules('tanggal','<b class="text-uppercase">Tanggal</b>','required');
		$form->set_rules('saldoawal','<b class="text-uppercase">Jumlah</b>','required');
		$form->set_rules('satuan','<b class="text-uppercase">Satuan</b>','required');

		if($form->run() == FALSE){
			// Jika ada yg kurang
			// echo '<div class="alert alert-warning">';
			// echo validation_errors();
			// echo '</div>';
		}else{
			// Init input
			$input = $this->input;
			$id_user = $input->post('id_user');

			//Simpan satuan jika belum disimpan
			$ceksatuan = $this->crud->getDataWhere('logistik_satuan', array('nama_satuan'=>$input->post('satuan')));
			
			if($ceksatuan->num_rows() == 0){
				$numrow = $this->crud->selectAll('logistik_satuan')->num_rows();
				$kdstn = 'KS';
				$num = $numrow+1;
				$kd_stn = $kdstn.sprintf('%04d',$num);

				$data = array(
					'kd_satuan' => $kd_stn,
					'nama_satuan' => $input->post('satuan')
				);

				$this->crud->insertDataSave('logistik_satuan', $data);
			}else{
				$datasatuan = $ceksatuan->row_array();
				$kd_stn = $datasatuan['kd_satuan'];
			}

			// set tanggal
			$tgl = '';
			if($input->post('tanggal') != null){
				$tgl = date('Y-m-d',strtotime($input->post('tanggal')));
			}

			//Buat kode item
			$numrow = $this->db->get('logistik_saldoawal')->num_rows();

			$kditm = substr($input->post('namaitm'),0,3);
			if($numrow > 0 ){
				$num = $numrow+1;
				$kd_itm = $kditm.sprintf('%04d',$num);
			}else{
				$num = 1;
				$kd_itm = $kditm.sprintf('%04d',$num);
			}

			// Simpan data
			$data = array(
				'nama_item'=>$input->post('namaitm'),
				'kd_item' => $kd_itm,
				'kd_satuan' => $kd_stn,
				'nama_satuan'=>$input->post('satuan'),
				'tanggal'=>$tgl,
				'jumlah'=>$input->post('saldoawal')
			);

			$respon = $this->crud->insertDataSave('logistik_saldoawal',$data);

			// Simpan juga di logistik_item
			$whr_logistik_itm = array(
				'kd_item'=>$data['kd_item']
			);

			$cek_saldo = $this->crud->getDataWhere('logistik_item',$whr_logistik_itm)->row_array();

			if(count($cek_saldo) == 0){
				$data_saldo = array(
					'nama_item'=>$input->post('namaitm'),
					'kd_item' => $kd_itm,
					'kd_satuan' => $kd_stn,
					'nama_satuan'=>$input->post('satuan'),
					'jumlah'=>$input->post('saldoawal'),
					'input_id_user'=>$id_user,
					'tanggal_input'=>date('Y-m-d H:i:s')
				);

				$this->crud->insertDataSave('logistik_item',$data_saldo);
			}else{
				$data_saldo = array(
					'jumlah'=>$data['saldoawal'],
					'update_id_user'=>$id_user,
					'tanggal_update'=>date('Y-m-d H:i:s')
				);

				$this->crud->updData('logistik_item',$whr_logistik_itm,$data_saldo);
			}

			// Data respon alert
			if($respon['code'] == 0){
				echo '<div class="alert alert-success">';
				echo 'Data Tanggal <b>'.$data['tanggal'].'</b>, Cabang <b>'.strtoupper($data['nama_item']).'</b>, Saldo Awal <b>'.$data['jumlah'].'</b> berhasil di simpan';
				echo '</div>';
			}else{
				echo '<div class="alert alert-warning">';
				echo $respon['message'];
				echo '</div>';
			}
		}
	}

	public function updateSaldoawal(){
		// Form validation
		$form = $this->form_valid;
		$form->set_rules('namaitm','<b class="text-uppercase">Nama Item</b>','required');
		$form->set_rules('tanggal','<b class="text-uppercase">Tanggal</b>','required');
		$form->set_rules('saldoawal','<b class="text-uppercase">Jumlah</b>','required');
		$form->set_rules('satuan','<b class="text-uppercase">Satuan</b>','required');

		if($form->run() == FALSE){
			// Jika ada yg kurang
			echo '<div class="alert alert-warning">';
			echo validation_errors();
			echo '</div>';
		}else{
			// Init input
			$input = $this->input;

			//Buat kode item
			$numrow = $this->db->get('logistik_saldoawal')->num_rows();

			$kditm = substr($input->post('namaitm'),0,3);
			$kdstn = 'KS';
			if($numrow > 0 ){
				$num = $numrow+1;
				$kd_itm = $kditm.sprintf('%04d',$num);
				$kd_stn = $kdstn.sprintf('%04d',$num);
			}else{
				$num = 1;
				$kd_itm = $kditm.sprintf('%04d',$num);
				$kd_stn = $kdstn.sprintf('%04d',$num);
			}

			// id user
			$id_user = $input->post('id_user');

			// set tanggal
			$tgl = '';
			if($input->post('tanggal') != null){
				$tgl = date('Y-m-d',strtotime($input->post('tanggal')));
			}

			// Where id
			$whr = array(
				'id_saldoawal'=>$input->post('id_saldoawal')
			);

			// Dapatkan saldoawal lama
			$saldoawal_old = $this->crud->getDataWhere('logistik_saldoawal',$whr)->row_array();
			$sa_old = $saldoawal_old['jumlah'];

			//where kd item
			$kd_item = $saldoawal_old['kd_item'];

			// Dapatkan saldo now
			$saldo_old = $this->crud->getDataWhere('logistik_item',array('kd_item'=>$kd_item))->row_array();

			// Hitung saldo
			$hit_saldo = $saldo_old['jumlah']-$sa_old+$input->post('saldoawal');

			// Update data logistik_saldoawal
			$data = array(
				'nama_item'=>$input->post('namaitm'),
				'tanggal'=>$tgl,
				'jumlah'=>$input->post('saldoawal'),
				'nama_satuan'=>$input->post('satuan'),
				'kd_satuan'=>$kd_stn
			);

			$respon = $this->crud->updData('logistik_saldoawal',$whr,$data);

			// Update ke logistik item
			$whr_itm = array(
				'kd_item'=>$kd_item
			);

			$data_sld = array(
				'nama_item'=>$data['nama_item'],
				'jumlah'=>$hit_saldo,
				'nama_satuan'=>$data['nama_satuan'],
				'kd_satuan'=>$kd_stn,
				'update_id_user'=>$id_user,
				'tanggal_update'=>date('Y-m-d H:i:s')
			);

			$this->crud->updData('logistik_item',$whr_itm,$data_sld);

			// Data respon alert
			if($respon['code'] == 0){
				echo '<div class="alert alert-success">';
				echo 'Data Tanggal <b>'.$data['tanggal'].'</b>, Cabang <b>'.strtoupper($data['nama_item']).'</b>, Saldo Awal <b>'.$data['jumlah'].'</b> berhasil di simpan';
				echo '</div>';
			}else{
				echo '<div class="alert alert-warning">';
				echo $respon['message'];
				echo '</div>';
			}
		}
	}

	// public function datatableSaldoawal(){
	// 	$list = $this->datatablesaldoawal->get_datatables();
 //        $data = array();
 //        $no = $_POST['start'];
 //        foreach ($list as $field) {
 //            $no++;
 //            $row = array();

 //            $row[] = $field->kd_cabang;
 //            $row[] = $field->cabang;
 //            $row[] = $field->alamat;
            
 //            $tgl = '';
 //            if($field->tanggal != null){
 //            	$tgl = date('d/m/Y',strtotime($field->tanggal));
 //            }

 //            $row[] = $tgl;
 //            $row[] = number_format($field->saldoawal,2,',','.');
 
 //            $data[] = $row;
 //        }
 
 //        $output = array(
 //            "draw" => $_POST['draw'],
 //            "recordsTotal" => $this->datatablesaldoawal->count_all(),
 //            "recordsFiltered" => $this->datatablesaldoawal->count_filtered(),
 //            "data" => $data,
 //        );
 //        //output dalam format JSON
 //        echo json_encode($output);
	// }
}