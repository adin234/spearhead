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
	
	/* Private Properties
	-------------------------------*/
	/* Magic
	-------------------------------*/
	/* Public Methods
	-------------------------------*/
	public function render() {
		if(isset($_POST) && !empty($_POST)) {
			$this->validateData($_POST);
			//die(json_encode($_POST));
		}
		
		$this->_body['activeSidebar'] = 'form-client';
		return $this->_page();
	}
	
	/* Protected Methods
	-------------------------------*/
	/* Private Methods
	-------------------------------*/
}
