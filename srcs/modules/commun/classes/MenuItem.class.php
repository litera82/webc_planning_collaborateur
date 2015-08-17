<?php
/**
* @package		Reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/

/**
* Element d'un menu
* @package		Reghalal
* @subpackage	commun
*/
class MenuItem
{
	public $code;
	public $libelle;
	public $selectionne;
	public $url;

	/**
	* constructeur
	* @param string $lcode
	* @param string $llibelle
	* @param string $lurl
	* @param string $lselectionne
	*/
	function __construct($lcode, $llibelle, $lurl, $lselectionne){
		$this->code = $lcode;
		$this->libelle = $llibelle;
		$this->url = $lurl;
		$this->selectionne = $lselectionne;
	}

}
?>