<?php

namespace App\Other;


class ZodiacSign
{
    public static $zodiacIdName = [
        0 => 'Козерог',
        1 => 'Водолей',
        2 => 'Рыбы',
        3 => 'Овен',
        4 => 'Телец',
        5 => 'Близнецы',
        6 => 'Рак',
        7 => 'Лев',
        8 => 'Дева',
        9 => 'Весы',
        10 => 'Скорпион',
        11 => 'Стрелец',
    ];

    protected static $zodiacGaps = [
        ['start' => 1, 'finish' => 20, 'id' => 0],
        ['start' => 21, 'finish' => 50, 'id' => 1],
        ['start' => 51, 'finish' => 79, 'id' => 2],
        ['start' => 80, 'finish' => 110, 'id' => 3],
        ['start' => 111, 'finish' => 141, 'id' => 4],
        ['start' => 142, 'finish' => 172, 'id' => 5],
        ['start' => 173, 'finish' => 203, 'id' => 6],
        ['start' => 204, 'finish' => 234, 'id' => 7],
        ['start' => 235, 'finish' => 264, 'id' => 8],
        ['start' => 265, 'finish' => 294, 'id' => 9],
        ['start' => 295, 'finish' => 324, 'id' => 10],
        ['start' => 325, 'finish' => 354, 'id' => 11],
        ['start' => 355, 'finish' => 366, 'id' => 0],
    ];

    public static $monthSum = [
        0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334, 365
    ];

    public static function dateToSum($day, $month)
    {
        if ($day < 0 || $day > 31) {
            throw new \Exception('Invalid day');
        }

        if ($month < 1 || $month > 12) {
            throw new \Exception('Invalid month');
        }

        return static::$monthSum[$month - 1] + $day;
    }

    public static function getZodiacSign($day, $month)
    {
        $sum = static::dateToSum($day, $month);
        foreach (static::$zodiacGaps as $gap) {
            if ($gap['start'] <= $sum && $gap['finish'] >= $sum) {
                return $gap['id'];
            }
        }

        return -1;
    }

    public static function getZodiacSignNameByDate($day, $month)
    {
        return static::getZodiacSignNameById(static::getZodiacSign($day, $month));
    }

    public static function getZodiacSignNameById($id)
    {
        if ($id == -1) {
            return 'Неизвестно';
        }

        return static::$zodiacIdName[$id];
    }
}
