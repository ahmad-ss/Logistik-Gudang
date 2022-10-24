<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function dataLogistikItem(){
		$draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $query = $this->crud->selectAllOrderby('logistik_item','id_item','asc');

        $data = [];

        foreach($query->result() as $r) {
            $row = array();

            $row[] = $r->kd_item;
            $row[] = $r->nama_item;
            $row[] = $r->jumlah;
            $row[] = $r->nama_satuan;
            // $row[] = $r->tanggal_input;
            // $row[] = $r->tanggal_update;

            $user_input = $this->crud->getDataWhere('users',array('id_user'=>$r->input_id_user))->row_array();
            $user_update = $this->crud->getDataWhere('users',array('id_user'=>$r->update_id_user))->row_array();

            $row[] = $user_input['nama'].' '.$r->tanggal_input;
            $row[] = $user_update['nama'].' '.$r->tanggal_update;

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

}