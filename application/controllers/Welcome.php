<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		if($this->session->userdata('get_login') != 1){
			redirect('Login');
		}else{
			redirect('Main/dashboard');
		}
	}

}
