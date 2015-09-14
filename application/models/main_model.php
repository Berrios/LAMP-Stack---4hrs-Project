<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function register_user($input_data)
	{
		$this->load->library("form_validation");
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
			$this->form_validation->set_rules('register_password', 'Password', 'required|matches[confirm_pass]|min_length[8]');
			$this->form_validation->set_rules('confirm_pass', 'Confirm Password', 'required');
			$this->form_validation->set_rules('bday','Date of Birth','required|callback_checkDateFormat'); 
			$validate = $this->form_validation->run();
		
		if ($validate == TRUE)
		{
			$user_data = array(
				'name' => $this->input->post('name'),
				'alias' => $this->input->post('alias'),
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('register_password')),
				'bday' => $this->input->post('bday'),
				'favored_id' => NULL
 			); 
		
			$user_query = "INSERT INTO users (name, alias, email, password, bday, favored_id, created_at) 
						   VALUES (?,?,?,?,?,?,NOW())";

			$insert = $this->db->query($user_query, $user_data);
			$this->db->insert_id($insert);		
			$user_data['success'] = TRUE;
			$user_data['success_message'] = "You have Successfully Registered!";
		}
			else 
		{
			$user_data['success'] = FALSE;
			$user_data['register_messages'] = validation_errors();
		}
		return $user_data;	
	}


	public function add_quotes($data)
	{	
		$person = $data['person'];
		$quote = $data['quote'];
		$id = $data['id'];

		$this->load->library("form_validation");
			$this->form_validation->set_rules('person', 'Person', 'required');
			$this->form_validation->set_rules('quote', 'Quote', 'required');
			$validate = $this->form_validation->run();

		if($validate == TRUE)
		{
			$data = array(
				'person' => $person,
				'quote' => $quote,
				'created_at' => date("D M j G:i:s T Y")
			); 
		
			$user_query = "INSERT INTO quotes (person, quote, created_at) 
						   VALUES (?,?,NOW())";			   

			$insert = $this->db->query($user_query, $data);
			$quotes_data['message_id'] = $this->db->insert_id($insert);	
		}
		else
		return $quotes_data['error_message'] = validation_errors();
		
	return $quotes_data['message_id'];	
	}

	public function get_user($email)
	{
		$query = $this->db->query("SELECT * FROM users 
								   WHERE email = ?", array($email))->row_array();
		return $query;
	}


	public function get_quotes($id)
	{
	 	$query = $this->db->query("SELECT quote, person, id 
							    	FROM quotes 
							    	WHERE id NOT IN (SELECT quotes_id 
							    		FROM favored 
							    		WHERE users_id = $id)")->result_array();
		 return $query;	
		
	
	}

	public function get_favorites($id)
	{
		$query = $this->db->query("SELECT *
		 						   FROM quotes
		 						   LEFT JOIN favored ON favored.quotes_id = quotes.id
		 						   WHERE favored.users_id = $id")->result_array();
		 return $query;
	}


	public function add_favorites($message_id)
	{   
		$id = $this->session->userdata('id');
		$fav_data = array(
				'users_id' => $id,
				'quotes_id' => $message_id
	 			); 

			$user_query = "INSERT INTO favored (users_id, quotes_id)
						   VALUES (?,?)";

		    $insert = $this->db->query($user_query, $fav_data);
		    $this->db->insert_id($insert);
	}

	public function unfavored($message_id)
	{
		$id = $this->session->userdata('id');

		$this->db->query("DELETE 
						 	FROM favored 
						 	WHERE users_id = $id 
						 	AND quotes_id = $message_id");
	}
}


