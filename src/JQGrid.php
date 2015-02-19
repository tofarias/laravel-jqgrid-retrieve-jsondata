<?php

namespace CPLNet\Support\Grid;

use Illuminate\Database\Eloquent\Builder;

abstract class JQGrid{
	
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
		$this->builder = $builder;
		$this->fields  = $fields;
		$this->records = $records;
		$this->page = $page;
		
		$this->totalPages = 0;
		$this->sidx = $sidx;
		$this->sord = $sord;

		$this->build();
	}
	
	public abstract function buildRows();
	
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
	
	private function buildData()
	{
		$this->data['page'] = $this->page;
		$this->data['total'] = $this->totalPages;
		$this->data['sidx'] = $this->sidx;
		$this->data['records'] = $this->builderCriteria->count();
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
