<?php

namespace App\Logics;

class FaceLogic
{

    protected $result;

    protected $sex;

    protected $age;

    protected $emotion;

    protected $smile;

    protected $beauty;

    protected $faceupResult;

    protected $facemidResult;

    protected $facedownResult;

    protected $eyeinResult;

    protected $righteyeEmptyResult;

    protected $lefteyeEmptyResult;

    protected $goldenTriangle;

    protected $faceType;

    protected $jawType;

    protected $eyebrowType;

    protected $eyesType;

    protected $noseType;

    protected $mouthType;

    protected $eyePouch;

    protected $darkCircle;

    protected $foreheadWrinkle;

    protected $crowsFeet;

    protected $eyeFinelines;

    protected $glabellaWrinkle;

    protected $nasolabialFold;

    protected $acne;

    /**
     * 面相解析
     */
    public function analyse($order)
    {
        $face = json_decode($order->facialfeatures_data, true);
        $skin = json_decode($order->skinanalyze_data, true);
        $detect = json_decode($order->detect_data, true);

        $this->sex = $detect['faces'][0]['attributes']['gender']['value']; //性别
        $this->age = $detect['faces'][0]['attributes']['age']['value']; //年龄
        $this->emotion = array_search(max($detect['faces'][0]['attributes']['emotion']), $detect['faces'][0]['attributes']['emotion']); //情绪识别结果
        $this->smile = $detect['faces'][0]['attributes']['smile']['value'] >= $detect['faces'][0]['attributes']['smile']['threshold']; //笑容分析结果
        $this->beauty = max($detect['faces'][0]['attributes']['beauty']['male_score'], $detect['faces'][0]['attributes']['beauty']['female_score']); //颜值识别结果
        $this->faceupResult = $face['result']['three_parts']['one_part']['faceup_result']; //上庭判断结果
        $this->facemidResult = $face['result']['three_parts']['two_part']['facemid_result']; //中庭判断结果
        $this->facedownResult = $face['result']['three_parts']['three_part']['facedown_result']; //下庭判断结果
        $this->eyeinResult = $face['result']['five_eyes']['three_eye']['eyein_result']; //内眼角间距判断结果
        $this->righteyeEmptyResult = $face['result']['five_eyes']['one_eye']['righteye_empty_result']; //五眼右侧判断结果
        $this->lefteyeEmptyResult = $face['result']['five_eyes']['five_eye']['lefteye_empty_result']; //五眼左侧距判断结果
        $this->goldenTriangle = $face['result']['golden_triangle']; //黄金三角度数
        $this->faceType = $face['result']['face']['face_type']; //脸型判断结果
        $this->jawType = $face['result']['jaw']['jaw_type']; //下巴判断结果
        $this->eyebrowType = $face['result']['eyebrow']['eyebrow_type']; //眉型判断结果
        $this->eyesType = $face['result']['eyes']['eyes_type']; //眼型判断结果
        $this->noseType = $face['result']['nose']['nose_type']; //鼻翼判断结果
        $this->mouthType = $face['result']['mouth']['mouth_type']; //唇型判断结果
        $this->eyePouch = $skin['result']['eye_pouch']['value']; //眼袋检测结果
        $this->darkCircle = $skin['result']['dark_circle']['value']; //黑眼圈检测结果
        $this->foreheadWrinkle = $skin['result']['forehead_wrinkle']['value']; //抬头纹检测结果
        $this->crowsFeet = $skin['result']['crows_feet']['value']; //鱼尾纹检测结果
        $this->eyeFinelines = $skin['result']['eye_finelines']['value']; //眼部细纹检测结果
        $this->glabellaWrinkle = $skin['result']['glabella_wrinkle']['value']; //眉间纹检测结果
        $this->nasolabialFold = $skin['result']['nasolabial_fold']['value']; //法令纹检测结果
        $this->acne = $skin['result']['acne']['value']; //痘痘检测结果

        if ($this->age <= 10) { //0-10
            return $this->sex == 'Male' ? $this->male10() : $this->female10();
        } elseif ($this->age <= 19) { //11-19
            return $this->sex == 'Male' ? $this->male19() : $this->female19();
        } elseif ($this->age <= 26) { //20-26
            return $this->sex == 'Male' ? $this->male26() : $this->female26();
        }elseif ($this->age <= 35) { //27-35
            return $this->sex == 'Male' ? $this->male35() : $this->female35();
        } elseif ($this->age <= 50) { //35-50
            return $this->sex == 'Male' ? $this->male50() : $this->female50();
        } else { //>50
            return $this->sex == 'Male' ? $this->maleInf() : $this->femaleInf();
        }
    }

    protected function male10()
    {
        if ($this->faceupResult == 'faceup_long') { //上庭偏长
            $this->result .= '孩子的天庭饱满，是个具有智慧与前程的面相。在未来的事业运势上，会比较顺风顺水，尤其是中年时期，在事业上会有所成就。在性格上，';
            if ($this->eyebrowType == 'bushy_eyebrows') { //粗眉
                $this->result .= '孩子的性格会较为外向，比较活泼好动，富有创造力，但是偶尔也会犯一些小错误。所以作为孩子的家长，应该在适当的时候，给予孩子耐心的教育和恰当的批评，这样会让孩子在成长的道路上越走越顺。';
            } elseif ($this->eyebrowType == 'thin_eyebrows') { //细眉
                $this->result .= '孩子的性格会有点内向，平时是个比较乖巧平静的人。同时，孩子也是一个心地善良、富有同情心、心思灵敏、具有良好的判断能力、喜欢学习的人。家长在平时的生活中，应该多让孩子与其他性格相似的小朋友们一起玩耍学习，这样会让孩子受益良多。';
            } else {
                $this->result .= '有些时候孩子的意志力会比较不坚定，做事容易优柔寡断，在处理与人交往的事情上会有所欠缺，但却是一个为人正直、能够快速判断是非的人。所以家长平时应该注重提升孩子的意志力，让孩子在将来的人生中不为一些不必要的事情所困扰。';
            }
        } elseif ($this->mouthType == 'smile_lip' || $this->emotion == 'happiness') { //微笑唇 或者 高兴的情绪
            $this->result = '你是一个性格开朗、乐观、自信、不爱生气的人，你所在的家庭也是一个氛围良好和睦的家庭。你往往是大家的开心果，身边的人都会很喜欢你。你长大之后，对生活、工作和感情都能够长时间保持激情、热情，总是以积极乐观的心态去面对生活中的人或事。';
            $this->result .= '烦恼、忧愁对你的影响都不大，因为你总是能够自主地调节这类不良的情绪，让积极乐观占据主动，不让这些不良的情绪影响生活和工作，所以你的一生都会生活地很轻松、幸福、快乐。';
        } elseif ($this->eyeinResult == 'eyein_long') { //内眼角间距偏宽

        } else {

        }

        return $this->result;
    }

    protected function female10()
    {
        return $this->result;
    }

    protected function male19()
    {
        return $this->result;
    }

    protected function female19()
    {
        return $this->result;
    }

    protected function male26()
    {
        return $this->result;
    }

    protected function female26()
    {
        return $this->result;
    }

    protected function male35()
    {
        return $this->result;
    }

    protected function female35()
    {
        if ($this->beauty >= 70) {
            $this->result .= '你带有多子多福之相，在后代生养问题上，你是可以少操心的，孩子也会聪明，健康，乖顺，懂事，成长顺利，学业也不错。';
            $this->result .= '同时，你自身也是带有富贵之相的，以后的人生也会较为地顺风顺水，会有一个安逸且快乐的人生。';
        }

        return $this->result;
    }

    protected function male50()
    {
        return $this->result;
    }

    protected function female50()
    {
        return $this->result;
    }

    protected function maleInf()
    {
        return $this->result;
    }

    protected function femaleInf()
    {
        return $this->result;
    }



}







