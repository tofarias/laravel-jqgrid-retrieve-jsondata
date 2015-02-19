<?php

use Illuminate\Database\Eloquent\Builder;

class JQGrid{
	
	protected $builder;
	protected $fields;
	protected $records;
	protected $page;
	protected $sidx;
	protected $sord;
	protected $totalPages;
	protected $skip;
	protected $take;
	protected $builderCriteria;
	
	protected $data;
	
	public function __construct(Builder $builder, Array $fields, $records, $page, $sidx, $sord = 'asc')
	{
		$c = $builder->count();
		$this->builder = $builder;
		$this->fields  = $fields;
		$this->records = $records;
		$this->page = $page;
		
		$this->totalPages = 0;
		$this->sidx = $sidx;
		$this->sord = $sord;

		$this->build();
	}
	
	private function buildTotalPages()
	{
		$this->totalPages = ceil($this->builder->count()/$this->records);
		
		if( $this->page > $this->totalPages ){
			$this->page = $this->totalPages;
		}
	}
	
	private function buildQueryLimits()
	{
		$this->skip = $this->records * $this->page - $this->records;
		$this->take = $this->records;
	}
	
	private function buildQueryCriteria()
	{
		$this->builderCriteria = $this->builder
									   ->orderBy( $this->sidx, $this->sord )
									   ->skip( $this->skip )
									   ->take( $this->take )
									   ->get( $this->fields );
	}
	
	#public abstract function buildRows();
	
	private function buildData()
	{
		$this->data['page'] = $this->page;
		$this->data['total'] = $this->totalPages;
		$this->data['sidx'] = $this->sidx;
		$this->data['records'] = $this->builderCriteria->count();
		
		/* for ( $i = 0; $i < $this->data['records']; $i++) {
				
			$this->data['rows'][$i]['id'] = 0;
			$this->data['rows'][$i]['cell'] = [999];
		} */
	}
	
	protected function build()
	{
		$this->data = Array();
		$this->buildTotalPages();
		$this->buildQueryLimits();
		$this->buildQueryCriteria();
		$this->buildData();
	}
	
	public function getData()
	{
		return $this->data;
	}
}
