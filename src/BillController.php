```php
class BillController extends \BaseController implements IGrid{

	private $fcfoRepo;
	private $contractRepo;
	private $orderRepo;
	private $tmovRepo;
	
	public function __construct(IFCFO $fcfo, IContract $contract, IOrder $order, ITMOV $tmov)
	{
		parent::__construct();
		
		$this->fcfoRepo = $fcfo;
		$this->contractRepo = $contract;
		$this->orderRepo = $order;
		$this->tmovRepo = $tmov;
	}

	public function getFetchData()
	{
		$contract = $this->contractRepo->findOrFail( \Input::get('contract_id') );
		$billsBuilder = $this->tmovRepo->findAll()->whereCostCenter( $contract->cost_center );
		
		$billGrid = new BillGrid( $billsBuilder,TMov::fieldsInSelect(), \Input::get('rows'), \Input::get('page'), \Input::get('sidx'), \Input::get('sord') );
		$data = $billGrid->buildRows()->getData();
		
		return \Response::json( $data );
	}
}
```
