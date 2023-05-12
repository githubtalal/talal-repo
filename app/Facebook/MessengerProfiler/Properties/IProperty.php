<?php

namespace App\Facebook\MessengerProfiler\Properties;

interface IProperty
{

    static function create(): self;

    function toArray(): array;

    function getKey(): string;
}
