<?php

namespace Modules\Pos\Http\Controllers;

use App\Jobs\Income\CreateInvoiceItem;
use App\Models\Auth\User;
use App\Models\Common\Item;
use App\Models\Income\Customer;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceItem;
use App\Models\Setting\Currency;
use App\Traits\Incomes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PosController extends Controller
{
    use Incomes;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $currency = Currency::where('enabled', '=', 1)->first();

        return view('pos::index', compact('currency'));
    }

    public function best()
    {
        $itens = Item::all();

        return response()->json($itens, 200);
    }

    public function customers(Request $request)
    {
        $data = $request->all();
        
        $itens = Customer::where('name', 'like', "%{$data['query']}%")->get();

        return response()->json($itens, 200);
    }

    public function addCustomer(Request $request){
        $data = $request->all();

        if(isset($data['id']))
            $customer = Customer::find($data['id']);
        else
            $customer = new Customer();

        $customer->name = $data['name'];
        $customer->email = $data['email'];
        $customer->phone = $data['phone'];
        $customer->website = $data['website'];
        $customer->address = $data['address'];
        $customer->save();
        
        return response()->json($customer, 200);
    }

    public function sale(Request $request){

        if (empty($request['amount'])) {
            $request['amount'] = 0;
        }

        if(empty($request['customer'])) {
            $customer = self::getUserDefault();
        }else{
            $customer = (object) $request['customer'];
        }

        $data = $request->input();
        $data['company_id'] = session('company_id');
        $data['invoice_number'] = $this->getNextInvoiceNumber();
        $data['status_code'] = 'draft';
        $data['invoice_status_code'] = 'paid';
        $data['invoiced_at'] = date("Y-m-d");
        $data['due_at'] = date("Y-m-d");
        $data['currency_code'] = $customer->currency_code;
        $data['currency_rate'] = 0;
        $data['customer_id'] = $customer->id;
        $data['customer_name'] = $customer->name;
        $data['customer_email'] = $customer->email;

        $invoice = Invoice::create($data);

        if ($request['producs']) {
            foreach ($request['producs'] as $item) {

                $invoiceItem = new InvoiceItem();
                $invoiceItem->company_id = session('company_id');
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->item_id = $invoice->id;
                $invoiceItem->name = $item['name'];
                $invoiceItem->quantity = $item['quantity'];
                $invoiceItem->price = $item['sale_price'];
                $invoiceItem->total = $item['quantity'] * $item['sale_price'];
                $invoiceItem->tax = 0;
                $invoiceItem->save();


            }
        }
    }

    public function getUserDefault(){
        $user = User::where('name', '=', 'default')->first();

        if($user) {
            $customer = Customer::where('user_id', $user->id)->first();
        }else{
            $user = new User();
            $user->name = 'default';
            $user->email = 'default@email.com';
            $user->password = bcrypt(mt_rand());
            $user->save();

            $customer = new Customer();
            $customer->user_id = $user->id;
            $customer->name = $user->name;
            $customer->email = $user->email;
            $customer->company_id = session('company_id');
            $customer->currency_code = setting('general.default_currency');
            $customer->enabled = 1;
            $customer->save();
        }

        return $customer;
    }

    public function currencies(){
        $result = Currency::where('enabled', '=', 1)->get();

        return response()->json($result, 200);
    }
}
