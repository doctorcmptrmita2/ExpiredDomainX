<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchDomainsRequest extends FormRequest
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
        return [
            'tld' => 'nullable|string|max:10',
            'status' => 'nullable|in:expired,expiring,active,pending_delete',
            'min_age_years' => 'nullable|integer|min:0|max:50',
            'expiry_window' => 'nullable|in:expired,7_days,30_days,90_days',
            'min_organic_traffic' => 'nullable|integer|min:0',
            'min_referring_domains' => 'nullable|integer|min:0',
            'keyword' => 'nullable|string|max:255',
        ];
    }
}
