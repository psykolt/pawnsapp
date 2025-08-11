<?php

namespace App\DTOs;

class ProfileAnswerDTO
{
    /**
     * @var int
     */
    private int $questionId;

    /**
     * @var string
     */
    private string $answer;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->questionId = $data['id'];
        $this->answer = $data['value'];
    }

    /**
     * @return int
     */
    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }
}
