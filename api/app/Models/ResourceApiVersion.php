<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $resource_id
 * @property int $id
 * @property boolean $v1_is_enabled
 * @property boolean $v1_is_default
 * @property boolean $v1_separator
 * @property boolean $v1_label
 * @property int $v1_order
 * @property int $v1_id
 * @property string $v1_name
 * @property boolean $v2_is_enabled
 * @property boolean $v2_is_default
 * @property float $v2_weighted
 * @property Resource $resource
 */
class ResourceApiVersion extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'resource_api_version';

    /**
     * @var array
     */
    protected $fillable = ['v1_is_enabled', 'v1_is_default', 'v1_separator', 'v1_label', 'v1_order', 'v1_id', 'v1_name', 'v2_is_enabled', 'v2_is_default', 'v2_weighted'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo('App\Resource', null, 'resource_id');
    }
}
