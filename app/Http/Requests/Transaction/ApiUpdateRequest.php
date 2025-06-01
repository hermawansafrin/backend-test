<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\APIRequest;

class ApiUpdateRequest extends APIRequest
{
    use RuleTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        $items = $this->items;
        $items = array_map(function ($item) {
            return [
                'product_id' => (int) $item['product_id'],
                'qty' => (int) $item['qty'],
            ];
        }, $items);

        $this->merge([
            'id' => (int) $this->id,
            'customer_id' => (int) $this->customer_id,
            'discount_percentage' => (int) $this->discount_percentage,
            'items' => $items,
            'note' => $this->note ?? null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => $this->getIdCannotBeChangedRules(),
            'customer_id' => $this->getCustomerIdRules(),
            'discount_percentage' => $this->getDiscountPercentageRules(),
            'items' => $this->getItemsRules(),
            'items.*.product_id' => $this->getProductIdRules(),
            'items.*.qty' => $this->getQtyRules(),
            'note' => $this->getNoteRules(),
        ];
    }
}
