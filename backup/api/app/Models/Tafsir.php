<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $tafsir_id
 * @property int $id
 * @property int $resource_id
 * @property string $text
 * @property Resource $resource
 * @property Ayah[] $ayahs
 */
class Tafsir extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tafsir';

    /**
     * @var array
     */
    protected $fillable = ['resource_id', 'text'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo('App\Resource', null, 'resource_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ayahs()
    {
        return $this->belongsToMany('App\Ayah', 'tafsir_ayah', null, 'ayah_key');
    }
}
