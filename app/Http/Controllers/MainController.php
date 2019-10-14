<?php

namespace App\Http\Controllers;

use App\Item;
use App\Other\ZodiacSign;

class MainController
{
    public function indexAction()
    {
        $items = Item::cursor();

        $header = array_values(ZodiacSign::$zodiacIdName);
        array_unshift($header, "М\Ж");

        $rows = [];
        foreach (ZodiacSign::$zodiacIdName as $index => $sign) {
            $row = array_fill(0, count(ZodiacSign::$zodiacIdName), 0);

            $items->each(function ($item) use ($index, &$row) {
                if ($item['husband_sign'] == $index) {
                    $row[$item['wife_sign']]++;
                }
            });
            array_unshift($row, $sign);
            $rows[] = $row;
        }

        return view('main', ['header' => $header, 'rows' => $rows]);
    }
}
