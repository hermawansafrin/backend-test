<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        $items = json_decode($this->items, true);
        $items = array_map(function ($item) {
            return [
                'product_id' => (int) $item['product_id'],
                'qty' => (int) $item['qty'],
            ];
        }, $items);

        $this->merge([
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
        // dd($this->all());
        return [
            'customer_id' => $this->getCustomerIdRules(),
            'discount_percentage' => $this->getDiscountPercentageRules(),
            'items' => $this->getItemsRules(),
            'items.*.product_id' => $this->getProductIdRules(),
            'items.*.qty' => $this->getQtyRules(),
            'note' => $this->getNoteRules(),
        ];
    }
}
