<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $token_id
 * @property int $id
 * @property string $value
 * @property string $clean
 * @property Word[] $words
 */
class Token extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'token';

    /**
     * @var array
     */
    protected $fillable = ['value', 'clean'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function words()
    {
        return $this->hasMany('App\Word', null, 'token_id');
    }
}
