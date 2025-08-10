<?php

namespace App\Services;

use App\DTOs\ProfileAnswerDTO;
use App\Enums\QuestionType;
use App\Models\ProfilingQuestion;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfilingQuestionService
{
    /**
     * @param ProfileAnswerDTO $answerDTO
     * @return void
     */
    public function validateQuestion(ProfileAnswerDTO $answerDTO): void
    {
        /** @var ProfilingQuestion $question */
        $question = ProfilingQuestion::where('id', $answerDTO->getQuestionId())->first();

        $rules = match ($question->type) {
            QuestionType::DATE->value => ['value' => ['required', 'date', 'date_format:Y-m-d', 'before:today']],
            QuestionType::SINGLE_CHOICE->value => ['value' => ['required', Rule::in($question->options)]],
            default => [],
        };

        if (empty($rules)) { // nothing to validate
            return;
        }

        $messages = [
            'in' => 'The <' . $question->question . '> must be one of the following types: :values',
        ];

        $validator = Validator::make(['value' => $answerDTO->getAnswer()], $rules, $messages);

        $validator->validate();
    }

    /**
     * @param array $rules
     * @param mixed $value
     * @return void
     */
    private function processValidation(array $rules, mixed $value): void
    {
        $validator = Validator::make(['value' => $value], $rules);

        $validator->validate();
    }

    /**
     * @param int $userId
     * @param ProfileAnswerDTO $answerDTO
     * @return void
     */
    public function saveProfile(int $userId, ProfileAnswerDTO $answerDTO): void
    {
        UserProfile::updateOrCreate(
            ['user_id' => $userId, 'profiling_question_id' => $answerDTO->getQuestionId()],
            ['value' => $answerDTO->getAnswer()]
        );

        Log::info("User Profile updated for user $userId, question " . $answerDTO->getQuestionId());
    }
}
