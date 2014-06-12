<?php //-->
/*
 * This file is part a custom application package.
 */

/**
 * Default logic to output a page
 */
class Front_Page_Lists_Client extends Front_Page {
  /* Constants
  -------------------------------*/
  /* Public Properties
  -------------------------------*/
  /* Protected Properties
  -------------------------------*/
  protected $_title = 'Client List';
  protected $_class = 'client-list';
  protected $_template = '/lists/client.phtml';

  /* Private Properties
  -------------------------------*/
  /* Magic
  -------------------------------*/
  /* Public Methods
  -------------------------------*/
  public function render() {
    $this->_body['clients'] = $this->getFirearms();
    $this->_body['activeSidebar'] = 'list-client';

    if(isset($_GET['ajax'])) {
      die(json_encode($this->_body['clients']));
    }

    return $this->_page();
  }

  protected function getFirearms() {
    $database = front()->database();
    $search = $database->search('client');
    if(isset($_GET['query'])) {
      
    }

    return $search->getRows();
  }

  /* Protected Methods
  -------------------------------*/
  /* Private Methods
  -------------------------------*/
}
