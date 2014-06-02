<?php //-->
/*
 * This file is part a custom application package.
 */

/**
 * Default logic to output a page
 */
class Front_Page_Lists_Firearms extends Front_Page {
	/* Constants
	-------------------------------*/
	/* Public Properties
	-------------------------------*/
	/* Protected Properties
	-------------------------------*/
	protected $_title = 'Firearms List';
	protected $_class = 'firearms-list';
	protected $_template = '/lists/firearms.phtml';
	
	/* Private Properties
	-------------------------------*/
	/* Magic
	-------------------------------*/
	/* Public Methods
	-------------------------------*/
	public function render() {
		$this->_body['firearms'] = $this->getFirearms();
		$this->_body['activeSidebar'] = 'list-firearms';

		if(isset($_GET['ajax'])) {
			die(json_encode($this->_body['firearms']));
		}

		return $this->_page();
	}

	protected function getFirearms() {
		$database = front()->database();
		$search = $database->search('firearms');
		if(isset($_GET['query'])) {
			$search->addFilter(
				'firearm_title like %s '.
					'OR firearm_serial like %s ',
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
