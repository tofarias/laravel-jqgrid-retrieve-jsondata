# laravel-jqgrid-retrieve-jsondata

This is based on this demo [Loading Data -> JSON Data](http://www.trirand.com/blog/jqgrid/jqgrid.html "Loading Data -> JSON Data").

## Usage

1. First you need to **extends** the JQGrid class, and implement the method **buildRows** like this:
```php
class ModelGrid extends JQGrid{
	
	public function __construct($builder, $fields, $records, $page, $sidx, $sord)
	{
		parent::__construct($builder, $fields, $records, $page, $sidx, $sord);
	}
  
  public function buildRows()
	{
		$collection = $this->builderCriteria;
		
		foreach ($collection as $key => $model) {
			
			$fields = Array();
			$fields[] = $model->column1;
			$fields[] = $model->column2;
			$fields[] = $model->column3;
			
			$this->data['rows'][$key]['id'] = $key;
			$this->data['rows'][$key]['cell'] = $fields;
		}
		
		return $this;
	}
}
```
## After that you can...
* Data paging;
* Column sort by click in the column header;
* On grid load default sort by specific field;
