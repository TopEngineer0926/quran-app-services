<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $resource_id
 * @property int $id
 * @property string $language_code
 * @property int $author_id
 * @property int $source_id
 * @property string $type
 * @property string $sub_type
 * @property string $cardinality_type
 * @property string $slug
 * @property boolean $is_available
 * @property string $description
 * @property string $name
 * @property Author $author
 * @property Language $language
 * @property Source $source
 * @property Image[] $images
 * @property ResourceApiVersion[] $resourceApiVersions
 * @property Tafsir[] $tafsirs
 * @property Text[] $texts
 * @property Translation[] $translations
 * @property Transliteration[] $transliterations
 * @property WordFont[] $wordFonts
 */
class Resource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resource';

    /**
     * @var array
     */
    protected $fillable = ['language_code', 'author_id', 'source_id', 'type', 'sub_type', 'cardinality_type', 'slug', 'is_available', 'description', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('App\Models\Author', null, 'author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo('App\Language', 'language_code', 'language_code');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->hasOne('App\Models\Source','id', 'source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany('App\Image', null, 'resource_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resourceApiVersions()
    {
        return $this->hasMany('App\ResourceApiVersion', null, 'resource_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tafsirs()
    {
        return $this->hasMany('App\Tafsir', null, 'resource_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function texts()
    {
        return $this->hasMany('App\Text', null, 'resource_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->hasMany('App\Translation', null, 'resource_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transliterations()
    {
        return $this->hasMany('App\Transliteration', null, 'resource_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordFonts()
    {
        return $this->hasMany('App\WordFont', null, 'resource_id');
    }
}
