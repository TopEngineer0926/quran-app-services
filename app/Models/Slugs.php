<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $chapter_id
 * @property string $slug
 * @property string $locale
 * @property string $created_at
 * @property string $updated_at
 */
class Slugs extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['chapter_id', 'slug', 'locale', 'created_at', 'updated_at'];

}
