<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $language_code
 * @property string $description
 * @property int $surah_id
 * @property string $content_source
 * @property string $short_description
 */
class SurahInfos extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['language_code', 'description', 'surah_id', 'content_source', 'short_description'];

}
