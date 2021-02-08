<?php

namespace App\Logics;

class FaceLogic
{

    /**
     * 面相解析
     */
    public function analyse($order)
    {
        $result = '';

        $face = json_decode($order->facialfeatures_data, true);
        $skin = json_decode($order->skinanalyze_data, true);
        $detect = json_decode($order->detect_data, true);

        if ($face['result']['five_eyes']['three_eye']['eyein_result'] == 'eyein_long') {
            $result .= '性格上，你为人较聪明，做任何事情都能有把握，游刃有余。说话直爽，胸怀坦荡，具有包容心，对于一些事情，能够采取宽宏的态度，富有同情心。经常会路见不平，拔刀相助，侠义心肠浓厚。';
        } elseif ($face['result']['five_eyes']['three_eye']['eyein_result'] == 'eyein_short') {
            $result .= '性格上，你是个做事容易操之过急的人，往往会招来措手不及的失败。由于肚量不够宽大，会经常把一些小事放在心上，造成精神上的忧患，情绪容易紧张。所以，要学会放松，放松心胸，才能让心情愉快，保持平常心是关键。';
        }





















        return $result;
    }



}




