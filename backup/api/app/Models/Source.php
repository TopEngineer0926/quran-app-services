<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $source_id
 * @property int $id
 * @property string $name
 * @property string $url
 * @property Resource[] $resources
 */
class Source extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'source';

    /**
     * @var array
     */
    protected $fillable = ['name', 'url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources()
    {
        return $this->hasMany('App\Resource', null, 'source_id');
    }
}
