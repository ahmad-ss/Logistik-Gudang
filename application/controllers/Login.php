<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index(){
		// Deletes cache for the currently requested URI
		$this->output->delete_cache();
		$this->session->sess_destroy();
		$this->load->view('login');
	}

	public function getLogin(){
		$this->form_valid->set_rules('username','<b class="text-uppercase">Username</b>','required');
		$this->form_valid->set_rules('password','<b class="text-uppercase">Password</b>','required');

		if($this->form_valid->run() == FALSE){
			echo '<div class="alert alert-danger">'.validation_errors().'</div>';
		}else{
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$cek_auth = $this->crud->getDataWhere('users',array('username'=>strtolower($username),'password'=>strtolower($password)));

			if($cek_auth->num_rows() > 0){
				$my_auth = $cek_auth->row_array();
				// Set session
				$set_sess = array(
					'id_user'=>$my_auth['id_user'],
					'nama_user'=>$my_auth['nama'],
					'username_user'=>$my_auth['username'],
					'get_login'=>1
				);

				$this->session->set_userdata($set_sess);
				
				echo '<div class="alert alert-success">Welcome <b>'.$username.'</b></div>';
			}else{
				echo '<div class="alert alert-warning">Username / Password incorrect!</div>';
			}
		}
	}

	public function getLogout(){
		// Deletes cache for the currently requested URI
		$this->output->delete_cache();
		$this->session->sess_destroy();
		redirect(base_url());
	}

}
