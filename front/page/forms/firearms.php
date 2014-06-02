<?php //-->
/*
 * This file is part a custom application package.
 */

/**
 * Default logic to output a page
 */
class Front_Page_Forms_Firearms extends Front_Page {
	/* Constants
	-------------------------------*/
	/* Public Properties
	-------------------------------*/
	/* Protected Properties
	-------------------------------*/
	protected $_title = 'Firearms Form';
	protected $_class = 'firearms-form';
	protected $_template = '/forms/firearms.phtml';
	
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
				$database->model($_POST)
					->save('firearms');
				$this->addMessage('Successfully Added Firearm', 'success');
				header("Location: /forms/firearms");
				exit;
			}
		}
		
		$this->_body['activeSidebar'] = 'form-firearms';
		return $this->_page();
	}

	protected function validateInputs($data) {
		$errors = array();
		if(!strlen($data['firearm_title'])) {
			$errors['firearm_title'] = 'Firearm Title not provided.';
		}

		if(!strlen($data['firearm_serial'])) {
			$errors['firearm_serial'] = 'Firearm Serial not provided.';
		}

		return $errors;
	}
	
	/* Protected Methods
	-------------------------------*/
	/* Private Methods
	-------------------------------*/
}
