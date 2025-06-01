<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CannotDeleteCauseDataHasBeenUsed implements ValidationRule
{
    /**
     * @var string
     */
    private string $tableName;

    /**
     * @var string
     */
    private string $columnName;

    /**
     * Constructor
     */
    public function __construct(string $tableName, string $columnName)
    {
        $this->tableName = $tableName;
        $this->columnName = $columnName;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table($this->tableName)
            ->where($this->columnName, $value)
            ->exists();

        if ($exists) {
            $fail(__('validation.cannot_delete_cause_data_has_been_used'));
        }
    }
}
