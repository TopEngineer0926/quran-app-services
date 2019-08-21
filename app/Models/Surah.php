<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $surah_id
 * @property int $id
 * @property int $ayat
 * @property boolean $bismillah_pre
 * @property int $revelation_order
 * @property string $revelation_place
 * @property int $page
 * @property string $name_complex
 * @property string $name_simple
 * @property string $name_english
 * @property string $name_arabic
 * @property Ayah[] $ayahs
 */
class Surah extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'surah';

    /**
     * @var array
     */
    protected $fillable = ['ayat', 'bismillah_pre', 'revelation_order', 'revelation_place', 'page', 'name_complex', 'name_simple', 'name_english', 'name_arabic'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ayahs()
    {
        return $this->hasMany('App\Ayah', null, 'surah_id');
    }
}
