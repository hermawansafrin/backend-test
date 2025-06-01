<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="CreateProduct",
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          example="Product Name"
 *     ),
 *     @OA\Property(
 *          property="price",
 *          type="integer",
 *          example="100000"
 *     ),
 *     @OA\Property(
 *          property="stock",
 *          type="integer",
 *          example="100"
 *     ),
 *     @OA\Property(
 *          property="is_active",
 *          type="integer",
 *          example="1"
 *     ),
 * )
 *
 * @OA\Schema(
 *     schema="UpdateProduct",
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          example="Product Name Update"
 *     ),
 *     @OA\Property(
 *          property="price",
 *          type="integer",
 *          example="500000"
 *     ),
 *     @OA\Property(
 *          property="stock",
 *          type="integer",
 *          example="200"
 *     ),
 *     @OA\Property(
 *          property="is_active",
 *          type="integer",
 *          example="1"
 *     ),
 * )
 */

class Product extends Model
{
    public $table = 'products';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'price',
        'stock',
        'is_active'
    ];

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
}
