<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Remission extends Model
{
    public const CREATED_AT = 'createdat';

    public const UPDATED_AT = 'updatedat';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'consecutive',
        'idcustomer',
        'total',
        'totalpackages',
        'createdby',
        'updatedby',
        'createdat',
        'updatedat',
    ];

    /**
     * @return HasOne<Customer,$this>
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'idcustomer');
    }
}
