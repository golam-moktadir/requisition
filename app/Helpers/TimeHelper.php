<?php

namespace App\Helpers;

class TimeHelper
{
    /**
     * Convert time to minutes for storage.
     *
     * @param float|int $value The time value (can be fractional)
     * @param int $unit One of: 0 = day, 1 = hour, 2 = minute
     * @return int Time in minutes
     */
    public static function toMinutes(float|int $value, int $unit): int
    {
        return match ($unit) {
            0 => (int) round($value * 1440), // days
            1 => (int) round($value * 60),   // hours
            2 => (int) round($value),        // minutes
            default => 0,                    // fallback
        };
    }

    /**
     * Convert minutes to unit value and type.
     *
     * @param int $minutes Total time in minutes
     * @return array ['value' => float|int, 'unit' => int (0 = day, 1 = hour, 2 = minute)]
     */
    public static function fromMinutes(int $minutes): array
    {
        if ($minutes >= 1440 && $minutes % 1440 === 0) {
            return [
                'value' => $minutes / 1440,
                'unit' => 0, // Day
            ];
        }

        if ($minutes >= 60 && $minutes % 60 === 0) {
            return [
                'value' => $minutes / 60,
                'unit' => 1, // Hour
            ];
        }

        return [
            'value' => $minutes,
            'unit' => 2, // Minute
        ];
    }
}


