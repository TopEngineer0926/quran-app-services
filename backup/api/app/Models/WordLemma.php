<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $word_id
 * @property int $lemma_id
 * @property int $position
 * @property Lemma $lemma
 * @property Word $word
 */
class WordLemma extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'word_lemma';

    /**
     * @var array
     */
    protected $fillable = ['position'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lemma()
    {
        return $this->belongsTo('App\Lemma', null, 'lemma_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function word()
    {
        return $this->belongsTo('App\Word', null, 'word_id');
    }
}
