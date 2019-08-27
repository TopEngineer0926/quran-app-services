<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $reciter_id
 * @property int $id
 * @property string $path
 * @property string $slug
 * @property string $english
 * @property string $arabic
 * @property Recitation[] $recitations
 */
class Reciter extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'reciter';

    /**
     * @var array
     */
    protected $fillable = ['path', 'slug', 'english', 'arabic'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recitations()
    {
        return $this->hasMany('App\Recitation', null, 'reciter_id');
    }
}
