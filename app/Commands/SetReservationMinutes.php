<?php

namespace App\Commands;

use App\Facebook\Facebook;
use Carbon\Carbon;

class SetReservationMinutes extends Command
{
    const SIG = 'set-reservation-minutes';
    const PRODUCT_ID = 'product_id';
    const YEAR = 'year';
    const MONTH = 'month';
    const DAY = 'day';
    const HOUR = 'hour';
    const MINUTE = 'minute';


    private $hour, $product_id, $year, $month, $day, $minute, $require_end_date = false, $start_date, $end_date;

    public static function create(array $payload = []): Command
    {
        $instance = new self();

        $instance->product_id = $payload[0] ?? null;
        $instance->year = $payload[1] ?? null;
        $instance->month = $payload[2] ?? null;
        $instance->day = $payload[3] ?? null;
        $instance->hour = $payload[4] ?? null;
        $instance->minute = $payload[5] ?? null;

        return $instance;
    }

    public function run()
    {
        $cart = $this->cartRepository->getCart();
        $product = $this->productRepository->find($this->product_id);
        if (!$cart) $cart = $this->cartRepository->createCart();
        // If the product require end date we need to check if we have check in date stored in the cart additional data
        if ($product->require_end_date ?? false) {
            // We've found a check in date in the cart additional data, so the current command contain the checkout date
            // So we add the product normally to the cart.
            if ($cart->additional[$this->product_id]['checkin'] ?? false) {

                $checkinDate = $cart->additional[$this->product_id]['checkin'];
                $this->start_date = Carbon::parse($checkinDate);

                $checkoutDate = now()->setYear($this->year)->setMonth($this->month)->setDay($this->day)->setHour($this->hour)->setMinute($this->minute);

                $this->end_date = $checkoutDate;

                $this->cartRepository->addProduct($this->product_id, [
                    'checkin' => $checkinDate,
                    'checkout' => $checkoutDate
                ]);
                $this->cartRepository->getCart()->update([
                    'platform' => Facebook::SIGNATURE,
                ]);
                // After we add the product to the cart we need to empty the additional data
                $additional = $cart->additional;
                unset($additional[$this->product_id]);
                $cart->update([
                    'additional' => $additional,
                ]);
            } else {
                // We don't have checkin date, so we consider the current command as the checkin date
                $additional = $cart->additional ?? [];

                $checkinDate = now()->setYear($this->year)->setMonth($this->month)->setDay($this->day)->setHour($this->hour)->setMinute($this->minute);

                $this->start_date = $checkinDate;
                $additional[$this->product_id] = [
                    'checkin' => $checkinDate,
                ];
                $cart->update([
                    'additional' => $additional,
                ]);
                $this->require_end_date = true;
            }
        } else {

            $date = now()->setYear($this->year)->setMonth($this->month)->setDay($this->day)->setHour($this->hour)->setMinute($this->minute);

            $this->start_date = $date;

            $this->cartRepository->addProduct($this->product_id, [
                'checkin' => $date,
            ]);
            $this->cartRepository->getCart()->update([
                'platform' => Facebook::SIGNATURE,
            ]);
        }
    }

    public static function buildPayload(array $properties = []): string
    {
        return self::SIG . '_' . $properties[self::PRODUCT_ID] . '_' . $properties[self::YEAR] . '_' . $properties[self::MONTH] . '_' . $properties[self::DAY] . '_' . $properties[self::HOUR] . '_' . $properties[self::MINUTE];
    }

    /**
     * @return mixed
     */
    public function requireEndDate(): bool
    {
        return $this->require_end_date;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    public function productRequireEndDate()
    {
        $product = $this->productRepository->find($this->product_id);
        return $product->require_end_date ?? false;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function ifCheckinDateisLastDay()
    {
        $date = Carbon::parse($this->getStartDate());
        return $date->diffInDays($date->copy()->endOfMonth());
    }

    public function getStartDate()
    {
        $hourFormat = '';

        if (ifHourEnabled())
            $hourFormat = ' g:i A';

        if (is_string($this->start_date)) {
            $this->start_date = Carbon::parse($this->start_date)->timezone('Asia/Damascus');
        }
        return $this->start_date->timezone('Asia/Damascus')->format('Y-m-d' . $hourFormat);
    }

    public function getEndDate()
    {
        $hourFormat = '';

        if (ifHourEnabled())
            $hourFormat = ' g:i A';

        if (is_string($this->end_date)) {
            $this->end_date = Carbon::parse($this->end_date)->timezone('Asia/Damascus');
        }
        return $this->end_date->timezone('Asia/Damascus')->format('Y-m-d' . $hourFormat);
    }
}
