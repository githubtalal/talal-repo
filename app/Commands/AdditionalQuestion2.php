<?php

namespace App\Commands;

class AdditionalQuestion2 extends Command
{
    private $question;
    const SIG = 'set-additional-Q-2';

    public static function create(array $payload = []): Command
    {
        $instance = new self();
        $instance->question = $payload[0] ?? '';

        return $instance;
    }

    public function run()
    {
        $this->cartRepository->getCart()->update([
            'additional_question2' => $this->question,
        ]);
    }

    public static function buildPayload(array $properties = []): string
    {
        return self::SIG;
    }
}
