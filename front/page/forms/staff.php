<?php //-->
/*
 * This file is part a custom application package.
 */

/**
 * Default logic to output a page
 */
class Front_Page_Forms_Staff extends Front_Page {
	/* Constants
	-------------------------------*/
	/* Public Properties
	-------------------------------*/
	/* Protected Properties
	-------------------------------*/
	protected $_title = 'Staff Form';
	protected $_class = 'staff-form';
	protected $_template = '/forms/staff.phtml';
	
	/* Private Properties
	-------------------------------*/
	/* Magic
	-------------------------------*/
	/* Public Methods
	-------------------------------*/
	public function render() {
		$errors = array();
		if(isset($_POST) && !empty($_POST)) {
			$errors = $this->validateInputs($_POST);
			if(empty($errors)) {
				$database = front()->database();
				$_POST['user_type'] = 100;
				$_POST['user_created'] = date('Y-m-d H:i:s');
				$_POST['user_updated'] = date('Y-m-d H:i:s');
				$_POST['user_password'] = md5($_POST['user_password']);
				$database->model($_POST)
					->save('users');
				$this->addMessage('Successfully Added User', 'success');
				header("Location: /forms/staff");
				exit;
			}
		}
		
		$this->_body['errors'] = $errors;
		$this->_body['data'] = $_POST;
		$this->_body['activeSidebar'] = 'form-staff';
		return $this->_page();
	}

	protected function validateInputs($data) {
		$errors = array();
		if(!strlen($data['user_serial'])) {
			$errors['user_serial'] = 'ID not provided.';
			$this->addMessage('ID not provided.');
		}

		if(!strlen($data['user_email'])) {
			$errors['user_email'] = 'Email not provided.';
			$this->addMessage('ID not provided.');
		}

		if(!strlen($data['user_firstname'])) {
			$errors['user_firstname'] = 'First Name not provided.';
			$this->addMessage('ID not provided.');
		}

		if(!strlen($data['user_lastname'])) {
			$errors['user_lastname'] = 'Last Name not provided.';
			$this->addMessage('Last Name not provided.');
		}

		if(!strlen($data['user_password'])) {
			$errors['user_password'] = 'Password not provided.';
			$errors['confirm_password'] = 'Password not provided.';
			$this->addMessage('Password not provided.');
		}

		if(!isset($errors['user_password']) && $data['confirm_password'] != $data['user_password']) {
			$errors['user_password'] = 'Passwords do not match.';
			$errors['confirm_password'] = 'Passwords do not match.';
			$this->addMessage('Passwords do not match.');
		}

		return $errors;
	}
	
	/* Protected Methods
	-------------------------------*/
	/* Private Methods
	-------------------------------*/
}
