<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    public const CREATED_AT = 'createdat';

    public const UPDATED_AT = 'updatedat';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'user',
        'email',
        'idrole',
        'password',
        'active',
        'idcustomer',
        'system',
        'createdat',
        'updatedat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return HasOne<Role,$this>
     */
    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'idrole');
    }

    /**
     * @return HasOne<Customer,$this>
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'idcustomer');
    }
}
