<?php

namespace App\Service;

use DateTime;

class LessonDurationService
{
    public function sumDurations(array $durations): int
{
    $totalSeconds = 0;

    foreach ($durations as $duration) {
        if (isset($duration['time']) && $duration['time'] instanceof \DateTimeImmutable) {
            $hours = (int) $duration['time']->format('H');
            $minutes = (int) $duration['time']->format('i');
            $seconds = (int) $duration['time']->format('s');

            $totalSeconds += $hours * 3600 + $minutes * 60 + $seconds;
        }
    }

    return $totalSeconds;
}


    public function convertSecondsToTime(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds %= 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
