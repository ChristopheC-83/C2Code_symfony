<?php

namespace App\Service;




class CreditsDatasProvider
{



    public function getCreditsDatas(): array
    {
        return [
            [
                'credits' => 100,
                'price' => 5,
                'img'  =>   "credits_100.webp",
                'purchase_id' => 'price_1QtYLiPl7UDO5tMUIeqqsuL7'

            ],
            [
                'credits' => 200,
                'price' => 9,
                'img'  =>   "credits_200.webp",
                'purchase_id' => 'price_1QtYMlPl7UDO5tMUASL2HOZL'

            ],
            [
                'credits' => 500,
                'price' => 20,
                'img'  =>   "credits_500.webp",
                'purchase_id' => 'price_1QtYNyPl7UDO5tMUAHl9XyVR'

            ],

        ];
    }
}