<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;


class Orders extends Authenticatable{

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'product_id',
		'invoice_id',
		'coupon_code',
		'discount',
		'product_name',
        'customer_name',
		'customer_email',
        'customer_mobile',
		'customer_address',
        'customer_city',
		'customer_state',
		'customer_country',
		'customer_zipcode',
		'order_date',
        'total',
        'dispatch_through',
        'order_status',
		'status',
		'payment_id',
		'token',
		'payer_id',
		'payment_status'
    ];


	public function GetRecordById($id){

		return $this::where('id', $id)->first();

	}

	public function UpdateRecord($Details){

		$Record = $this::where('id', $Details['id'])->update($Details);

		return true;

	}

	public function CreateRecord($Details){

		$Record = $this::create($Details);

		return $Record;

	}


}