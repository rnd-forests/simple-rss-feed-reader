<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FeedUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'bail|required|max:255',
            'url' => [
                'required',
                'active_url',
                Rule::unique('feeds')->ignore($this->route('feed')->id)->where(function ($query) {
                    return $query->where('user_id', $this->user()->getKey());
                }),
            ]
        ];
    }
}
