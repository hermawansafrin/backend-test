<?php

namespace App\Rules;

use App\Models\StatusFlow;
use App\Models\Transaction;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TransactionLockedRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $transaction = Transaction::find($value);

        if ($transaction === null) {
            $fail(__('validation.transaction_not_found'));
        }

        if ($transaction->status_flow_id != StatusFlow::NEW) {
            $fail(__('validation.transaction_locked'));
        }
    }
}
