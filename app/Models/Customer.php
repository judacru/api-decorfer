<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
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
        'email',
        'phone',
        'cellphone',
        'address',
        'identification',
        'active',
        'special',
        'createdby',
        'updatedby',
        'createdat',
        'updatedat',
    ];
}
