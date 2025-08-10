<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfilingQuestionResource;
use App\Models\ProfilingQuestion;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProfilingController extends Controller
{
    /**
     * Get profiling questions
     *
     * @return AnonymousResourceCollection
     */
    public function getProfilingQuestions(): AnonymousResourceCollection
    {
        $questions = ProfilingQuestion::all(['id', 'question', 'type', 'options']);

        return ProfilingQuestionResource::collection($questions);
    }
}
