<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $resource_id
 * @property string $ayah_key
 * @property string $text
 * @property Ayah $ayah
 * @property Resource $resource
 */
class Translation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'translation';
    protected $hidden =['created_at','updated_at'];
    /**
     * @var array
     */
    protected $fillable = ['text'];

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
    public function resource()
    {
        return $this->belongsTo('App\Resource', null, 'resource_id');
    }
}
