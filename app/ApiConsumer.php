<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiConsumer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'api_token', 'reset_key', 'level'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['api_token'];

    /**
     * Determine whether or not the ApiConsumer instance is a system account.
     *
     * @return bool
     */
    public function isSystemAccount()
    {
        // Here, we are using level 9 as the system level, but any desired logic can be used here
        return $this->level === 9;
    }
}
