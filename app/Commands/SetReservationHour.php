<?php

namespace App\Commands;

class SetReservationHour extends Command
{
    const HOUR = 'hour';
    const DAY = 'day';
    const SIG = 'set-reservation-hour';
    const PRODUCT_ID = 'product_id';
    const MONTH = 'month';
    const YEAR = 'year';
    const PAGE = 'page';

    private $product_id, $month, $year, $hour;
    /**
     * @var mixed|null
     */
    private mixed $day;

    public static function create(array $payload = []): Command
    {
        $instance = new self();

        $instance->product_id = $payload[0] ?? null;
        $instance->year = $payload[1] ?? null;
        $instance->month = $payload[2] ?? null;
        $instance->day = $payload[3] ?? null;
        //$instance->hour = json_decode('"' . $payload[3] ?? null . '"');
        $instance->hour = $payload[4] ?? null;

        return $instance;
    }

    public function run()
    {
        // TODO: Implement run() method.
    }

    public static function buildPayload(array $properties = []): string
    {
        return self::SIG . '_' . $properties[self::PRODUCT_ID] . '_' . $properties[self::YEAR] . '_' . $properties[self::MONTH] . '_' . $properties[self::DAY] . '_' . $properties[self::HOUR];
    }

    public function getHour()
    {
        return translateNumber($this->hour);
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @return mixed|null
     */
    public function getDay(): mixed
    {
        return $this->day;
    }
}
