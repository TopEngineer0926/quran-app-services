<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $root_id
 * @property int $id
 * @property string $value
 * @property WordRoot[] $wordRoots
 */
class Root extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'root';

    /**
     * @var array
     */
    protected $fillable = ['value'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordRoots()
    {
        return $this->hasMany('App\WordRoot', null, 'root_id');
    }
}
