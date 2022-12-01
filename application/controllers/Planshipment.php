<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planshipment extends CI_Controller {
    public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

    public function getDataPlan(){
        // Load data yang dibutuhkan untuk planshipment
        // sql join table logistik_plasnshipment dan logistik_d_planshipment
        $sql = 'SELECT a.id_PalinShipment,a.Tgl_Shipment, a.Ket , b.* FROM logistik_plainshipment a 
        JOIN logistik_d_plainshipment b ON a.id_PalinShipment = b.id_PalinShipment 
        ORDER BY a.Tgl_Shipment, a.id_PalinShipment, b.KD_ITMBYR ASC';
        $query = $this->crud->getDataQuery($sql);
        // sql data planshipment
        $sql2 = 'SELECT * FROM logistik_plainshipment ORDER BY id_PalinShipment ASC';
        $query2 = $this->crud->getDataQuery($sql2);
        
        $data['planshipment'] = $query2->result();
        $data['d_planshipment'] = $query->result();
        
        // Load dataIndex.php
        $this->load->view('planshipment/dataIndex',$data);
    }

    public function addPlanshipment(){
        // Load data yang dibutuhkan untuk planshipment
        $input = $this->input;
        $tglkirim = strtotime($input->post('tglshipmt'));
        $ket = $input->post('keterangan');
        
        // query insert planshipment
        $data = array(
            'Tgl_Shipment' => date('Y-m-d', $tglkirim),
            'Ket' => $ket,
            'input_id_user' => $this->session->userdata('id_user'),
            'tanggal_input' => date('Y-m-d H:i:s')
        );
        $respon = $this->crud->insertDataSave('logistik_plainshipment',$data);

        // query insert detail planshipment
        $Iloop = count($input->post('kdbrg'));
        for($i=0; $i<$Iloop; $i++){
            // ambil data matrial
            $kdbrg = $input->post('kdbrg')[$i];
            $sql = "SELECT * FROM logistik_matrial WHERE KD_ITMBYR = '$kdbrg'";
            $query = $this->crud->getDataQuery($sql);
            if($query->num_rows() > 0){
                //'KD_ITEMSPL' => $input->post('kditmspl')[$i],
                //'Kd_Satuan_Mst' => $input->posr('kdstnMst')[$i],
                foreach($query->result() as $row){
                    $dataDetail = array(
                        'id_PalinShipment' => $respon['last_id'],
                        'id_Material' => $row->id_Material,
                        'KD_ITMBYR' => $input->post('kdbrg')[$i],
                        'Jumlah_Shipment' => $input->post('jumlahkirim')[$i],
                        'Satuan' => $input->post('satuan')[$i],
                        'id_Brgpo' => $input->post('idBrg')[$i],
                        'id_Brg' => $input->post('idBrg')[$i],
                        'KD_ITEMVDR' => $input->post('kdbrg')[$i],
                        'DIMENSION' => $input->post('dimension')[$i],
                        'DESKRIPTION' => $input->post('desc')[$i],
                        'DESKRIPTION2' => $row->DESKRIPTION2,
                        'id_item' => $row->id_item,
                        'nama_item' => strtoupper($row->nama_item),
                        'kd_item' => strtoupper($row->kd_item),
                        'id_satuan' => $row->id_satuan,
                        'kd_satuan' => $row->kd_satuan,
                        'nama_satuan' => strtoupper($row->nama_satuan),
                        'jumlah_Material' => $row->jumlah,
                        'Sub_Total' => $input->post('jumlahkirim')[$i] * $row->jumlah
                    );
                    $this->crud->insertDataSave('logistik_d_plainshipment',$dataDetail);
                }
            }
        }
        // return hasil operasi
        echo json_encode($respon);
    }

    public function dataReport(){
        // Load data yang dibutuhkan untuk planshipment
        $tglawl = strtotime($this->input->post('tglawal'));
        $tglakh = strtotime($this->input->post('tglakhir'));
        // get data DESKRIPTION from table brgpi that part in logistik_d_planshipment
        $sql = 'SELECT DESKRIPTION,KD_ITMBYR FROM brgpi WHERE KD_ITMBYR IN 
        (SELECT KD_ITMBYR FROM logistik_d_plainshipment a JOIN logistik_plainshipment b ON b.id_PalinShipment = a.id_PalinShipment 
        WHERE b.Tgl_Shipment BETWEEN "'.date('Y-m-d',$tglawl).'" AND "'.date('Y-m-d',$tglakh).'")';
        $query = $this->crud->getDataQuery($sql);
        $data['matrial'] = $query->result();
        // // get data planshipment
        $sql2 = 'SELECT sum(b.Sub_Total) as Btotal, a.id_PalinShipment,a.Tgl_Shipment, a.Ket , b.*
        FROM logistik_plainshipment a JOIN logistik_d_plainshipment b ON a.id_PalinShipment = b.id_PalinShipment 
        WHERE a.Tgl_Shipment BETWEEN "'.date('Y-m-d',$tglawl).'" AND "'.date('Y-m-d',$tglakh).'"
        GROUP BY b.KD_ITMBYR,b.kd_item ORDER BY b.kd_item'; // a.Tgl_Shipment, b.id_PalinShipment,
        $query2 = $this->crud->getDataQuery($sql2);
        $data['planshipment'] = $query2->result();
        
        // return hasil view dataReport.php
        $this->load->view('planshipment/dataReport',$data);
    }

    public function deletePlanshipment(){
        $id = $this->input->post('id');
        $delete1 = $this->crud->delData(array('id_PalinShipment'=>$id),'logistik_plainshipment');

        $delete2 = $this->crud->delData(array('id_PalinShipment'=>$id), 'logistik_d_plainshipment');
        //$delete1 = null; $delete2 = null;
        $respon['code'] = 1;
        $respon['message'] = 'Data gagal dihapus';
        if($delete1 != null){
            $respon['code'] = 0;
            $respon['message'] = 'Data berhasil dihapus';
        }
        echo json_encode($respon);
    }
    public function deleteItemPlanshipment(){
        $id = $this->input->post('id');
        $kd = $this->input->post('kd_itmbyr');

        $delete = $this->crud->delData2C(array('id_PalinShipment'=>$id), array('KD_ITMBYR'=>$kd), 'logistik_d_plainshipment');
        //$delete2
        $respon['code'] = 1;
        $respon['message'] = 'Data gagal dihapus';
        if($delete != null){
            $respon['code'] = 0;
            $respon['message'] = 'Data berhasil dihapus';
        }
        echo json_encode($respon);
    }
}