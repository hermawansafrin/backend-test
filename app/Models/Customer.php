<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="CreateCustomer",
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          example="Customer Name"
 *     ),
 *     @OA\Property(
 *          property="email",
 *          type="string",
 *          example="mail@mail.test"
 *     ),
 *     @OA\Property(
 *          property="phone",
 *          type="string",
 *          example="0832432423"
 *     ),
 * )
 *
 * @OA\Schema(
 *     schema="UpdateCustomer",
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          example="Customer Name"
 *     ),
 *     @OA\Property(
 *          property="email",
 *          type="string",
 *          example="mail@mail.test"
 *     ),
 *     @OA\Property(
 *          property="phone",
 *          type="string",
 *          example="084324234"
 *     )
 * )
 */

class Customer extends Model
{
    public $table = 'customers';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
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
