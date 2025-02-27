<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RemissionDetail extends Model
{
    public const CREATED_AT = 'createdat';

    public const UPDATED_AT = 'updatedat';

    protected $table = 'remissiondetail';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'idremission',
        'idproduct',
        'reference',
        'quantity',
        'packages',
        'price',
        'total',
        'colors',
        'minimum',
        'createdby',
        'updatedby',
        'createdat',
        'updatedat',
    ];

    /**
     * @return HasOne<Product,$this>
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'idproduct');
    }
}
