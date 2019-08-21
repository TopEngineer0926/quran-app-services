<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $author_id
 * @property int $id
 * @property string $url
 * @property string $name
 * @property Resource[] $resources
 */
class Author extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'author';

    /**
     * @var array
     */
    protected $fillable = ['url', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources()
    {
        return $this->hasMany('App\Resource', null, 'author_id');
    }
}
