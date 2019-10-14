<?php

namespace App\Console\Commands;

use App\Item;
use App\Other\VKHelper;
use App\Other\ZodiacSign;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ZodiacSignParser extends Command
{
    protected $signature = 'vk:parse';
    protected $description = 'Start Zodiac Sign Parse';

    protected $count;
    protected $accessToken;
    protected $apiVersion;
    protected $offset;

    protected function init()
    {
        $this->count = 1000;
        $this->accessToken = 'd5b28011342c3263844c6d6db7e798bf697c2220fd59077fd5781fd2e42c1b98cd9268e33e39559c1d1a5';
        $this->apiVersion = '5.102';
        $this->offset = Cache::has('offset') ? Cache::get('offset') : 0;
    }

    protected function process(array $items)
    {
        foreach ($items as $husband) {
            if (isset($husband['relation_partner']) && isset($husband['bdate'])) {

                $relationPartner = $husband['relation_partner'];

                $wife = VKHelper::getProfile($this->accessToken, $relationPartner['id'], $this->apiVersion);

                if ($wife == null || !isset($wife['bdate'])) {
                    continue;
                }

                $dateWife = VKHelper::parseVKDate($wife['bdate']);

                if ($dateWife == null) {
                    continue;
                }

                $dateHusband = VKHelper::parseVKDate($husband['bdate']);

                $husbandSignId = ZodiacSign::getZodiacSign($dateHusband['day'], $dateHusband['month']);
                $wifeSignId = ZodiacSign::getZodiacSign($dateWife['day'], $dateWife['month']);

                $husbandSignName = ZodiacSign::getZodiacSignNameById($husbandSignId);
                $wifeSignName = ZodiacSign::getZodiacSignNameById($wifeSignId);

                $this->info($husbandSignName . ' ' . $wifeSignName);

                if ($husbandSignId != -1 && $wifeSignId != -1) {
                    Item::checkAndCreate([
                        'husband_id' => $husband['id'],
                        'husband_date' => $this->toDate($dateHusband['day'], $dateHusband['month']),
                        'husband_sign' => $husbandSignId,
                        'wife_id' => $wife['id'],
                        'wife_date' => $this->toDate($dateWife['day'], $dateWife['month']),
                        'wife_sign' => $wifeSignId,
                    ]);
                }
            }
        }
    }

    protected function toDate($day, $month)
    {
        return ($day < 10 ? '0' . $day : $day) . '.' . ($month < 10 ? '0' . $month : $month);
    }

    public function handle()
    {
        $this->init();

        $this->info("H  W");

        $daysInMonth = [1 => 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        for ($year = 1970; $year <= 2001; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                for ($day = 1; $day <= $daysInMonth[$month]; $day++) {
                    $offset = 0;
                    while (true) {
                        $users = VKHelper::search($this->accessToken, $offset, $this->count, $day, $month, null, $this->apiVersion);
                        $count = count($users);
                        if ($count == 0) {
                            break;
                        }
                        $this->process($users);
                        $offset += $count;
                    }
                }
            }
        }


        $this->info('End');
    }
}
