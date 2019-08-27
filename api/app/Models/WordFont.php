<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $resource_id
 * @property string $ayah_key
 * @property int $position
 * @property int $word_id
 * @property int $char_type_id
 * @property int $page_num
 * @property int $line_num
 * @property int $code_dec
 * @property string $code_hex
 * @property Ayah $ayah
 * @property CharType $charType
 * @property Resource $resource
 * @property Word $word
 */
class WordFont extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'word_font';

    /**
     * @var array
     */
    protected $fillable = ['word_id', 'char_type_id', 'page_num', 'line_num', 'code_dec', 'code_hex'];

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
    public function charType()
    {
        return $this->belongsTo('App\CharType', null, 'char_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo('App\Resource', null, 'resource_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function word()
    {
        return $this->belongsTo('App\Word', null, 'word_id');
    }
}
