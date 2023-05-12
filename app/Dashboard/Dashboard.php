<?php

namespace App\Dashboard;

abstract class Dashboard
{
    public $filters = [];

    abstract public function execute(): array;
}
