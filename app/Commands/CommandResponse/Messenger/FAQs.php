<?php

namespace App\Commands\CommandResponse\Messenger;

use App\Commands\CommandResponse\Response;
use App\Commands\Question;
use App\Facebook\Messages\QuickReplies\Text;

class FAQs extends Response
{

    public static function create($command): Response
    {
        $response = new self();

        $index = 0;
        $questions = $command->get_Questions();

        if (!$questions) {
            $response->responses = __('responses.faq.empty_questions');
            return $response;
        }

        $questionsQuickReply = Text::create();
        $questionsQuickReply->setText(__('responses.faq.choose_question'));

        foreach ($questions as $question) {
            $questionsQuickReply->add($question->question, \App\Commands\Question::buildPayload(
                [Question::QUESTION_INDEX => $index]
            ));
            $index++;
        }
        $response->responses = $questionsQuickReply;

        return $response;
    }
}
