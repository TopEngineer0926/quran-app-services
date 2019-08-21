<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $style_id
 * @property int $id
 * @property string $path
 * @property string $slug
 * @property string $english
 * @property string $arabic
 * @property Recitation[] $recitations
 */
class Style extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'style';

    /**
     * @var array
     */
    protected $fillable = ['path', 'slug', 'english', 'arabic'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recitations()
    {
        return $this->hasMany('App\Recitation', null, 'style_id');
    }
}
