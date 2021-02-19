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

    /**
     * 男性 0-10
     */
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
            $this->result = '你是一个很聪明，并且记忆力特别好的小孩，任何事物只要见过一眼便会深深记住。所以在学习上，你会比其他的小孩更加轻松，往往也会取得更好的成绩。在以后的为人上，你会是一个温和敦厚、朴实善良的人，别人和你相处的时候，会很放松自在。';
            $this->result .= '性格上，你是比较随兴、不拘小节、不会与别人斤斤计较的人，但有时会缺乏判断力和过于相信他人，容易被他人利用，所以家长应该多培养孩子的判断能力。';
        } elseif ($this->eyeinResult == 'eyein_short') { //内眼角间距偏窄
            $this->result = '你是一个心思细腻的孩子，做事情通常可以抓住要领，并且善于处理麻烦的事情。所以在学习上，你往往很快就能抓住重点，学习的效率会比其他同学高。你也是一个警觉性、观察力比较强的人，在以后的工作中会比较有谋略，大家都喜欢征求你的想法和意见。';
            $this->result .= '在以后的感情上，你也会比较受女生的欢迎，但是也容易产生一些不必要的感情纠葛，所以家长应该从小培养孩子正确的感情观。';
        } else {
            $this->result = '你是一个有福气的孩子，一生都不会有大灾大难，身体也会非常健康，能够顺顺利利地成长。在学习上，你的成绩会比较稳定，父母在你的学习上也可以少操心，是属于能让家长放心的类型。在未来的事业上，会较为地平凡，不会有太大的波动，';
            $this->result .= '因此在生活上也会比较稳定，不会有太大的波澜起伏。如果想要在事业上做出一番成就，就要做到敢想敢做，有自己独特的想法，而不是人言亦言，跟着别人走，所以家长在孩子小的时候，就要注重培养孩子独立思考的能力。';
        }

        return $this->result;
    }

    /**
     * 女性 0-10
     */
    protected function female10()
    {
        return $this->result;
    }

    /**
     * 男性 11-19
     */
    protected function male19()
    {
        return $this->result;
    }

    /**
     * 女性 11-19
     */
    protected function female19()
    {
        return $this->result;
    }

    /**
     * 男性 20-26
     */
    protected function male26()
    {
        return $this->result;
    }

    /**
     * 女性 20-26
     */
    protected function female26()
    {
        return $this->result;
    }

    /**
     * 男性 27-35
     */
    protected function male35()
    {
        return $this->result;
    }

    /**
     * 女性 27-35
     */
    protected function female35()
    {
        if ($this->beauty >= 70) { //颜值高
            $this->result = '你的面相贵气逼人，属于雍容富贵之相，并且天生就带有旺夫运。你的财运会特别好，也愿意用自己的财力帮助他人，因此平时人缘也特别好。性格上，你非常地善解人意，别人和你相处的时候，会感觉轻松自在。';
            $this->result .= '你以后的人生也会较为地顺风顺水，身体上也会很少生病，会有一个安逸、幸福且快乐的人生。而且你带有多子多福之相，在后代生养问题上，你是可以少操心的，孩子也会聪明，健康，乖顺，懂事，成长顺利，学业也不错。';
        } elseif (in_array($this->eyebrowType, ['raise_eyebrows', 'round_eyebrows', 'arch_eyebrows'])) { //上挑眉 拱形眉 柳叶眉
            $this->result = '你是个脾气好、性格温和、看重感情、温柔贤惠、善良体贴的人。你的面相自带福气，自身财运好，会赚钱、会花钱，能够嫁给一个实力强劲，并且对自己好的男人。结婚以后家庭幸福美满，日子会过得很幸福，有人疼有人爱，';
            $this->result .= '是属于好命的女人。你的人缘也比较好，在平时生活中，你为人大度，待人诚恳，不爱斤斤计较，与人交往具有包容心，会给人一种如沐春风的感觉。';
        } elseif ($this->faceupResult == 'faceup_long' || $this->noseType == 'thick_nose' || $this->nasolabialFold == 1 || $this->faceType == 'square_face') { //上庭偏长 宽鼻 有法令纹 方形脸
            $this->result = '你是一个有智慧、有头脑、有才华的女人。你为人处事大方得体，做事认真负责，聪明能干，学习能力强，既有实力又有能力。你擅长与人沟通交流，通常人际关系不错，身边贵人也多，财运也较好。';
            $this->result .= '你的事业心比较强，无论是自己创业还是打工上班，都是很有成绩和成就的，属于女强人类型。你的性格略微强势，无论是在感情方面还是在职场中，都喜欢占据主导地位，不喜欢被人约束和管制，人生会比较自在与精彩。';
        } elseif (in_array($this->eyesType, ['round_eyes', 'big_eyes']) || in_array($this->faceType, ['pointed_face', 'oval_face'])) { //圆眼 大眼 瓜子脸 椭圆脸
            $this->result = '在感情上，你是一个有福相的女人。你会嫁给一个与你真心诚意相爱的男人，你的婚姻会很幸福美满。你是一个会体贴爱人、能为爱人着想、会考虑爱人感受的人。而且你也会尊重爱人在事业上的抉择，并且在背后默默地支持，';
            $this->result .= '能娶到像你这样的女人，是你爱人的福气。同时，你也很会处理婆媳之间的关系，会孝顺公婆，所以你与公婆的关系也会很和谐，公婆也比较能容忍和尊重你的个性。不会像有些人那样，和公婆的关系搞得很紧张。';
        } else {
            $this->result = '你是一个注重家庭的女人。你温婉贤惠，善解人意，为人亲切，待人体贴入微，很会照顾人，充满女人味，是一个典型的贤妻良母类型的女人，颇受身边人的喜爱。你的家庭生活会有些许地平淡，但在平淡的生活中又时常带有小幸福，';
            $this->result .= '一个平平淡淡且幸福的家庭，才是最让人羡慕的。在后代问题上，你比较看重孩子的教育，也愿意为了教育好孩子而付出更多的心力，但是不会把自己的意愿强加给孩子，你的孩子也会是一个有幸福感的人。';
        }

        return $this->result;
    }

    /**
     * 男性 35-50
     */
    protected function male50()
    {
        return $this->result;
    }

    /**
     * 女性 35-50
     */
    protected function female50()
    {
        return $this->result;
    }

    /**
     * 男性 >50
     */
    protected function maleInf()
    {
        return $this->result;
    }

    /**
     * 女性 >50
     */
    protected function femaleInf()
    {
        return $this->result;
    }



}







