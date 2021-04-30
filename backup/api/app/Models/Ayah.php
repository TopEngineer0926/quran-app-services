<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $ayah_key
 * @property int $id
 * @property int $surah_id
 * @property int $ayah_index
 * @property int $ayah_num
 * @property int $page_num
 * @property int $juz_num
 * @property int $hizb_num
 * @property int $rub_num
 * @property string $text
 * @property string $sajdah
 * @property Surah $surah
 * @property File[] $files
 * @property Image[] $images
 * @property Tafsir[] $tafsirs
 * @property Translation[] $translations
 * @property Word[] $words
 * @property WordFont[] $wordFonts
 */
class Ayah extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ayah';

    /**
     * @var array
     */
    protected $fillable = ['surah_id', 'ayah_index', 'ayah_num', 'page_num', 'juz_num', 'hizb_num', 'rub_num', 'text', 'sajdah'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function surah()
    {
        return $this->belongsTo('App\Surah', null, 'surah_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany('App\File', 'ayah_key', 'ayah_key');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany('App\Image', 'ayah_key', 'ayah_key');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tafsirs()
    {
        return $this->belongsToMany('App\Tafsir', 'tafsir_ayah', 'ayah_key');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->hasMany('App\Translation', 'ayah_key', 'ayah_key');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function words()
    {
        return $this->hasMany('App\Word', 'ayah_key', 'ayah_key');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordFonts()
    {
        return $this->hasMany('App\WordFont', 'ayah_key', 'ayah_key');
    }
}
