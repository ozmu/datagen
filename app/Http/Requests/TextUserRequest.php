<?php

namespace App\Http\Requests;

use App\Models\Text;
use App\Models\TextUser;
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
        if ($this->method() == "POST"){
            if ($this->input('draft') == 'true'){
                return true;
            }
            $text = Text::where('id', $this->input('text_id'));
            if ($text->count() && !in_array($text->first()->id, $this->user()->texts->pluck('text_id')->toArray())){
                return true;
            }
        }
        else if ($this->method() == "PUT"){
            $textUser = TextUser::where('id', $this->input('text_id'));
            if ($textUser->count() && in_array($textUser->first()->id, $this->user()->texts->pluck('id')->toArray())){
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
