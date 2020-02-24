<?php

namespace App\Http\Requests;

use App\Models\Text;
use Illuminate\Foundation\Http\FormRequest;

class TextUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $text = Text::where('id', $this->input('text_id'));
        if ($text->count()){
            $inArray = in_array($text->first()->id, $this->user()->texts->pluck('text_id')->toArray());
            if (($this->method() == "POST" && !$inArray) || ($this->method() == "PUT" && $inArray)){
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text_id' => 'required|integer',
            'tagged_text' => 'required|string'
        ];
    }
}
