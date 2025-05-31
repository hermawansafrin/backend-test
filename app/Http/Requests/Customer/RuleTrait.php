<?php

namespace App\Http\Requests\Customer;

trait RuleTrait
{
    /**
     * Get the validation rules for the id field.
     * @return array
     */
    public function getIdRules(): array
    {
        return ['required', 'integer', 'exists:customers,id'];
    }

    /**
     * Get the validation rules for the name field.
     *
     * @return array
     */
    public function getNameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules for the email field.
     * @return array
     */
    public function getEmailRules(): array
    {
        return ['required', 'max:255', 'unique:customers,email', 'email:rfc'];
    }

    /**
     * Get the validation rules for the email field.
     * @return array
     */
    public function getEmailUpdateRules(): array
    {
        $basicRule = $this->getEmailRules();
        $basicRule[2] = 'unique:customers,email,' . $this->id;//index on unique, must except some id
        return $basicRule;
    }

    /**
     * Get the validation rules for the phone field.
     * @return array
     */
    public function getPhoneRules(): array
    {
        return ['required', 'string', 'max:20', 'unique:customers,phone', 'regex:/^[0-9]+$/'];
    }

    /**
     * Get the validation rules for the phone field.
     * @return array
     */
    public function getPhoneUpdateRules(): array
    {
        $basicRule = $this->getPhoneRules();
        $basicRule[3] = 'unique:customers,phone,' . $this->id;//index on unique, must except some id
        return $basicRule;
    }
}
