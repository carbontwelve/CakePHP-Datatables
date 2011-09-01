<?php
class PartsController extends AppController {

	var $name = 'Parts';
	var $components = array('c12_datatables.DataTables', 'RequestHandler');
	var $helpers = array('c12_datatables.DataTables');

	function index() {

		// Values sortable and searchable are true by default.
		$dataTables = array(
			'sTable' => 'Part',
			'aColumns' => array(
				'id' => array(
					'sName' => 'id',
					'sortable' => true,
					'searchable' => true,
					'aaSorting' => 'desc',
				),
				'modified' => array(
					'sName' => 'modified',
					'searchable' => false,
				),
				'name',
				'part_number',
				'price',
				'public' => array(
					'sName' => 'public',
					'searchable' => false,
				)
			),
			'use' => 'Part', // Could this be the same as sTable? if so remove it and fix the component
			'sAjaxSource' => array(
				'action' => 'index'
			)
		);

		if ($this->RequestHandler->isAjax()) {
			$this->DataTables->output($dataTables);
		}else{
			$this->set('dataTables', $dataTables);
		}
	}

	function generate(){

		$names = array("Engine Part", "Gear Box Thingy", "Random Electronic Component", "Key Type", "Exhaust End", "Chrome Doobrey", "Black Magic", "Expensive Authentic Part");
		$desc = array(
			"Special component, designed for special purpose",
			"Custom Vehicle Part",
			"Super Magic, Ultra special thingy",
			"Without this the floor will fall off!",
			"Magic Unicorns Magic made this",
			"Dont touch the hot end, its hot"
		);
		for ($i = 1; $i <= 1000; $i++) {

			$this->data = array();

			$this->data['Part']['name'] = $names[rand(0,7)];
			$this->data['Part']['description'] = $desc[rand(0,5)];
			$this->data['Part']['price'] = rand(25,1200);
			$this->data['Part']['public'] = rand(0,1);
			$this->data['Part']['part_number'] = $this->randomNumbers() . '-' . $this->randomNumbers() . '-' . $this->randomNumbers() . '-' . $this->randomNumbers();

			$this->Part->create();
			if ($this->Part->save($this->data)){
				echo $i . ' Saved<br/>';
			}
		}

		die();


	}

	function randomNumbers($length = 4){

		$output = '';
		$abc= array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
		$num= array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
			for ($i = 1; $i <= $length; $i++){
				$p = rand(0,1);
				if ($p == 1){
					$output .= $abc[rand(0,25)];
				}else{
					$output .= $num[rand(0,9)];
				}
			}

		return $output;


	}

}
