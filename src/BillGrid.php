<?php

class BillGrid extends JQGrid{
	
	public function __construct($builder, $fields, $records, $page, $sidx, $sord)
	{
		parent::__construct($builder, $fields, $records, $page, $sidx, $sord);
	}
	
	public function buildRows()
	{
		$billsCollection = $this->builderCriteria;
		
		foreach ($billsCollection as $key => $bill) {
			
			$fields = Array();
			$fields[] = $bill->column1;
			$fields[] = $bill->column2;
			$fields[] = $bill->column3;
			
			$this->data['rows'][$key]['id'] = $key;
			$this->data['rows'][$key]['cell'] = $fields;
		}
		
		return $this;
	}
}
