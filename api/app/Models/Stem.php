<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $stem_id
 * @property int $id
 * @property string $value
 * @property string $clean
 * @property WordStem[] $wordStems
 */
class Stem extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'stem';

    /**
     * @var array
     */
    protected $fillable = ['value', 'clean'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordStems()
    {
        return $this->hasMany('App\WordStem', null, 'stem_id');
    }
}
