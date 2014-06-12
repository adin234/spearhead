<?php //-->
/*
 * This file is part a custom application package.
 */

/**
 * Default logic to output a page
 */
class Front_Page_Lists_Staff extends Front_Page {
	/* Constants
	-------------------------------*/
	/* Public Properties
	-------------------------------*/
	/* Protected Properties
	-------------------------------*/
	protected $_title = 'Staff List';
	protected $_class = 'staff-list';
	protected $_template = '/lists/staff.phtml';
	
	/* Private Properties
	-------------------------------*/
	/* Magic
	-------------------------------*/
	/* Public Methods
	-------------------------------*/
	public function render() {
		$this->_body['staff'] = $this->getStaff();
		$this->_body['activeSidebar'] = 'list-staff';

		if(isset($_GET['ajax'])) {
			die(json_encode($this->_body['staff']));
		}

		return $this->_page();
	}

	protected function getStaff() {
		$database = front()->database();
		$search = $database->search('users');
		if(isset($_GET['query'])) {
			$search->addFilter(
				'user_lastname like %s '.
					'OR user_firstname like %s '.
					'OR user_serial like %s ',
				'%'.$_GET['query'].'%',
				'%'.$_GET['query'].'%',
				'%'.$_GET['query'].'%');
		}

		return $search->getRows();
	}

	/* Protected Methods
	-------------------------------*/
	/* Private Methods
	-------------------------------*/
}
