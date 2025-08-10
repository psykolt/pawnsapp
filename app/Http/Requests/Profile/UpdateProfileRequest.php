<?php

namespace App\Http\Requests\Profile;

use App\DTOs\ProfileAnswerDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'answers' => ['array'],
            'answers.*.id' => 'required|integer|exists:profiling_questions,id',
            'answers.*.value' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function getAnsweredQuestions(): array
    {
        $data = $this->validated('answers');

        return Arr::map($data, function (array $answer) {
            return new ProfileAnswerDTO($answer);
        });
    }
}
