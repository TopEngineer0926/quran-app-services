<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $tafsir_id
 * @property string $ayah_key
 * @property Ayah $ayah
 * @property Tafsir $tafsir
 */
class TafsirAyah extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tafsir_ayah';

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ayah()
    {
        return $this->belongsTo('App\Ayah', 'ayah_key', 'ayah_key');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tafsir()
    {
        return $this->belongsTo('App\Tafsir', null, 'tafsir_id');
    }
}
