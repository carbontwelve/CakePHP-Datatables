<?php
/**
 * jQuery DataTables Component.
 *
 *
 * PHP versions 5 - Known & Tested
 *
 * Copyright 2011, Simon Dann. (http://likepie.net)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2011, Simon Dann. (http://likepie.net)
 * @link          http://likepie.net/cakephp-jquery-datatables-componenthelper/ jQuery DataTables Component Project
 * @package       CakePHP-Datatables   
 * @since         CakePHP-Datatables v 1.0.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class DataTablesComponent extends Object {

	var $sLimit = null;
	var $sNames = null;
	var $model = null;

   /**
	* initialize function.
	* loads any and all linked models to the controller, this is quite inefficient and dumb atm
	* very lazy and should be fixed once someone comes up with a better solution.
	* @since     CakePHP-Datatables v 1.0.0
	*/
	function initialize(&$controller, $settings = array()) {
		$this->controller =& $controller;
		foreach ($this->controller->modelNames as $modelClass){
			$controller->loadModel($modelClass);
			$this->$modelClass = $controller->$modelClass;
		}
	}
	
	
   /**
	* output function.
	* echos out a json encoded array for the dataTables jQuery font end to pick up
	* yes once again lazyness has resulted in this being a simple echo and kill rather than
	* being passed on to a view, which should probably be returned via a url with a .json at the end.
	* @since     CakePHP-Datatables v 1.0.0
	*/
	function output ($dataTables = null){
		if ($dataTables){			
			
			// A way of getting the names of all the columns.
			if ( isset( $this->controller->params['url']['sNames'] ) ){
				$this->sNames = explode(',', $this->controller->params['url']['sNames'] );
			}
			
			// Are we sorting columns
			if ( isset( $this->controller->params['url']['iSortCol_0'] ) ){
				for ( $i=0 ; $i<intval( $this->controller->params['url']['iSortingCols'] ) ; $i++ ){
					if ( $this->controller->params['url'][ 'bSortable_'.intval( $this->controller->params['url']['iSortCol_'.$i] ) ] == "true" ){
						if (isset ($this->sNames)){
							$ordering[] = $dataTables['use'] . '.' . $this->sNames[ intval( $this->controller->params['url']['iSortCol_'.$i] ) ] . ' ' . $this->controller->params['url']['sSortDir_'.$i];
						}
					}
				}
			}
			
			// If the user is searching
			if ( $this->controller->params['url']['sSearch'] != "" ){
				for ( $i=0 ; $i<count($this->sNames) ; $i++ ){
					if ( $this->controller->params['url']['bSearchable_'.$i] == "true"){
						$conditions[$dataTables['use'] . '.' . $this->sNames[$i] . ' LIKE'] = '%' . $this->controller->params['url']['sSearch'] . '%';
					}
				}
			}
			
			if (isset($conditions) && !empty($conditions)){
				$finalconditions = array('OR'=>$conditions);
			}else{
				$finalconditions = '';
			}
			
			$n = array(
				'conditions' => $finalconditions,
				'recursive' => 1,
				'fields' => $this->sNames,
				'order' => $ordering,
				'limit' => $this->controller->params['url']['iDisplayLength'], 
				'offset'=> $this->controller->params['url']['iDisplayStart'], 
			);
			
			$rResult = $this->$dataTables['use']->find('all', $n);
			$iTotal = $this->$dataTables['use']->find('count');
			if ($this->controller->params['url']['sSearch']){
				$n['limit'] = null;
				$n['offset'] = null;
				$iFilteredTotal = $this->$dataTables['use']->find('count', array('conditions' => $finalconditions));
			}else{
				$iFilteredTotal = $iTotal;
			}
			
			$output = array(
				"aoColumns" => implode(',', $this->sNames),
				"sEcho" => intval($this->controller->params['url']['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);
			foreach ($rResult as $record){
				$row = array();
				for ( $i=0 ; $i<count($this->sNames) ; $i++ ){
					$row[] = $this->filter($this->sNames[$i], $record, $dataTables['use']);
				}
				/* Add buttons as an additional column?
				 * maybe in the form of:
				 * $dataTables['buttons'] = array(
				 * 		'view' => '/view/%id%',
				 *		'edit' => '/edit/%id$',
				 * );
				 * with urls being able to be hooked into cakes automagic black box :)
				 */
				$output['aaData'][] = $row;
			}
			echo json_encode( $output );
			die();
		}else{
			return false;
		}
	}
		
   /**
	* filter function.
	* Use this function to filter the output as provided by the find request, if you are using joins such as belongsTo,
	* or virtual fields with weird __ in their names you can filter them here and fix the output
	* the example below replaces public which is a boole from having a 0/1 output to having a no/yes output
	* @since     CakePHP-Datatables v 1.0.0
	*/
	function filter($input, $record, $tName){

        switch ($input){

            case 'public':
                $output = 'No';
                if ($record[$tName]['public'] == '1'){
                    $output = 'Yes';
                }
                return $output;
                break;
            default:
                return $record[$tName][$input];
                break;
        }
    }

}

?>
