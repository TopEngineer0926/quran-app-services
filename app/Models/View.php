<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $view_id
 * @property int $id
 */
class View extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'view';

    /**
     * @var array
     */
    protected $fillable = [];

}
