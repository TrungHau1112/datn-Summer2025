<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScope;

class Order extends Model
{
    use HasFactory, QueryScope;

    protected $table = 'orders';

    protected $fillable = [
        'id',
        'code',
        'name',
        'phone',
        'email',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'note',
        'total',
        'payment_status',
        'payment_method_id',
        'status',
        'fee_ship',
        'user_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    public function getWithPaginateBy($perPage = 10)
    {
        return $this->paginate($perPage);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // public function shipping(){
    //     return $this->belongsTo(Shipping::class, 'shipping_id');
    // }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
    public function payment()
    {
        return $this->hasOne(OrderPayment::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'code');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'code');
    }
    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id', 'code');
    }
    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
