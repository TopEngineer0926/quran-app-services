<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $recitation_id
 * @property int $id
 * @property int $reciter_id
 * @property int $style_id
 * @property boolean $is_enabled
 * @property Reciter $reciter
 * @property Style $style
 * @property File[] $files
 */
class Recitation extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'recitation';

    /**
     * @var array
     */
    protected $fillable = ['reciter_id', 'style_id', 'is_enabled'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reciter()
    {
        return $this->belongsTo('App\Reciter', null, 'reciter_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function style()
    {
        return $this->belongsTo('App\Style', null, 'style_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany('App\File', null, 'recitation_id');
    }
}
