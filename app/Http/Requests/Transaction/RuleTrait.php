<?php

namespace App\Http\Requests\Transaction;

use App\Rules\TransactionLockedRule;

trait RuleTrait
{
    /**
     * Get the validation rules for the id field.
     *
     * @return array
     */
    public function getIdRules()
    {
        return ['required', 'integer', 'numeric', 'exists:transactions,id'];
    }

    /**
     * Get the validation rules for the id field.
     *
     * @return array
     */
    public function getIdCannotBeChangedRules()
    {
        $basicRules = $this->getIdRules();
        $basicRules[] = new TransactionLockedRule();

        return $basicRules;
    }

    /**
     * Get the validation rules for the customer_id field.
     *
     * @return array
     */
    public function getCustomerIdRules()
    {
        return ['required', 'integer', 'exists:customers,id'];
    }

    /**
     * Get the validation rules for the discount_percentage field.
     *
     * @return array
     */
    public function getDiscountPercentageRules()
    {
        return ['required', 'integer', 'min:0', 'max:100'];
    }

    /**
     * Get the validation rules for the items field.
     *
     * @return array
     */
    public function getItemsRules()
    {
        return ['required', 'array'];
    }

    /**
     * Get the validation rules for the product_id field.
     *
     * @return array
     */
    public function getProductIdRules()
    {
        return ['required', 'integer', 'exists:products,id'];
    }

    /**
     * Get the validation rules for the qty field.
     *
     * @return array
     */
    public function getQtyRules()
    {
        return ['required', 'integer', 'min:1'];
    }

    /**
     * Get the validation rules for the note field.
     *
     * @return array
     */
    public function getNoteRules()
    {
        return ['nullable', 'string', 'max:300'];
    }
}
