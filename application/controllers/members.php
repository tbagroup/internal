<?php

class Members extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		#$this->output->enable_profiler(TRUE);
	}

	public function index() {
		gatekeeper();
		
		$head = array(
			'title' => 'Members',
		);
		
		// ToDo: Pagination? Tablesorter?
		$members = $this->Member_model->get_all();

		$this->load->view('header', $head);
		$this->load->view('members/list', array('members' => $members));
		$this->load->view('footer');

	}
	
	public function search() {
		gatekeeper();
		
		$head = array(
			'title' => 'Search Result',
		);
		
		// If POST is valid
		if ($this->form_validation->run()) {
			$keyword = $this->input->post('search');
			
			// Load search model and search!
			$this->load->model('Search_model');
			$members = $this->Search_model->member($keyword);
			
		} else {
			$members = array();
		}
		
		$this->load->view('header', $head);
		$this->load->view('members/search', array('members' => $members));
		$this->load->view('footer');

	}
	
	public function export($type = '', $id = '') {
		gatekeeper();
	
		// If POST is valid - export based upon POST
		if ($this->form_validation->run()) {
		
			// Load model
			$this->load->model('Export_model');
			
			// Process the export based upon post
			$this->Export_model->export_post();
		
		// OR by type and id
		} elseif(in_array($type, array('group', 'member')) && !empty($id)) {
			
			// Load model
			$this->load->model('Export_model');
			
			// ToDo: Export...
			#$this->Export_model->export_group();
			#$this->Export_model->export_member();
			
		}
		
		$head = array(
			'title' => 'Export members',
		);
		
		$data = array(
			'groups' => $this->Group_model->get_all(),
			'export_fields' => $this->Member_model->export_fields(),
		);
		
		$this->load->view('header', $head);
		$this->load->view('members/export', $data);
		$this->load->view('footer');

	}
	
	public function view($member_id = '') {
		gatekeeper();
		
		// Check if member exists.
		if(!$member = $this->Member_model->get_member($member_id)) {
				error('The member doesn\'t exist!');
				redirect();
		}
		
		$head = array(
			'title' => 'View Member',
		);
		
		$data = array(
			'member' => $member,
		);
		
		$this->load->view('header', $head);
		$this->load->view('members/view', $data);
		$this->load->view('footer');
		
	
	}
	
	
	public function add() {
		gatekeeper();
		
		// If POST is valid
		if ($this->form_validation->run()) {
		
			// Add member to db
			$data = $this->input->post();
			$result = $this->Member_model->add_member($data);
			
			if($result) {
				message('Successfully added new member.');
				redirect('members/view/'.$this->db->insert_id());
			} else {
				error('Couldn\'t add new member, please try again.');
			}
		}
		
		$head = array(
			'title' => 'Add new member',
		);
		
		$this->load->view('header', $head);
		$this->load->view('members/add');
		$this->load->view('footer');
		
	}
	
	public function edit($member_id = '') {
		gatekeeper();
		
		// No member selected
		if(empty($member_id)) redirect('members/edit/'.member_id());
		
		// Get and validate member
		$member = $this->Member_model->get_member($member_id);
		if(!$member) {
			error('That member doesn\'t exist!');
			redirect();
		}
		
		// If POST is valid
		if ($this->form_validation->run('members/edit')) {
		
			// Get POST-data fields.
			$data = $this->input->post();
			
			// Update member in database.
			$result = $this->Member_model->update_member($member_id, $data);
			
			if($result) {
				message('Successfully updated member.');
				redirect('members/view/'.$member_id);
			} else {
				error('Couldn\'t update member, please try again.');
			}
		}
		
		$head = array(
			'title' => 'Edit member',
		);
		
		$this->load->view('header', $head);
		$this->load->view('members/edit', array('member' => $member));
		$this->load->view('footer');
	}
	
	public function group_switch($member_id = '', $group_name = '') {
		gatekeeper();

		if(empty($member_id)) {
			error('Invalid member id');
			redirect();
		} elseif(empty($group_name)) {
			error('Invalid group name');
			redirect();
		}
		
		// Modify group membership for member
		$return = $this->Group_model->group_switch($member_id, $group_name);
		
		if(!$return) {
			error('Couldn\'t change the group membership!');
			redirect('/members/view/'.$member_id);
		}

		message('Membership of group successfully updated.');
		redirect('/members/view/'.$member_id);

	}

	public function _validate_country($str) {
	
		// Validate country against dbconfig
		if(!empty($str)) {
		
			// Get countries
			$countries = (array)$this->dbconfig->countries;
			
			// Validate country
			if(array_key_exists($str, $countries)) {
				return true;
			}
			
			$this->form_validation->set_message('_validate_country', 'The field %s doesn\'t contain a valid option.');
			return false;
			
		}
		
	}
	
}
