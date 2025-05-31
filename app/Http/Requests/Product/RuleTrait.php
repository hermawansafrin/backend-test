<?php

namespace App\Http\Requests\Product;

trait RuleTrait
{
    /**
     * Get the validation rules for the id field.
     *
     * @return array
     */
    public function getIdRules(): array
    {
        return ['required', 'integer', 'numeric', 'exists:products,id'];
    }

    /**
     * Get the validation rules for the name field.
     *
     * @return array
     */
    public function getNameRules()
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules for the price field.
     *
     * @return array
     */
    public function getPriceRules()
    {
        return ['required', 'integer', 'numeric', 'min:0'];
    }

    /**
     * Get the validation rules for the stock field.
     *
     * @return array
     */
    public function getStockRules()
    {
        return ['required', 'integer', 'numeric', 'min:0'];
    }

    /**
     * Get the validation rules for the is_active field.
     *
     * @return array
     */
    public function getIsActiveRules()
    {
        return ['required', 'integer', 'numeric', 'in:0,1'];
    }
}
