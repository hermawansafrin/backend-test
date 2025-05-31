<?php

namespace App\Http\Requests\User;

trait RuleTrait
{
    /**
     * Get the validation rules for the id field.
     * @return array
     */
    public function getIdRules(): array
    {
        return ['required', 'integer', 'numeric', 'exists:users,id'];
    }

    /**
     * Get the validation rules for the name field.
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
        return ['required', 'email', 'max:255', 'unique:users,email', 'email:rfc'];
    }

    /**
     * Get the validation rules for the email field.
     * @return array
     */
    public function getEmailUpdateRules(): array
    {
        $basicRules = $this->getEmailRules();
        $basicRules[3] = 'unique:users,email,' . $this->id;//change unique except specific id

        return $basicRules;
    }

    /**
     * Get the validation rules for the role_id field.
     * @return array
     */
    public function getRoleIdRules(): array
    {
        return ['required', 'exists:roles,id'];
    }

    /**
     * Get the validation rules for the is_active field.
     * @return array
     */
    public function getIsActiveRules(): array
    {
        return ['required', 'in:0,1'];
    }

    /**
     * Get the validation for password rules
     * @return array
     */
    public function getPasswordRules(): array
    {
        return [
            'required', 'string', 'min:6', 'max:12', 'confirmed'
        ];
    }

    /**
     * Get the validation for password update rules
     * @return array
     */
    public function getPasswordUpdateRules(): array
    {
        $basicRules = $this->getPasswordRules();
        $basicRules[0] = 'nullable';

        return $basicRules;
    }

    /**
     * Get the validation for password confirmation rules
     * @return array
     */
    public function getPasswordConfirmationRules(): array
    {
        return [
            'required', 'string', 'min:6', 'max:12', 'same:password'
        ];
    }

    /**
     * Get the validation for password confirmation update rules
     * @return array
     */
    public function getPasswordConfirmationUpdateRules(): array
    {
        $basicRules = $this->getPasswordConfirmationRules();
        $basicRules[0] = 'nullable';

        return $basicRules;
    }
}
