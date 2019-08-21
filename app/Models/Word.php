<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $word_id
 * @property int $id
 * @property string $ayah_key
 * @property int $token_id
 * @property int $position
 * @property string $translation
 * @property string $transliteration
 * @property Ayah $ayah
 * @property Token $token
 * @property WordFont[] $wordFonts
 * @property WordLemma[] $wordLemmas
 * @property WordRoot[] $wordRoots
 * @property WordStem[] $wordStems
 * @property WordTranslation[] $wordTranslations
 */
class Word extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'word';

    /**
     * @var array
     */
    protected $fillable = ['ayah_key', 'token_id', 'position', 'translation', 'transliteration'];

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
    public function token()
    {
        return $this->belongsTo('App\Token', null, 'token_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordFonts()
    {
        return $this->hasMany('App\WordFont', null, 'word_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordLemmas()
    {
        return $this->hasMany('App\WordLemma', null, 'word_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordRoots()
    {
        return $this->hasMany('App\WordRoot', null, 'word_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordStems()
    {
        return $this->hasMany('App\WordStem', null, 'word_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordTranslations()
    {
        return $this->hasMany('App\WordTranslation', null, 'word_id');
    }
}
