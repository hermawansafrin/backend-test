<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusFlow extends Model
{
    public const NEW = 1;
    public const COMPLETED = 2;
    public const CANCELLED = 3;

    public $timestamps = false;
    public $table = 'status_flows';

    protected $fillable = [
        'name',
    ];
}
