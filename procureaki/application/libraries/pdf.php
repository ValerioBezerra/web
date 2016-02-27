<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require( APPPATH.'/third_party/fpdf/fpdf.php');
class Pdf extends FPDF {
  function __construct($params) {
    parent::__construct($params['orientation'], $params['unit'], $params['size']);
  }
}
?>