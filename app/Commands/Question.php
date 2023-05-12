<?php

namespace App\Commands;

use App\Models\StoreSettings;

class Question extends Command
{
    const SIG = 'question';
    const QUESTION_INDEX = 'question_index';

    protected $question;

    public static function create(array $payload = []): Command
    {
        $command = new self();
        $command->question = $payload[0];

        return $command;
    }

    public function run()
    {
    }

    public static function buildPayload(array $properties = []): string
    {
        if (!isset($properties[self::QUESTION_INDEX]))
            throw new \Exception('Missing QUESTION_INDEX property');

        $question_index = $properties[self::QUESTION_INDEX];

        return self::SIG . '_' . $question_index;
    }

    public function get_Answer()
    {
        $questions = StoreSettings::where([
            ['key', 'FAQs'],
            ['store_id', request('store_id')]
        ])->first();

        $questions = json_decode($questions->value);

        return $questions[$this->question];
    }
}
