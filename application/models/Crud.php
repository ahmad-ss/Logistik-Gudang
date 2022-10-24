<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud extends CI_Model {

	public function getDataWhere($tbl,$data){
		return $this->db->get_where($tbl,$data);
	}

	public function insertDataSave($tbl,$data){
		$this->db->insert($tbl,$data);
		$last_id = $this->db->insert_id();
        if($this->db->affected_rows() > 0){
            $return = array(
                'code' => 0,
                'message' => "Data saved",
                'last_id' => $last_id
            );
        }
        else{
            $return = array(
                'code' => 1,
                'message' => "Data not saved"
            );
        }
        return $return;
	}

	public function getDataQuery($sql){
		return $this->db->query($sql);
	}

	public function updData($tbl,$where,$data){
        $this->db->where($where);
        $this->db->update($tbl,$data);
        if($this->db->affected_rows() > 0){
            $return = array(
                'code' => 0,
                'message' => "Update successful"
            );
        }
        else{
            $return = array(
                'code' => 1,
                'message' => "Update unsuccessful"
            );
        }
        return $return;
    }

    public function selectAll($tbl){
        return $this->db->get($tbl);
    }

    public function selectAllOrderby($tbl,$col,$order){
        $this->db->order_by($col,$order);
        return $this->db->get($tbl);
    }

    public function delData($w,$t){
        $this->db->where($w);
        $this->db->delete($t);
        return $this->db->affected_rows();
    }

}