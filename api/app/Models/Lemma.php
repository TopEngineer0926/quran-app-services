<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $lemma_id
 * @property int $id
 * @property string $value
 * @property string $clean
 * @property WordLemma[] $wordLemmas
 */
class Lemma extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'lemma';

    /**
     * @var array
     */
    protected $fillable = ['value', 'clean'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordLemmas()
    {
        return $this->hasMany('App\WordLemma', null, 'lemma_id');
    }
}
