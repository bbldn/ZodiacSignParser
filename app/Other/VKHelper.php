<?php

namespace App\Other;

use Kozz\Laravel\Facades\Guzzle;

class VKHelper
{
    public static function search($token, $offset, $count, $day, $month, $year = null, $apiVersion = '5.102')
    {
        $url = "https://api.vk.com/method/users.search?status=2&search_global=1&access_token=${token}&v=${apiVersion}&fields=relation,bdate&sex=2";
        $part = "&offset=${offset}&count=${count}&birth_day=${day}&birth_month=${month}";

        if ($year != null) {
            $part .= "&birth_year=${year}";
        }

        $response = Guzzle::get($url . $part);

        $data = json_decode(strval($response->getBody()), true);

        if (!isset($data['response']['items'])) {
            return [];
        }

        return $data['response']['items'];
    }

    public static function getProfile($token, $id, $apiVersion = '5.102')
    {
        $url = "https://api.vk.com/method/users.get?user_ids=${id}&access_token=${token}&v=${apiVersion}&fields=bdate";

        $response = Guzzle::get($url);

        $data = json_decode(strval($response->getBody()), true);

        if (!isset($data['response']) || count($data['response']) == 0) {
            return null;
        }

        return $data['response'][0];
    }

    public static function parseVKDate($date)
    {
        preg_match('/^([0-9]{1,2})\.([0-9]{1,2})/', $date, $match);
        return ['day' => $match[1], 'month' => $match[2]];
    }

}
