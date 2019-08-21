<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $char_type_id
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $description
 * @property CharType $charType
 * @property WordFont[] $wordFonts
 */
class CharType extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'char_type';

    /**
     * @var array
     */
    protected $fillable = ['parent_id', 'name', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function charType()
    {
        return $this->belongsTo('App\CharType', 'parent_id', 'char_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wordFonts()
    {
        return $this->hasMany('App\WordFont', null, 'char_type_id');
    }
}
