<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $word_id
 * @property int $stem_id
 * @property int $position
 * @property Stem $stem
 * @property Word $word
 */
class WordStem extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'word_stem';

    /**
     * @var array
     */
    protected $fillable = ['position'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stem()
    {
        return $this->belongsTo('App\Stem', null, 'stem_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function word()
    {
        return $this->belongsTo('App\Word', null, 'word_id');
    }
}
