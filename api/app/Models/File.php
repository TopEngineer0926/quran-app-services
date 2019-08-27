<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $file_id
 * @property int $id
 * @property int $recitation_id
 * @property string $ayah_key
 * @property string $format
 * @property float $duration
 * @property string $mime_type
 * @property boolean $is_enabled
 * @property string $url
 * @property mixed $segments_stats
 * @property string $segments
 * @property Ayah $ayah
 * @property Recitation $recitation
 */
class File extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'file';

    /**
     * @var array
     */
    protected $fillable = ['recitation_id', 'ayah_key', 'format', 'duration', 'mime_type', 'is_enabled', 'url', 'segments_stats', 'segments'];

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
    public function recitation()
    {
        return $this->belongsTo('App\Recitation', null, 'recitation_id');
    }
}
