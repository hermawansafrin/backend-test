<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="ItemTransaction",
 *     @OA\Property(
 *          property="product_id",
 *          type="integer",
 *          example="1"
 *     ),
 *     @OA\Property(
 *          property="qty",
 *          type="integer",
 *          example="1"
 *     ),
 * )
 *
 * @OA\Schema(
 *     schema="CompleteTransaction",
 *     @OA\Property(
 *          property="paid_date_time",
 *          type="string",
 *          example="2025-01-01 00:00:00"
 *     ),
 * )
 *
 * @OA\Schema(
 *     schema="CreateTransaction",
 *     @OA\Property(
 *          property="customer_id",
 *          type="integer",
 *          example="1"
 *     ),
 *     @OA\Property(
 *          property="discount_percentage",
 *          type="integer",
 *          example="0"
 *     ),
 *     @OA\Property(
 *          property="note",
 *          type="string",
 *          example="this is transaction note"
 *     ),
 *     @OA\Property(
 *          property="items",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/ItemTransaction")
 *     ),
 * )
 *
 * @OA\Schema(
 *     schema="UpdateTransaction",
 *     @OA\Property(
 *          property="customer_id",
 *          type="integer",
 *          example="1"
 *     ),
 *     @OA\Property(
 *          property="discount_percentage",
 *          type="integer",
 *          example="0"
 *     ),
 *     @OA\Property(
 *          property="note",
 *          type="string",
 *          example="this is transaction note"
 *     ),
 *     @OA\Property(
 *          property="items",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/ItemTransaction")
 *     ),
 * )
 */

class Transaction extends Model
{
    use SoftDeletes;

    public $table = 'transactions';
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

    /**
     * get transaction items
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaction_items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id', 'id');
    }

    /**
     * get customer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * get status flow
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status_flow()
    {
        return $this->belongsTo(StatusFlow::class, 'status_flow_id', 'id');
    }

    /**
     * get created user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_user_id', 'id');
    }

    /**
     * get last updated user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function last_updated_user()
    {
        return $this->belongsTo(User::class, 'last_updated_user_id', 'id');
    }
}
