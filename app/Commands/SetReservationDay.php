<?php

namespace App\Commands;

class SetReservationDay extends Command
{
    const DAY = 'day';
    const SIG = 'set-reservation-day';
    const PRODUCT_ID = 'product_id';
    const MONTH = 'month';
    const YEAR = 'year';
    const PAGE = 'page';

    private $product_id, $month, $day, $year;

    public static function create(array $payload = []): Command
    {
        $instance = new self();

        $instance->product_id = $payload[0] ?? null;
        $instance->year = $payload[1] ?? null;
        $instance->month = $payload[2] ?? null;
        $instance->day = $payload[3] ?? null;

        return $instance;
    }

    public function run()
    {
        // TODO: Implement run() method.
    }

    public static function buildPayload(array $properties = []): string
    {
        return self::SIG . '_' . $properties[self::PRODUCT_ID] . '_' . $properties[self::YEAR]  . '_' . $properties[self::MONTH] . '_' . $properties[self::DAY];
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }
}
