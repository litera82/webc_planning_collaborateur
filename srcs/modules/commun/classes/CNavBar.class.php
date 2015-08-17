<?php

/**
* Classe de gestion d'une Barre de Navigation
* @package Reghalal
* @subpackage commun
* @author NEOV
* @version 1
*
*/
class CNavBar
{

	var	$iNbPages ;
	var $iCurrPage ;
	var $iNextPage ;
	var $iPrevPage ;
	var $iPageSize ;
	var $iBarWidth ;
	var $iShowFirst ;
	var $iShowLast ;
	var $tiPages = array () ;
	
	/**
	* @param int $_iPageSize
	* @param int $_iBarWidth
	*/
	public function CNavBar ($_iPageSize, $_iBarWidth = 20)
	{
		$this->iPageSize = $_iPageSize ;
		$this->iBarWidth = $_iBarWidth ;
	}

    /**
	* normalisation d'une bar de navigation
	* @param	int $_iNbRecords
	*/
	public function normalizeBar ($_iNbRecords)
    {
		$this->iNbPages = ceil ($_iNbRecords / $this->iPageSize) ;
		while ($this->iCurrPage > $this->iNbPages)
		{
			$this->iCurrPage-- ;
		}

        if ($this->iNbPages <= 0)
        {
            $this->iNbPages = 1 ;
			$this->iCurrPage = 1 ;
        }
    }

	/**
	* fonction mergeBar
	*/
	public function mergeBar ()
	{
		$this->iShowFirst = 0 ;
		$this->iShowLast  = 0 ;
		$this->tiPages = array () ;
		if ($this->iNbPages > 1)
		{
			$iBarIndex = 0 ;
			for ($iIndex = 1 ; $iIndex <= $this->iNbPages ; $iIndex++)
			{
				if ($this->iNbPages > $this->iBarWidth)
				{
					if ((($iIndex >= ($this->iCurrPage - ($this->iBarWidth / 2))) && ($iIndex <= ($this->iCurrPage + ($this->iBarWidth / 2)))) || (($this->iCurrPage <= ($this->iBarWidth / 2)) && ($iIndex <= $this->iBarWidth)))
					{
						$this->tiPages[$iBarIndex++] = $iIndex ;
					}
				}
				else
				{
					$this->tiPages[$iBarIndex++] = $iIndex ;
				}
			}

			$this->iShowFirst = ($this->tiPages[0] > 1) && ($this->iCurrPage > 0) ? 1 : 0 ;
			$this->iShowLast = ($this->tiPages[0] + $this->iBarWidth) <= $this->iNbPages ? 1 : 0 ;

		}
		$this->iNextPage = $this->iCurrPage + 1 ;
		$this->iPrevPage = $this->iCurrPage - 1 ;
	}

}

?>