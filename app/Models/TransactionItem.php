<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TransactionItem extends Model
{
    use SoftDeletes;

    public $table = 'transaction_items';
    public $timestamps = true;

    protected $guarded = ['id'];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * auto boot for adding uuid
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($table) {
            $table->uuid = (string)Str::uuid();
        });
    }
}
