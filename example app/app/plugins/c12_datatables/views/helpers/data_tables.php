<?php

class DataTablesHelper extends AppHelper {

	var $out = null;
	var $helpers = array('Html');

	function display ($data){

		$this->out = null;

		$this->out .= '<table id="datatable" cellpadding="0" cellspacing="0">';
	       $this->out .= ' <thead>';
	       $this->out .= '     <tr>';
		foreach ($data['aColumns'] as $key => $value){
			if (is_numeric($key)){ 
				$this->out .= '          <th>'.$value.'</th>';
			}else{
				$this->out .= '          <th>'.$key.'</th>';
			}

		}
	       $this->out .= '    </tr>';
	       $this->out .= '</thead>';
	       $this->out .= '<tbody>';
	       $this->out .= '</tbody>';
		$this->out .= '</table>';

		return ($this->out);

	}

	// The default settings enable infinate scroll (lazy loading - good for large tables) and server-sde
	// You will probably only need to edit sScroll and possibly iDisplayLength and bJqueryUI

	function javaScript ($data, $options=array()){

		$this->out = null;

		if (!isset ($options['sScrollY']) ){ $options['sScrollY'] = '300px'; }
		if (!isset ($options['bScrollInfinite']) ){ $options['bScrollInfinite'] = true; }
		if (!isset ($options['bProcessing']) ){ $options['bProcessing'] = false; }
		if (!isset ($options['bLengthChange']) ){ $options['bLengthChange'] = false; }
		if (!isset ($options['bScrollCollapse']) ){ $options['bScrollCollapse'] = false; }
		if (!isset ($options['iDisplayLength']) ){ $options['iDisplayLength'] = 50; }
		if (!isset ($options['bServerSide']) ){ $options['bServerSide'] = true; }
		if (!isset ($options['bJQueryUI']) ){ $options['bJQueryUI'] = true; }

		if ($options['bScrollInfinite']){$options['bScrollInfinite'] = 'true';}else{$options['bScrollInfinite'] = 'false';}
		if ($options['bProcessing']){$options['bProcessing'] = 'true';}else{$options['bProcessing'] = 'false';}
		if ($options['bLengthChange']){$options['bLengthChange'] = 'true';}else{$options['bLengthChange'] = 'false';}
		if ($options['bScrollCollapse']){$options['bScrollCollapse'] = 'true';}else{$options['bScrollCollapse'] = 'false';}
		if ($options['bServerSide']){$options['bServerSide'] = 'true';}else{$options['bServerSide'] = 'false';}
		if ($options['bJQueryUI']){$options['bJQueryUI'] = 'true';}else{$options['bJQueryUI'] = 'false';}

		$this->out .= '<script type="text/javascript" charset="utf-8">';
		$this->out .= '	$(document).ready(function() {';
        	$this->out .= '		$("#datatable").dataTable( {';
		$this->out .= '			"sScrollY": "'. $options['sScrollY'] .'",';
	       $this->out .= '			"bScrollInfinite": '. $options['bScrollInfinite'] .',';
	       $this->out .= '			"bProcessing": '. $options['bProcessing'] .',';
	       $this->out .= '			"bLengthChange": '. $options['bLengthChange'] .',';
	       $this->out .= '			"bScrollCollapse": '. $options['bScrollCollapse'] .',';
	       $this->out .= '			"iDisplayLength": '. $options['iDisplayLength'] .',';
	       $this->out .= '			"bServerSide": '. $options['bServerSide'] .',';
	       $this->out .= '			"bJQueryUI": '. $options['bJQueryUI'] .',';
	       $this->out .= '			"aaSorting": [[0,"asc"]],';
	       $this->out .= '			"aoColumns": [';
		foreach ($data['aColumns'] as $key => $value){
			if (is_numeric($key)){
				$this->out .= '{ "sName": "'.$value.'" },';
			}else{
				$this->out .= '{ "sName" : "' . $key . '", ';
				if (isset($value['sortable'])){
					if ($value['sortable']){
						$this->out .= '"bSortable": true, ';
					}else{
						$this->out .= '"bSortable": false, ';
					}
				}
				if (isset($value['searchable'])){
					if ($value['searchable']){
						$this->out .= '"bSearchable": true, ';
					}else{
						$this->out .= '"bSearchable": false, ';
					}
				}
				$this->out = substr($this->out,0,-2) . ' },';
			}
		}			
		$this->out .= '			],';
		$this->out .= '			"sAjaxSource": "' . $this->Html->url($data['sAjaxSource']) . '"';
		$this->out .= '		} );';
	    	$this->out .= '	} );';
		$this->out .= '</script>';

		return ($this->out);

	}
}

?>