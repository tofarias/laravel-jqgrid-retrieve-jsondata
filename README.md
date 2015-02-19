# laravel-jqgrid-retrieve-jsondata

This is based on this demo [Loading Data -> JSON Data](http://www.trirand.com/blog/jqgrid/jqgrid.html "Loading Data -> JSON Data").

## After that you can...
* Data paging;
* Sort a column by click in the column header;
* On grid load, sort by default field;

## Usage
* First, consider this simple *JQGrid* configuration:
```javascript
jQuery("#table").jqGrid({
	url: 'controller/fetch-data', // feel free to change
	datatype: 'json',
	sortname:'field-from-database',
	sortorder: 'desc',
	rowNum: 10,
	rowList:[10,50,100],
	gridview: true,
	rownumbers: true,
	rownumWidth: 20,
	colNames: [...],
	colmodel: [{...}]
	...
	...
	...
});
		
```

* Now you need to create a php class and **extends** the JQGrid class, and implement the method **buildRows** like this:
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
If you need to process the data from different databases you could create a classe for the database like: *ModelSqlServerGrid extends JQGrid*, ModelPostgresSqlGrid extends JQGrid supported by Laravel Eloquent ORM.

* Create a controller and implement the method defined in *url* parameter in *jqgrid*:

```php
class Controller{

	public function getFetchData()
	{
		// Here, you can use many 'wheres', 'joins', doesn't matter.
		// The important is return a builder object.
		$builder = $this->modelRepository->findAll();
		
		$fields = Array();
		$fields[] = 'field1';
		$fields[] = 'field2';
		$fields[] = 'field3';
		
		$grid = new ModelGrid( $builder, $fields, \Input::get('rows'), \Input::get('page'), \Input::get('sidx'), \Input::get('sord') );
		
		$data = $grid->buildRows()->getData();
		
		return \Response::json( $data );
	}
}
```
