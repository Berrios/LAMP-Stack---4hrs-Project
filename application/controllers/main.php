<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	protected $view_data = array();
	protected $data = array();

	public function __construct()
	{
		parent::__construct();
		$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		$this->load->view("welcome.php");
	}

	public function register()
	{
		$input_data = $this->input->post();
		$this->load->model("Main_model");
		$user_data = $this->Main_model->register_user($input_data);
	
		if($user_data['success'] == TRUE){ 
			$this->session->set_flashdata('success', $user_data['success_message']); 
			redirect(base_url());
		}
		else
		{
			$this->session->set_flashdata("register_errors", $user_data['register_messages']);
			redirect(base_url());
		}
	}

	public function login()
	{
		
		$email = $this->input->post('email');
		$password = md5($this->input->post('login_password'));
		$this->load->model('Main_model');
		$user_data = $this->Main_model->get_user($email);

		$this->load->library('form_validation');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('login_password', 'Password', 'trim|required');

		$form_data = array();
		
		if($this->form_validation->run() == FALSE)
		{
			$form_data['error_message'] = validation_errors();
			$this->session->set_flashdata("login_errors", $form_data['error_message']);
		    redirect(base_url());
		}
		elseif(!$user_data)
		{
			$this->session->set_flashdata("login_errors", "<p>Invalid email</p>");
			redirect(base_url());
		}
		elseif($email == $user_data['email'] && $user_data['password'] == $password)
		{
			$user = array(
				'id' => $user_data['id'],
				'email' => $user_data['email'],
				'name' =>  $user_data['name'],
				'alias' => $user_data['alias'],
				'is_logged_in' => true
			);

			$current_user = $this->session->set_userdata($user);
			redirect("main/quotes/".$current_user);
		}
		else
		{
			$this->session->set_flashdata("login_errors", "<p>The is something wrong with your credentials</p>");
			redirect(base_url());
		}
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}


	public function quotes()
	{
		$id = $this->session->userdata('id');
		$this->load->model('Main_model');
		$this->view_data['get_quotes'] = $this->Main_model->get_quotes($id);
		$this->view_data['get_favorites'] = $this->Main_model->get_favorites($id);
		$this->load->view('quotes', $this->view_data);
	}
	public function add_quote($id)
	{
		$data['person'] = $this->input->post('person');
		$data['quote'] = $this->input->post('quote');
		$data['id'] = $this->session->userdata('id');
		$this->load->model("Main_model");
		$data['add_quote_data'] = $this->Main_model->add_quotes($data);
		redirect(base_url("main/quotes"));
	}

	public function add_to_list($message_id)
	{
		$this->load->model("Main_model");
		$this->view_data['favored_id'] = $this->Main_model->add_favorites($message_id);
		redirect(base_url("main/quotes"));
	}

	public function unfavored($message_id)
	{
		$this->load->model("Main_model");
		$this->Main_model->unfavored($message_id);
		redirect(base_url("main/quotes"));
	}

}

//end of main controller