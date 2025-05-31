<?php

namespace App\Http\Requests\Auth;

trait RuleTrait
{
    /**
     * Get email rules
     * @return array
     */
    public function getEmailRules(): array
    {
        return [
            'bail',
            'required',
            'min:5',
            'max:100',
            'email:rfc',
            'exists:users,email',
        ];
    }

    /**
     * Get password rules
     * @return array
     */
    public function getPasswordRules(): array
    {
        return [
            'bail',
            'required',
            'min:6',
            'max: 16',
        ];
    }
}
