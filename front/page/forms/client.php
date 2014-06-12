<?php //-->
/*
 * This file is part a custom application package.
 */

/**
 * Default logic to output a page
 */
class Front_Page_Forms_Client extends Front_Page {
	/* Constants
	-------------------------------*/
	/* Public Properties
	-------------------------------*/
	/* Protected Properties
	-------------------------------*/
	protected $_title = 'Client Form';
	protected $_class = 'client-form';
	protected $_template = '/forms/client.phtml';

	protected $_db;

	/* Private Properties
	-------------------------------*/
	/* Magic
	-------------------------------*/
	/* Public Methods
	-------------------------------*/
	public function __construct() {
			$this->_db = front()->database();
	}

	public function render() {
		if(isset($_POST) && !empty($_POST)) {
			$errors = $this->validateData($_POST);
			if(empty($errors)) {
				$latest = $this->_db->model($_POST)->save('client')->get();
				foreach($latest['client_contact'] as &$contact) {
					$contact = array(
						'client_contact_client' 	=> $latest['client_id'],
						'client_contact_contact'	=> $contact
					);
				}

				$this->_db->insertRows('client_contact', $latest['client_contact']);

				foreach($latest['client_staff'] as &$staff) {
					$staff = array(
						'client_staff_client' => $latest['client_id'],
						'client_staff_staff'	=> $staff['user'],
						'client_staff_title'	=> $staff['title'],
						'client_staff_salary' => $staff['salary']
					);
				}

				$this->_db->insertRows('client_staff', $latest['client_staff']);

				foreach($latest['client_firearms'] as &$firearm) {
					$firearm = array(
						'client_firearm_client_firearm' => $firearm,
						'client_firearm_client_client' => $latest['client_id']
					);
				}

				$this->_db->insertRows(
					'client_firearm_client',
					$latest['client_firearms']
				);

				foreach($latest['client_equipment'] as &$equipment) {
					$equipment = array(
						'client_equipment_equipment' 	=> $equipment,
						'client_equipment_client' 		=> $latest['client_id']
					);
				}

				$this->_db->insertRows('client_equipment', $latest['client_equipment']);

			}

			die(json_encode($_POST));
		}

		$this->_body['activeSidebar'] = 'form-client';
		return $this->_page();
	}

	public function validateData($data) {

		return array();
	}

	/* Protected Methods
	-------------------------------*/
	/* Private Methods
	-------------------------------*/
}
