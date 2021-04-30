<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $word_id
 * @property int $root_id
 * @property int $position
 * @property Root $root
 * @property Word $word
 */
class WordRoot extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'word_root';

    /**
     * @var array
     */
    protected $fillable = ['position'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function root()
    {
        return $this->belongsTo('App\Root', null, 'root_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function word()
    {
        return $this->belongsTo('App\Word', null, 'word_id');
    }
}
