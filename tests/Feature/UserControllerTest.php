<?php

namespace Tests\Feature;

use App\Enums\QuestionType;
use App\Enums\TransactionType;
use App\Models\ProfilingQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testUpdateProfileSuccess(): void
    {
        $user = $this->sanctumLogin();

        $questions = ProfilingQuestion::factory(2)
            ->sequence(
                ['question' => $this->faker->sentence(), 'type' => QuestionType::TEXT->value],
                ['question' => $this->faker->sentence(), 'type' => QuestionType::SINGLE_CHOICE->value, 'options' => ['a', 'b', 'c']],
            )
            ->create();

        $request['answers'] = [];

        /** @var ProfilingQuestion $question */
        foreach ($questions as $question) {
            $value = $question->type === QuestionType::SINGLE_CHOICE->value ?
                $this->faker->randomElement($question->options) : $this->faker->text();
            $request['answers'][] = ['id' => $question->id, 'value' =>  $value];
        }

        $response = $this->patch(route('user.updateProfile'), $request);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users__profiles', ['user_id' => $user->id]);
        $this->assertDatabaseHas('points_transactions', [
            'user_id' => $user->id,
            'points' => 5,
            'type' => TransactionType::PROFILE_UPDATED->value,
        ]);
    }

    /**
     * @return void
     */
    public function testUpdateProfileBadQuestions(): void
    {
        $user = $this->sanctumLogin();

        /** @var ProfilingQuestion $question */
        $question = ProfilingQuestion::factory()->create([
            'question' => $this->faker->sentence(),
            'type' => QuestionType::SINGLE_CHOICE->value,
            'options' => ['a', 'b', 'c']
        ]);

        $request['answers'][] = ['id' => $question->id, 'value' =>  'X'];

        $response = $this->patch(route('user.updateProfile'), $request);

        $response->assertSessionHas('errors');
        $this->assertDatabaseMissing('users__profiles', ['user_id' => $user->id]);
    }

    /**
     * @return void
     */
    public function testUpdateProfileFailOncePerDay(): void
    {
        $this->sanctumLogin();

        /** @var ProfilingQuestion $question */
        $question = ProfilingQuestion::factory()->create([
            'question' => $this->faker->sentence(),
            'type' => QuestionType::TEXT->value,
        ]);

        $request['answers'][] = ['id' => $question->id, 'value' =>  $this->faker->text()];

        $response = $this->patch(route('user.updateProfile'), $request);

        $response->assertSuccessful();

        $response2 = $this->patch(route('user.updateProfile'), $request);

        $response2->assertStatus(425);

    }
}
