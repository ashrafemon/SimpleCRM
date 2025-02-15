<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $data = [
            'name'     => 'sometimes|required',
            'email'    => 'sometimes|required|email:rfc,dns',
            'phone'    => 'sometimes',
            'password' => 'sometimes|required|min:6',
            'role'     => 'sometimes|required|in:ADMIN,COUNSELOR',
            'status'   => 'sometimes|required|in:active,inactive',
        ];

        if (in_array(request()->method(), ['PUT', 'PATCH'])) {
            unset($data['password']);
        }

        return $data;
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->wantsJson() || $this->ajax()) {
            throw new HttpResponseException(validateError($validator->errors()));
        }
        parent::failedValidation($validator);
    }
}
