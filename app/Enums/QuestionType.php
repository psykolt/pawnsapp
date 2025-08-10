<?php

namespace App\Enums;

enum QuestionType: string
{
    case TEXT = 'text';

    case SINGLE_CHOICE = 'single_choice';

    case MULTIPLE_CHOICE = 'multiple_choice';

    case DATE = 'date';
}
