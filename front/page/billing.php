<?php //-->
/*
 * This file is part a custom application package.
 */

/**
 * Default logic to output a page
 */
class Front_Page_Billing extends Front_Page {
  /* Constants
  -------------------------------*/
  /* Public Properties
  -------------------------------*/
  /* Protected Properties
  -------------------------------*/
  protected $_title = 'Billing';
  protected $_class = 'billing';
  protected $_template = '/billing.phtml';
  protected $bodyonly = true;

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
    $days = isset($_GET['days']) ? $_GET['days'] : 30;
    $clientId = front()->registry()->get('request', 'variables', '0');
    $client = $this->_db->search('client')->filterByClientId($clientId)->getRow();
    $this->_body['today'] = date("F j, Y");
    $this->_body['serviceFrom'] = $_GET['start'];
    $this->_body['serviceTo'] = $_GET['end'];
    $this->_body['contractPrice'] = $client['client_contract_price'];
    $start = date_create((string) date("Y-m-d", strtotime($_GET['start'])));
    $end = date_create((string) date("Y-m-d", strtotime($_GET['end'])));
    $this->_body['noDays'] = date_diff($end, $start)->days + 1;
    $this->_body['perDay'] = $client['client_contract_price'] / $days;
    $this->_body['total'] = number_format($this->_body['perDay'] * (date_diff($end, $start)->days + 1) * $client['client_guards'], 2);
    $this->_body['client'] = $client;

    return $this->_page();
  }

  /* Protected Methods
  -------------------------------*/
  /* Private Methods
  -------------------------------*/
}
