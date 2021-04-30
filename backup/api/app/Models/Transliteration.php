<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $resource_id
 * @property string $ayah_key
 * @property string $text
 * @property Resource $resource
 */
class Transliteration extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'transliteration';

    /**
     * @var array
     */
    protected $fillable = ['text'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo('App\Resource', null, 'resource_id');
    }
}
