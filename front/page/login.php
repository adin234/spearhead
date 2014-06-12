<?php //-->
/*
 * This file is part a custom application package.
 */

/**
 * Default logic to output a page
 */
class Front_Page_Login extends Front_Page {
	/* Constants
	-------------------------------*/
	/* Public Properties
	-------------------------------*/
	/* Protected Properties
	-------------------------------*/
	protected $_title = 'Login';
	protected $_class = 'login';
	protected $_template = '/login.phtml';

	/* Private Properties
	-------------------------------*/
	/* Magic
	-------------------------------*/
	/* Public Methods
	-------------------------------*/
	public function render() {
		$error = array();
		$data = array();

		if(isset($_POST) && !empty($_POST)) {
			$data = $_POST;
			$error = $this->validateLogin($_POST);
			if(empty($error)) {
				header("Location: /dashboard");
				exit;
			}
		}

		$this->_body['errors'] = $error;
		$this->_body['data'] = $data;
		return $this->_page();
	}

	protected function validateLogin($data) {
		$errors = array();
		if(!strlen($data['user_email'])) {
			$errors['user_email'] = 'Email not provided';
		}

		if(!strlen($data['user_password'])) {
			$errors['user_password'] = 'Password not provided';
		}

		if(empty($errors) && !$this->login($data)) {
			$errors['login'] = 'Invalid Login info';
		}

		return $errors;
	}

	protected function login($data) {
		$database = front()->database();
		return count($database->search('users')
			->filterByUserEmail($data['user_email'])
			->filterByUserPassword(md5($data['user_password']))
			->getRows());
	}

	/* Protected Methods
	-------------------------------*/
	/* Private Methods
	-------------------------------*/
}
