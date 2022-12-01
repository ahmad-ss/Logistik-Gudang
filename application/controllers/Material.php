<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material extends CI_Controller {
    
    public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}
    
    public function dataMaterial(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $sql = '
		SELECT a.KD_ITMBYR,a.DESKRIPTION, a.id_Material, a.jumlah, b.nama_satuan, b.nama_item FROM logistik_matrial a 
		JOIN logistik_item b ON a.kd_item = b.kd_item ORDER BY a.DESKRIPTION ASC';
		$query = $this->crud->getDataQuery($sql);

        $data = [];
        $no = 1;
        $barang = "";

        foreach($query->result() as $r) {
            if($barang != $r->KD_ITMBYR){
                $no=1;
            }
                $row = array();
                $row[] = $no++;
                $row[] = $r->DESKRIPTION;
                $row[] = $r->nama_item;
                $row[] = $r->jumlah;
                $row[] = strtoupper($r->nama_satuan);
                $row[] = '<a class="btn btn-info btn-sm" href="'.base_url().'main/materialUpdate?id='.$r->id_Material.'"><i class="glyphicon glyphicon-pencil"></i></a>';
                $row[] = '<button class="btn btn-danger btn-sm" onclick="preHps('.$r->id_Material.')"><i class="glyphicon glyphicon-trash"></i></button>';
                $data[] = $row;
            $barang = $r->KD_ITMBYR;
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

    public function dataMaterialById(){
        $id = $this->input->get('id');
        $data = $this->crud->getDataWhere('logistik_matrial',array('id_Material'=>$id))->row_array();
        
        header('Access-Control-Allow-Origin: *');
        echo json_encode($data);
    }

    public function addMaterial(){
        // Insert data ke table logistik_matrial
        $post= $this->input;
        $Iloop = count($post->post('kditm'));
        for($i=0; $i<$Iloop; $i++){
            $data = array(
                'id_Brgpo' => $post->post('idBrg'),
                'id_Brg' => $post->post('idBrg'),
                'KD_ITMBYR' => $post->post('kdbrg'),
                'KD_ITEMVDR' => $post->post('kditmvdr'),
                'KD_ITEMSPL' => $post->post('kditmspl'),
                'DESKRIPTION' => $post->post('desc'),
                'DIMENSION' => $post->post('dimension'),
                'Kd_Satuan_Mst' => $post->post('kdstnMst'),
                'Satuan' => $post->post('satuan'),
                'id_item' => $post->post('iditm')[$i],
                'nama_item' => strtoupper($post->post('nmitm')[$i]),
                'kd_item' => strtoupper($post->post('kditm')[$i]),
                'id_satuan' => $post->post('idstn')[$i],
                'kd_satuan' => $post->post('kdstn')[$i],
                'nama_satuan' => strtoupper($post->post('nmstn')[$i]),
                'jumlah' => $post->post('jumlah')[$i],
                'id_satuan_m' => $post->post('idstn')[$i],
                'kd_satuan_M' => $post->post('kdstn')[$i],
                'nama_satuan_M' => strtoupper($post->post('nmstn')[$i]),
                'input_id_user' => $post->post('id_user'),
                'tanggal_input' => date('Y-m-d H:i:s')
            );
            $respon = $this->crud->insertDataSave('logistik_matrial', $data);
        }
        echo json_encode($respon);
    }

    public function updateMaterial(){
        // Update data material
        $id = $this->input->post('id_material');
        $id_user = $this->input->post('id_user');
        $jumlah = $this->input->post('jumlah');

        $data = array(
            'jumlah' => $jumlah,
            'update_id_user' => $id_user,
            'tanggal_update' => date('Y-m-d H:i:s')
        );
        $whr = array('id_Material' => $id);

        $respon = $this->crud->updData('logistik_matrial', $whr ,$data);
        
        if($respon['code'] == 0){
            echo '<div class="alert alert-success">'.$respon['message'].'</div>';
        }else{
            echo '<div class="alert alert-warning">Tidak ada update</div>';
        }
    }

    public function deleteMaterial(){
        // Hapus data material
        $id = $this->input->post('id');
        $whr = array('id_Material' => $id);
        $respon = $this->crud->delData( $whr, 'logistik_matrial');

        if($respon != null){
            echo '<div class="alert alert-success">Material berhasil terhapus</div>';
        }else{
            echo '<div class="alert alert-warning">Material tidak terhapus</div>';
        }
    }

    public function getDataBarang(){
        // untuk mengisi data Barang buyer
        $kditmbyr = $this->input->get('kditmbyr');
        // load data barang buyer
        $sql = 'SELECT * FROM brgpi WHERE KD_ITMBYR = "'.$kditmbyr.'" LIMIT 1';
        $data['barang'] = $this->crud->getDataQuery($sql)->result();
        // load data item
        $sql2 = 'SELECT * FROM logistik_matrial WHERE KD_ITMBYR = "'.$kditmbyr.'"';
        $data['detail'] = $this->crud->getDataQuery($sql2)->result();
        
        header('Access-Control-Allow-Origin: *');
        echo json_encode($data);
    }

    public function getDataItem(){
        // untuk mengisi data item 
        $kditm = $this->input->get('kditm');
        $sql = 'SELECT a.* , b.id_satuan FROM logistik_item a 
        JOIN logistik_satuan b ON a.kd_satuan = b.kd_satuan  
        WHERE kd_item = "'.$kditm.'" LIMIT 1';
        $data = $this->crud->getDataQuery($sql)->result();
        header('Access-Control-Allow-Origin: *');
        echo json_encode($data);
    }

    public function cekDataItm(){
        // Cek apakah data item ada atau belum ada pada list material
        $kditm = $this->input->get('kditm');
        $kdbyr = $this->input->get('kdbyr');
        $sql = 'SELECT * FROM logistik_matrial 
        WHERE KD_ITMBYR = "'.$kdbyr.'" AND kd_item = "'.$kditm.'"';
        $cek = $this->crud->getDataQuery($sql)->num_rows();
        
        $respon = array('code'=> 1, 'message'=>'Data sudah ada');
        if($cek == 0){
            $respon = array('code'=> 0, 'message'=>'Data belum ada');
        }
        header('Access-Control-Allow-Origin: *');
        echo json_encode($respon);
    }

}