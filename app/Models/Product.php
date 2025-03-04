<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const CREATED_AT = 'createdat';

    public const UPDATED_AT = 'updatedat';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'active',
        'minimunvalue',
        'createdby',
        'updatedby',
        'createdat',
        'updatedat',
    ];
}
