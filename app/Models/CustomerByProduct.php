<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerByProduct extends Model
{
    public const CREATED_AT = 'createdat';

    public const UPDATED_AT = 'updatedat';

    protected $table = 'customerxproduct';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'idcustomer',
        'idproduct',
        'price',
        'createdby',
        'updatedby',
        'createdat',
        'updatedat',
    ];
}
