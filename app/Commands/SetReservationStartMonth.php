<?php

namespace App\Commands;

use Carbon\Carbon;

use function PHPUnit\Framework\isNull;

class SetReservationStartMonth extends Command
{
    const MONTH = 'month';
    const YEAR = 'year';
    const PRODUCT_ID = 'product_id';
    const PAGE = 'page';
    const DEFAULT_PER_PAGE = 11;

    const SIG = 'set-reservation-month';
    /**
     * @var mixed|null
     */
    private mixed $product_id;
    /**
     * @var mixed|null
     */
    private mixed $month;
    /**
     * @var mixed|null
     */
    private mixed $year;
    /**
     * @var mixed|null
     */
    private mixed $page;

    public static function create(array $payload = []): Command
    {
        $instance = new self();

        $instance->product_id = $payload[0] ?? null;
        $instance->year = $payload[1] ?? null;
        $instance->month = $payload[2] ?? null;
        $instance->page = $payload[3] ?? null;

        return $instance;
    }

    public function run()
    {
        // TODO: Implement run() method.
    }

    public static function buildPayload(array $properties = []): string
    {
        if (!isset($properties[self::MONTH]) || !isset($properties[self::YEAR]) || !isset($properties[self::PRODUCT_ID]))
            throw new \Exception('Missing properties');
        return self::SIG . '_' . $properties[self::PRODUCT_ID] . '_' . $properties[self::YEAR] . '_' . $properties[self::MONTH] . '_' . ($properties[self::PAGE] ?? 1);
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getDays()
    {
        $remainingDays = [];

        $date = Carbon::now()->startOfMonth()->setYear($this->year)->setMonth($this->month)->addDays(($this->page - 1) * self::DEFAULT_PER_PAGE);

        $checkin = $this->getCheckinDate();

        if ($checkin && $checkin->month == $this->month) {
            // the command is for checkout date
            $date = $checkin->addDays(($this->page - 1) * self::DEFAULT_PER_PAGE);
        } else {
            // the command is for checkin date
            if (now()->month == $this->month) {
                // Start from the same day
                $skipDays = Carbon::now()->day + (($this->page - 1) * self::DEFAULT_PER_PAGE) - 1;
                $date = Carbon::now()->startOfMonth()->setYear($this->year)->setMonth($this->month)->addDays($skipDays);
            }
        }

        $days = $date->diffInDays($date->copy()->endOfMonth());

        for ($i = 0; $i < self::DEFAULT_PER_PAGE && $i <= $days; $i++) {
            $remainingDays[] = [
                'text' => $date->dayName . ' - ' . $date->day,
                'value' => $date->day,
            ];
            $date = $date->addDay();
        }

        return $remainingDays;
    }

    public function hasPreviousPage()
    {
        return $this->page > 1;
    }

    public function hasNextPage()
    {
        $date = Carbon::now()->startOfMonth()->setYear($this->year)->setMonth($this->month)->addDays($this->page * self::DEFAULT_PER_PAGE);

        $checkin = $this->getCheckinDate();

        if ($checkin && $checkin->month == $this->month)
            // the command is for checkout date
            $date = $checkin->addDays($this->page * self::DEFAULT_PER_PAGE);
        else {
            // the command is for checkin date
            if (now()->month == $this->month)
                $date = Carbon::now()->setMonth($this->month)->setYear($this->year)->addDays($this->page * self::DEFAULT_PER_PAGE);
        }

        return  $date < Carbon::now()->setMonth($this->month)->setYear($this->year)->endOfMonth();
    }

    public function getCurrentPage()
    {
        return $this->page;
    }

    public static function buildMonthsList()
    {
        // TODO: Implement
    }

    public function getCheckinDate()
    {
        $checkin = null;
        $cart = $this->cartRepository->getCart();
        $product = $this->productRepository->find($this->product_id);

        if ($product->require_end_date && $cart && $cart->additional) {
            $checkin = Carbon::parse($cart->additional[$this->product_id]['checkin'])->timezone('Asia/Damascus');

            // start in checkout from the next day of checkin date except for when the hour is 12 am
            if (!ifHourEnabled() || $checkin->hour != 0)
                $checkin->addDay();
        }

        return $checkin;
    }
}
