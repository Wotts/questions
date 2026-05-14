<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Strip answers from the course
     *
     * @param mixed $course
     *
     * @return mixed $questions
     */
    private function omitAnswers(mixed $course): mixed
    {
        foreach ($course['questions'] as $key => $question) {
            foreach (['correct_option_id', 'model_answer', 'synonyms'] as $answer) {
                unset($question[$answer]);
            }
            $course['questions'][$key] = $question;
        }

        return $course;
    }

    /**
     * Load the course.
     *
     * @return mixed $questions
     */
    private function loadCourse(): mixed
    {
        $questions = file_get_contents('./questions.json');

        return json_decode($questions, TRUE);
    }

    /**
     * Get the questions with or without answers.
     *
     * @param mixed $answers
     *
     * @return mixed $scoredQuestions
     */
    private function scoreQuestions(mixed $answers): mixed
    {
        $courseQuestions = $this->loadCourse()['questions'];

        $scoredQuestions = [
            "score" => 0,
            'questions' => [],
        ];

        foreach ($answers as $questionId => $answer) {
            $courseQuestion = array_find(
                $courseQuestions,
                fn($question) => $question['id'] == $questionId
            );

            // $courseQuestion = reset($filterResult);

            if ($courseQuestion['type'] === 'multiple_choice') {
                $correctAnswer = array_find(
                    $courseQuestion['options'],
                    fn($option) => $option['id'] == $courseQuestion['correct_option_id'],
                );
                $submittedAnswer = array_find(
                    $courseQuestion['options'],
                    fn($option) => $option['id'] == $answer,
                );
                $question = [
                    "id" => $courseQuestion['id'],
                    "prompt" => $courseQuestion['prompt'],
                    "answer" => $correctAnswer['label'],
                    "submitted" => $submittedAnswer['label'],
                ];

                if ($answer === $courseQuestion['correct_option_id']) {
                    $question['correct'] = TRUE;
                    $scoredQuestions['score']++;
                } else {{
                    $question['correct'] = FALSE;
                }}

                array_push($scoredQuestions['questions'], $question);
            }

            if ($courseQuestion['type'] === 'open') {
                $question = [
                    "id" => $courseQuestion['id'],
                    "prompt" => $courseQuestion['prompt'],
                    "answer" => $courseQuestion['model_answer'],
                    "submitted" => $answer,
                ];

                $correctAnswers = $courseQuestion['synonyms'];
                array_push($correctAnswers, $courseQuestion['model_answer']);
                $lcAnswers = array_map('strtolower', $correctAnswers);

                if (in_array(strtolower($answer), $lcAnswers)) {
                    $question['correct'] = TRUE;
                    $scoredQuestions['score']++;
                } else {{
                    $question['correct'] = FALSE;
                }}

                array_push($scoredQuestions['questions'], $question);
            }
        }

        return $scoredQuestions;
    }

    /**
     * Get the Course questions without answers.
     *
     * @return string $courseQuestions
     */
    public function getCourseQuestions(): string
    {
        $course = $this->loadCourse();
        $courseQuestions = $this->omitAnswers($course);

        return json_encode($courseQuestions);
    }

    /**
     * Score submitted course answers.
     *
     * @param Request $request
     *
     * @return string $scoredQuestions
     */
    public function submitCourseAnswers(Request $request): string
    {
        $answers = json_decode($request->getContent());
        $scoredQuestions = $this->scoreQuestions($answers);

        return json_encode($scoredQuestions);
    }
}
