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

    protected $skinStatus;

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
        $this->skinStatus = $detect['faces'][0]['attributes']['skinstatus']; //面部特征识别结果

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
        } elseif ($this->age <= 15) { //11-15
            return $this->sex == 'Male' ? $this->male15() : $this->female15();
        } elseif ($this->age <= 19) { //16-19
            return $this->sex == 'Male' ? $this->male19() : $this->female19();
        } elseif ($this->age <= 26) { //20-26
            return $this->sex == 'Male' ? $this->male26() : $this->female26();
        } elseif ($this->age <= 35) { //27-35
            return $this->sex == 'Male' ? $this->male35() : $this->female35();
        } elseif ($this->age <= 50) { //36-50
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
        } elseif ($this->mouthType == 'smile_lip' || $this->emotion == 'happiness' || $this->smile) { //微笑唇 高兴的情绪 有笑容
            $this->result = '你是一个性格开朗、乐观、自信、不爱生气的孩子，你所在的家庭也是一个氛围良好和睦的家庭。你往往是大家的开心果，身边的人都会很喜欢你。你长大之后，对生活、工作和感情都能够长时间保持激情、热情，总是以积极乐观的心态去面对生活中的人或事。';
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
        if ($this->beauty >= 70) { //颜值高
            $this->result = '一张可爱白腻的小脸蛋，常常带着俏皮笑容的小嘴巴，以及那双宛若闪亮星星的大眼睛，真是一个人间小精灵，从小就是个美人胚子。你的面相属于福慧双修型，福气会伴随着你的一生。同时你还是个从小就非常聪明的小女孩，你的家长对你期望颇高，';
            $this->result .= '但是家长要注意，在孩子成长的道路上不要给予太大的压力，家长也不要把孩子当成温室的花朵娇生惯养，要适当地让孩子认识到世间的险恶，让孩子形成正确的人生观与价值观，这样才能在以后的人生中不被别有用心的人所迷惑。';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } else {
            $this->result = '';
            $this->result .= '';
        }

        return $this->result;
    }

    /**
     * 男性 11-15
     */
    protected function male15()
    {
        if () {
            $this->result = '';
            $this->result .= '';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } else {
            $this->result = '';
            $this->result .= '';
        }

        return $this->result;
    }

    /**
     * 女性 11-15
     */
    protected function female15()
    {
        if ($this->beauty >= 70) { //颜值高
            $this->result = '你是个颜值很高的小女孩，在人群中，大家总是能够一眼就注意到你，你从小就是个美人胚子，长大之后更是会成为一个亭亭玉立的大美女。长得漂亮固然是一件好事，但是有些时候会引来一些同龄女生的羡慕与嫉妒，她们可能会在背后说你坏话，';
            $this->result .= '给你制造一些流言蜚语，这可能会给你造成很大的困扰。家长应该多注意观察孩子的情绪，平时与孩子多多交流沟通，如果出现情况要及时合理地处理，不要让孩子一个人独自承受压力与困扰，这样才能让孩子在以后的人生道路上走得更加顺利。';
        } elseif ($this->faceType == 'oval_face' || $this->jawType == 'flat_jaw' || $this->eyesType == 'big_eyes' || $this->eyebrowType == 'arch_eyebrows') { //椭圆脸 圆下巴 大眼 柳叶眉
            $this->result = '你是一个性格乐观开朗、充满自信并且富有同情心的小女孩，你一生的运势会较为不错，尤其是贵人运会很好，未来在工作中比较容易得到同事的帮助和上级的器重，比一般的女生更加容易获得事业上的成功。在学业上，你是一个能让父母省心的孩子，';
            $this->result .= '你会非常自觉地完成自己的功课，不让家长因为你的功课问题而过多地操心。虽然你的年纪不大，但你却是一个做事情井井有条、进退有度并且懂得分寸的女生，这都得益于你有着一个融洽的家庭氛围以及良好的家庭教育。';
        } elseif ($this->faceupResult == 'faceup_long') { //上庭偏长
            $this->result = '你是一个聪明过人、悟性高、学习能力很强的小女孩，很多新的知识，你往往只需要学习一遍就能够掌握，这是很多同龄人所没有的能力，因此你的学习成绩会比较优异，是同学眼中的"学霸"，老师眼中学习的好苗子。你的眼睛明亮且黑白分明，看起来很有灵气，';
            $this->result .= '而且会给人一种很舒服且很好相处的感觉，这让你在学校里的人缘会比较不错。在自身的运势上，你是一个有富贵命的人，长大之后会有一份幸福美满的婚姻和一份令人羡慕的工作，在生活上基本会一帆风顺，不会经历太多坎坷。';
        } elseif ($this->faceType == 'square_face' || $this->jawType == 'square_jaw' || $this->eyesType == 'thin_eyes' || $this->noseType == 'thick_nose' || $this->mouthType == 'upset_lip') { //方形脸 方下巴 细长眼 宽鼻 态度唇
            $this->result = '你是一个聪明伶俐且较为独立自主的小女孩，你的自尊心比较强，偶尔也会有点小倔强，做什么事情都不会轻易放弃，对待学习的态度也非常认真且严谨。你还是一个懂得体谅他人的人，你知道父母在外工作的辛苦，所以在家里会帮父母做些力所能及的事情，';
            $this->result .= '在学校里也是个乐于帮助同学的好孩子。在正值豆蔻年华的年龄，你已经学会不畏惧困难、懂得勇敢地面对一切困难阻力，你这种积极的生活态度会带给你好运，而且很有可能会在三十多岁的时候成就自己的一番事业。';
        } else {
            $this->result = '你是一个性情温和、善良、待人友善的小女孩，在与同学、朋友们相处的时候，你从来都不会与她们发生矛盾。你也是一个能够为他人着想、懂得关心别人、懂得顾及别人想法的小女孩，当你的朋友不开心的时候，你会耐心地去安慰、陪伴朋友，';
            $this->result .= '直到她们摆脱不开心的情绪。你也是父母眼中的乖乖女、小棉袄，你从来都不会去惹父母生气，而且也经常会帮家里做一些力所能及的家务，是一个能够让父母非常省心、放心的小女孩。';
        }

        return $this->result;
    }

    /**
     * 男性 16-19
     */
    protected function male19()
    {
        if ($this->faceupResult == 'faceup_long') { //上庭偏长
            $this->result = '';
            $this->result .= '';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } else {
            $this->result = '';
            $this->result .= '';
        }

        return $this->result;
    }

    /**
     * 女性 16-19
     */
    protected function female19()
    {
        if ($this->beauty >= 70) { //颜值高
            $this->result = '';
            $this->result .= '';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } elseif () {
            $this->result = '';
            $this->result .= '';
        } else {
            $this->result = '';
            $this->result .= '';
        }

        return $this->result;
    }

    /**
     * 男性 20-26
     */
    protected function male26()
    {
        if ($this->faceupResult == 'faceup_long') { //上庭偏长
            $this->result = '从你的面相上来看，你中年的运势会很好，事业上会比较成功。你很明确自己的目标，能把握自己未来的方向，知道自己想要什么。你也特别懂得如何去跟别人打交道，人际关系好，会遇到贵人相助。';
            $this->result .= '在个人感情上，你的情感道路会比较一帆风顺，会与你相爱的人有一个幸福的家庭。你的妻子会是你的贤内助，在你打拼事业的路上，默默地支持你，做你背后的女人。';
        } elseif ($this->eyebrowType == 'eight_eyebrows') { //八字眉
            $this->result = '你是一个性格温和、脾气好、品德好的人，表面上好像给人一种不易接近的感觉，实际上是一个非常和蔼可亲、待人为善的人。你在追求自己喜欢的女孩子的时候会不予余力，对女朋友会专心致志，并且能够做到体贴与温柔，甚至无微不至。';
            $this->result .= '结婚后会"怕老婆"，事事都以老婆大人为上，赚来的钱财也会上交给老婆去保管，是属于好好先生的类型。但同时异性情缘也会很好，这点可能会另你的爱人有点头疼。';
        } elseif ($this->mouthType == 'thick_lip') { //厚唇
            $this->result = '你是一个性格较为随和的人，你为人忠厚老实并且待人诚恳，做事情能够一步一个脚印，让人感觉非常踏实。你的个人运势也比较不错，到中年或者中晚年的时候，能够厚积薄发，在事业上取得一定的成绩。';
            $this->result .= '更令人羡慕的是，你还是个旺妻命。在你结婚之后，你会给你的妻子带来很多帮助和好运。嫁给你这样的男人，你的妻子在以后的日子里，会过得富裕、舒心、且幸福。';
        } elseif ($this->eyesType == 'big_eyes') { //大眼
            $this->result = '你是一个做事能够果断、专注，办事效率高，并且不会轻言放弃的人，无论什么事情，只要去做，就不会轻易地放弃，会坚持到底。在工作上是如此，在感情上更是如此。在感情上，你是一个对待感情认真且非常执着的人，';
            $this->result .= '与人交往不会轻易地说分手；婚后，对婚姻的忠诚度很高，你要的是天长地久，而不仅仅只是曾经拥有。性格上，你是一个比较外向、开朗、随和、待人热情的人，因此你的人缘也比较不错，是一个比较受欢迎的人。';
        } elseif ($this->faceType == 'round_face') { //圆形脸
            $this->result = '你的面相是一个比较有福气的面相，你的人缘和财运都比较不错。在事业上，你时常会有好的机遇，并且容易得到贵人的帮助。性格上，你是一个乐观爽朗、比较随和、容易与人相处的人，你的脾气很好，不易与人起冲突，因此身边总是有不少朋友。';
            $this->result .= '你的异性缘也较好，对女生们，你总是能够作为一个暖男，嘘寒问暖，温柔体贴，因此女生们都很愿意与你相处。虽然异性缘好，但你的爱情道路会有点坎坷，如果能做到对爱人一心一意、专心致志，你也能够收获一份幸福的婚姻爱情。';
        } else {
            $this->result = '你是一个做事稳健、凡事都能够想得比较周到的人。在事业上，你会是一个很好的辅佐之才，你通常都是辅佐别人成功，但是自己却比较难成大业。如果想在事业上有所成就，在辅佐他人的同时，也应该注重建立自己的人际关系。';
            $this->result .= '经过长久的积累之后，你也会有机会成就一番自己的事业。你的异性缘一般，桃花运不是很旺盛，但是却能够遇到能与自己相爱一生、陪伴一生的爱人，这实在是一件非常幸运且幸福的事。';
        }

        return $this->result;
    }

    /**
     * 女性 20-26
     */
    protected function female26()
    {
        if ($this->beauty >= 70) { //颜值高
            $this->result = '你是个魅力十足的女生，你的颜值高，容易让人过目不忘，对异性有很大的吸引力和杀伤力，很多异性会主动接近你，身边从来都不乏追求者。你的感情生活比较丰富，在挑选男朋友时会比较小心谨慎，会考察对方的各种条件。';
            $this->result .= '一旦与异性交往时，也能够做到专一与一心一意，对于其他异性的邀约，也会合理地拒绝。你的婚姻也会比较幸福，能够和一个爱你、懂得呵护、疼惜你的人结婚，婚姻生活会过得甜甜蜜蜜，令人羡慕。';
        } elseif ($this->nasolabialFold == 1 || $this->faceType == 'square_face' || $this->eyebrowType == 'bushy_eyebrows' || $this->jawType == 'square_jaw') { //有法令纹 方形脸 粗眉 方下巴
            $this->result = '你是个性格沉稳，略微带点强势的女生。在工作事业上，你有自己的想法，有自己的目标。你的办事能力较强，做事总是有条不紊，对于工作勇于负责，肯吃苦耐劳，未来在事业上能够做出一番成就，很多男性都比不过你。';
            $this->result .= '在婚姻上，建议你找一个顾家、有家庭责任感、老实本分的男人结婚，这样你们在性格上就能够做到相互弥补，你们的家庭生活就会过得和和美美，小日子也会越来越滋润。';
        } elseif ($this->faceupResult == 'faceup_long' || in_array($this->eyebrowType, ['straight_eyebrows', 'arch_eyebrows']) || $this->eyesType == 'big_eyes') { //上庭偏长 一字眉 柳叶眉 大眼
            $this->result = '你是一个心灵手巧、聪慧知礼、有教养有担当、富有爱心和正义感的女生。你的面相看起来比较有贵气，是属于富贵好命的面相。在感情上，你年轻时可能会遇人不淑，感情经历可能会比较坎坷不顺，但是最终能够与你步入婚姻殿堂的，';
            $this->result .= '必是一个值得你托付终身的人。你们会有一个充满幸福与爱的小家庭，生活上不会有太多的忧愁烦恼，基本不会为钱财发愁。除此之外，你会有几个关系一直都很好的闺蜜，在以后的人生中能够相互陪伴、相互扶持。';
        } elseif ($this->mouthType == 'smile_lip' || $this->emotion == 'happiness' || $this->smile || in_array($this->faceType, ['round_face', 'oval_face'])) { //微笑唇 高兴的情绪 有笑容 圆形脸 椭圆脸
            $this->result = '你是一个性格开朗、活泼、自信、乐观的女生。你的面相比较有福气，从一生总体上来看，你的运势会比较不错，身体也会比较健康、少生病，晚年会过得比较安逸，且常有儿孙陪伴。在感情上，你的情感之路不会很坎坷，';
            $this->result .= '能够比较顺利地与相爱之人步入婚姻殿堂，婚后夫妻之间能够互相包容、相亲相爱。你还是一个有旺夫运的女生，能给丈夫的事业带来好运，使其事业顺利、财运亨通。';
        } else {
            $this->result = '你是一个重感情的女生，不管是闺蜜情还是爱情，都是如此。对于闺蜜好友，你总是能够真心以待，即使她们有这样或那样的缺点，你对她们都能够非常包容与信任。对待爱情，你是那种一旦陷入便很难自拔的人，你会很珍惜你的爱人，';
            $this->result .= '不会轻易地说分手，事事都会替对方考虑，会非常用心地经营你的爱情。性格上，你是一个有些许内向、慢热的人，你不会很快地与陌生人打成一片，但是一旦与人熟悉之后，便会放开自己的心扉，表达真实的自我。';
        }

        return $this->result;
    }

    /**
     * 男性 27-35
     */
    protected function male35()
    {
        if ($this->faceupResult == 'faceup_long') { //上庭偏长
            $this->result = '你是一个非常聪明且有才华的人，你的性格非常的好，很平易近人，因此身边的朋友也很多。在事业上，你是一个很有事业心的人，对待工作认真负责，肯拼搏、肯奋进，在事业上会取得较高的成就。';
            $this->result .= '你的财运也比较旺盛，在后面的人生中，只要不乱花钱，基本不会缺钱花，是一个富贵命。感情上，你基本不会遇到什么挫折，与爱人间的关系能够较为平稳和睦，家庭生活会比较和谐。';
        } elseif ($this->mouthType == 'thick_lip') { //厚唇
            $this->result = '你喜欢说理式的表达，在与人探讨交流时，你的表情与肢体动作时常会流露出充满理性的气息，这对于一些异性来说，是非常有魅力与吸引力的。你的这种理性、冷静的形象气质，会让她们在倾倒之余，还能增加对你的信服度。';
            $this->result .= '同样在工作上，你也会比较受到上级的器重，他们对于交待给你的事情，都能够比较放心。在同事之间，每当遇到棘手的问题的时候，大家也都比较信任你的判断与解决方案。';
        } elseif ($this->eyesType == 'thin_eyes') { //细长眼
            $this->result = '你是一个喜欢思考、并且善于思考的人。你做事情往往能够深谋远虑，在工作上出现问题的时候，总能找到合适的解决办法，所以会给身边人一种靠谱的感觉。同时你也不是一个随性的人，不管是在工作或者生活中，';
            $this->result .= '你都是一个很有责任感的人。在对待感情上，你是一个非常专一、不容易被身边花花绿绿的人或事所诱惑的人，因此你的爱人会对你比较放心，你的家庭氛围也会较为和睦。';
        } elseif ($this->glabellaWrinkle == 1) { //有眉间纹
            $this->result = '你是个性格稳重、为人靠谱、做事踏实的人。你做事情从来都不会急于求成，因为你知道急急忙忙反而容易出错、适得其反，因此每次都能够很好地完成工作上的任务。你也是很注重提升自己能力的人，你喜欢学习，觉得学习是一件有趣的事情，';
            $this->result .= '这让你在职场上始终能够保持非常强大的竞争力，你不会害怕失业，所谓的中年焦虑根本无法影响到你。你的人缘也比较好，当别人遇到问题的时候，你会愿意提供帮助，因此你能够结实到许多好朋友。';
        } elseif ($this->jawType == 'flat_jaw') { //圆下巴
            $this->result = '你的面相是一个有福之相，人生中不会遇到太多的坎坎坷坷，一生的运势都会比较好。你的性格温和，为人乐观，做事实在，平时人缘也较好，而且有贵人运。在接下来的几年中，如果能够继续勤勤恳恳工作，那么很有可能会有贵人给你带来好运，';
            $this->result .= '到时你的财运会非常旺盛，以后的人生中不会再为钱财发愁。在感情上，你是一个懂得爱护妻子的人，你的妻子会是你生活和事业上的好帮手，你的家庭生活也会较为美满，令人羡慕。';
        } else {
            $this->result = '你是一个有家庭责任心的男人。在外工作，你能够勤勤恳恳、尽心尽力；在家里，你懂得扛起家庭的重担，帮助妻子处理家务，对家庭尽你该尽的义务，因此你的家庭氛围会比较和睦。你对老婆也非常疼爱，懂得照顾、爱护妻子，';
            $this->result .= '对于女人来说，你是一个值得托付终生的人。你的晚年生活会比较平平稳稳，不会有太多的坎坎坷坷，会有一个比较幸福的晚年。而且能够子孙满堂，享尽天伦之乐。';
        }

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
     * 男性 36-50
     */
    protected function male50()
    {
        if ($this->facedownResult == 'facedown_long') { //下庭偏长
            $this->result = '你是一个性格随和、宽厚的人。在与人相处时，你总是能够做到真诚以待，因此你的人际关系也比较好。在对待自己的事业上，你是一个努力奋斗、积极向上、踏实肯干的人，因此你的工作伙伴都很乐意与你一起共事。';
            $this->result .= '你中晚年的运势会很好，是属于晚年大发的类型，届时将会时来运转，迎来自己事业上的巅峰。你的家庭生活会和和美美，你与你爱人的关系也是较为和睦，不会在感情上有太多坎坷磨难。';
        } elseif ($this->jawType == 'flat_jaw') { //圆下巴
            $this->result = '你的面相看起来特别地有福气，是属于比较典型的福气相。你的早年家境较为贫寒，吃过很多苦，但后面经过自己一步步多年地努力打拼，也拥有了一份属于自己的事业，慢慢地积累了不少的财富，生活上会过得比较不错。';
            $this->result .= '你平时待人都是乐呵呵的，性格也比较好，善于处理自己的情绪，不易与人发生矛盾冲突，有几个关系不错、能够相互帮扶的朋友，在以后的人生中，基本不会再经历太多的风雨。';
        } elseif ($this->faceType == 'square_face' || $this->jawType == 'square_jaw') { //方形脸 方下巴
            $this->result = '你是个有韧劲、不怕吃苦、有上进心、做事有毅力有决心的人，只要是自己认定的事情，就会一如既往地坚持下去。同时，你是个刚正不阿、做事情一板一眼、脚踏实地的人，因此总是能给人沉稳、大度、可靠、有力量的感觉。';
            $this->result .= '你具有很强的责任感，做事情认真负责，生怕自己会出错。而且在家庭方面的责任感更强，对老婆孩子上心、负责，为了家庭的美好生活，会努力地工作赚钱养家，让家庭生活越来越好。';
        } elseif ($this->beauty >= 70) { //颜值高
            $this->result = '在你这个年龄段的时候，你的许多同龄人，面容上早已显现出岁月的沧桑，要不成为中年油腻大叔，被人嫌弃；要不过早衰老，颓相尽显。但岁月却没有在你的脸上留下太多的刻痕，对于异性的吸引力，即使是那些年轻的“小鲜肉”，';
            $this->result .= '也都远远比不上你。岁月带走了你的年少轻狂，悄悄地为你增添了一抹成熟的气息，令人着迷。在以后的日子里，也要继续安安静静地做个岁月静好、安然若素的“美男子”。';
        } else {
            $this->result = '你是个为人厚道、诚笃，懂得善待身边人、与人为善的人，因此，你有着相当不错的人缘，在生活和事业上常常能够得到他人的帮助与支持。你经历过非常多的生活上的磨练，造就出了一副坚强的意志和宽阔的胸襟，';
            $this->result .= '做事情往往能够善始善终，不会轻易摆手、轻言放弃。一旦遇到事业上的挫折时，总能轻松应对，愈挫愈勇。岁月的磨砺，赋予了你足够的智慧与才能，让你在今后的人生中，能够越行越稳，越走越顺。';
        }

        return $this->result;
    }

    /**
     * 女性 36-50
     */
    protected function female50()
    {
        if ($this->beauty >= 70) { //颜值高
            $this->result = '岁月匆匆，却依旧带不走你的光彩；时光荏苒，你的容颜依然动人心扉。光阴似箭，时间增长了你的年龄，却沉淀了你的优雅；时间在你脸上刻画了淡淡的细纹，同时也为你带来了成熟的风韵。你是一生都能够美丽且优雅的女人，';
            $this->result .= '幸福是上天赏赐给你的永远的礼物，荡涤去你身边起落的尘埃，留下不动声色的温柔与从容。你是老天爷的幸运儿，他会让你度过一个幸福快乐的人生，这是其他人很难得到的好运。';
        } elseif (in_array($this->eyesType, ['big_eyes', 'round_eyes'])) { //大眼 圆眼
            $this->result = '你是个很能理解和体谅别人、没什么心机、性格豁达且淡然的人。在生活中，你是个很好相处，并且相处起来能够令人感到很舒服的人。你待人热情大方，愿意与朋友分享自己的喜乐，因此身边总是会有很多玩得好的闺蜜朋友。';
            $this->result .= '在家庭上，你是个热爱家庭、肯为了家庭的安稳和幸福做出牺牲的人，因此你的家庭生活能够较为和美，夫妻之间能够互相恩爱、敬重，在往后的漫漫岁月里，过着一个幸福、安稳、有乐趣的人生。';
        } elseif (in_array($this->faceType, ['pointed_face', 'oval_face'])) { //瓜子脸 椭圆脸
            $this->result = '你的脸型较为细长，整个脸的比例比较平均，是很有福气的面相。你在生活中的运气较好，有困难和烦恼的时候，总是能够得到很多人的帮助。对于丈夫，你是他家里的贤内助，事业上的好帮手，能够给丈夫带来好运，';
            $this->result .= '让他在事业上较为顺心；对于后代，你懂得给与耐心的培养、合适的教导，使其在为人处世上能够得体大方、稳重从容。你的晚年能够享受到子女带来的福分，会有一个老有所依、老有所乐的幸福晚年。';
        } elseif ($this->faceupResult == 'faceup_long' || $this->facedownResult == 'facedown_long' || $this->faceType == 'square_face') { //上庭偏长 下庭偏长 方形脸
            $this->result = '你是个性格沉稳、意志坚定、目光长远、善于谋划的女性，你有着很强的分析能力和判断能力，比较善于处理各种复杂的人际关系，人缘也比较旺。你有着非常出色的工作才能，善于打理事业，做事情执行力强，在事业上会有着不错的运势，';
            $this->result .= '财运也非常不错。你在家庭上付出的精力相对会较少，但这一点也不影响你的家庭和谐，因为你懂得如何有效地经营自己的夫妻关系、亲子关系，因此能够做到家庭事业双丰收。';
        } else {
            $this->result = '你是个很懂得居家过日子的人，对于家庭来说，你是一个贤惠的妻子，也是一个合格的母亲。你心思细腻，做事仔细，考虑事情周到，家里总是被你打理地井井有条、干干净净，因此丈夫能够很放心地把家庭里的事情交给你，';
            $this->result .= '从而专心去做自己的事业。你还是个说话懂得分寸且很有智慧的女性，你知道如何与家中长辈和谐相处，与子女的感情也非常深厚。你是个肯为了家庭全身心付出的人，但是爱家庭的同时，也不要忘了好好地爱自己。';
        }

        return $this->result;
    }

    /**
     * 男性 >50
     */
    protected function maleInf()
    {
        if (in_array($this->eyebrowType, ['straight_eyebrows', 'round_eyebrows']) || $this->eyesType == 'big_eyes' || $this->skinStatus['health'] >= 60) { //一字眉 拱形眉 大眼 面部特征健康
            $this->result = '从你的面相上来看，你晚年的运势总体来说会较为不错，而且你的面相也是一副长寿相。你的气场很好，不输于一般的年轻人。身体也比较健康、硬朗，基本上会无病无灾，不会碰到什么大病或者灾难。你的性格开朗，心态也很好，';
            $this->result .= '能够一直保持年轻态，对未来乐观，再大的事情也能看开，不放在心里，没有心病。你的子孙后代大都是非常孝顺的，懂得尊老爱老，所以能够享受到天伦之乐，家庭氛围也会非常和乐融融。';
        } elseif ($this->eyebrowType == 'bushy_eyebrows' || $this->noseType == 'thick_nose' || $this->jawType == 'flat_jaw') { //粗眉 宽鼻 圆下巴
            $this->result = '你的面相是个非常有福气的面相，老年运势较好，不会为钱财所困；身体较为健康，可能会偶有不适，却不会有大的疾病；夫妻之间长年恩爱相伴，白头偕老；子女有出息且孝顺，能够享受到含饴弄孙的乐趣，会有一个安逸、幸福且清闲的晚年。';
            $this->result .= '在性格上，你的性情温和，胸怀坦荡，具有包容心，有体谅人的雅量，因此你的人缘会比较不错，有几个关系很好、年头很长的老伙伴，而且年轻人也愿意和你打交道，因此你的晚年生活会丰富多彩，绝不会感到孤独。';
        } elseif ($this->mouthType == 'smile_lip' || $this->emotion == 'happiness' || $this->smile) { //微笑唇 高兴的情绪 有笑容
            $this->result = '你是一个心胸开阔、乐观开朗、心态非常好的人，虽然年龄一年大过一年，但你却从不会为此感到忧虑，因为你深知一个良好的心态的重要性。正是因为如此，你始终能够保持一个健康、状态良好的身心，';
            $this->result .= '让你看起来比同一年龄段的人更加年轻、更加精神、更加有气色。正所谓，年龄虽不少，青春志不小；人老心不老，开心便年少；心态永年轻，青春满怀抱。在以后的日子里，也要继续保持住良好的心态，过一个快乐的晚年。';
        } elseif ($this->mouthType == 'thick_lip' || $this->faceupResult == 'faceup_long' || $this->jawType == 'sharp_jaw') { //厚唇 上庭偏长 尖下巴
            $this->result = '你是一个即使年纪大了，也依旧闲不下来的人。和同年龄段的人相比，你有着更加旺盛的精力。你喜欢追求新鲜事物，平时看到好玩的事情就会去尝试做一做，没事的时候经常会和一群志同道合的老伙伴相聚在一起谈天说地，';
            $this->result .= '畅谈以前的生活和最近的状况。你绝对不会去过那种墨守陈规、死气成成的日子，你会让自己以后的每一天都过得非常充实。正是因为你是这样的一个人，所以你的晚年生活不会很无聊、寂寞。';
        } else {
            $this->result = '岁月如飞刀，刀刀催人老。随着时光地流逝，你的年龄也在一天天地增长，但是岁月却让你拥有了一份非常丰富的人生阅历，这让你的身上时常透露出一股淡定、从容的气息。与年轻人急急燥燥的行事风格不同，你遇事稳重，不慌不忙，';
            $this->result .= '不急不燥，能够井井有条地把事情处理好，这是长久的岁月磨砺所带来的成果。对于后辈，你有着一颗慈爱、宽厚、懂得包容的心，你愿意把自己的人生经历分享给他们，从而让他们在今后的人生中能够少走弯路。';
        }

        return $this->result;
    }

    /**
     * 女性 >50
     */
    protected function femaleInf()
    {
        if ($this->beauty >= 70) { //颜值高
            $this->result = '惟草木之零落兮，恐美人之迟暮。而你却是美人不迟暮，暮年也芳华。你是那么的与众不同，优雅、端庄、美丽这些词语用来形容你的美，永远都不会过时，你身上的那种美，在历经岁月的沉淀、时光的打磨后，更加地醇厚迷人。';
            $this->result .= '上天对你是那么地偏爱，你的同龄人在你这个年龄段的时候，大多数早已芳华流逝，不余遗香，而你却能够既留下貌美的容颜，又沉淀出岁月的优雅。你会有一个美丽且好运的晚年，这是上天赏赐给你的最好的礼物。';
        } elseif ($this->skinStatus['health'] >= 60 || $this->eyesType == 'big_eyes' || in_array($this->eyebrowType, ['straight_eyebrows', 'round_eyebrows'])) { //面部特征健康 大眼 一字眉 拱形眉
            $this->result = '从你的面相特征上来看，你的面相是属于长寿相。你的身体常年都较为健康，基本上不会有大疾大病，子女不用为你的身体健康问题操心。同时也别忘记要养成经常锻炼、定期体检的习惯，遵循良好的生活方式，会让你在长寿之路上越走越远。';
            $this->result .= '一个健康的身体，绝对离不开一个良好的心态，你是一个心态非常好、不爱与人斤斤计较、不爱攀比的人，遇到不开心的事，碰见不顺眼的人，你总是能够把心放宽，因此你的晚年会比别人活得更加轻松自在。';
        } elseif ($this->mouthType == 'smile_lip' || $this->emotion == 'happiness' || $this->smile) { //微笑唇 高兴的情绪 有笑容
            $this->result = '常言道：笑一笑，十年少。你是一个心态很好、性格乐观开朗的人。你懂得知足常乐，对物质生活没有过高的要求，是个很容易在物质上得到满足的人。你懂得以豁达的心态去看待世界，不会去做一些毫无意义的妄想，也不爱和他人攀比，';
            $this->result .= '因为你知道这只会徒增烦恼，因此你始终能够保持着一个健康良好的身心，这让你看起来比同龄人更加年轻、有气色、有活力。在往后的生活中，保持住这份良好的心态，会让你的晚年生活过得轻松、惬意、幸福。';
        } elseif ($this->facedownResult == 'facedown_long' || $this->faceupResult == 'faceup_long' || $this->eyesType == 'thin_eyes') { //下庭偏长 上庭偏长 细长眼
            $this->result = '你是个性格温柔善良、并且乐于助人的人。你年轻的时候能够很好地经营自己的家庭，因此你晚年的福气和运势都很好，是属于晚年能够享福的那类人。你基本上不会为钱财发愁，不会被财所困；你的家庭氛围也非常和谐，与丈夫能够相互扶持、白头偕老；';
            $this->result .= '与后辈能够和睦相处，儿女促膝，子孙围绕，能享受到子孙后代带来的快乐与福气；与邻里之间能够友好共处，互相帮助。你的身体也会健健康康、少生病，与此同时，不要忘记时常保持一副好心态，保持笑口常开哦。';
        } else {
            $this->result = '你是个很有善心的人，你的身上总是能够让人感受到，从内而外散发出的仁慈祥和的光芒，善良的人，总是受人欢迎，让人尊敬。你也是一个很有耐心的人，随着年龄的增长，有些事情做起来不如年轻的时候那么顺利，你慢慢地学会了遇事不冲动，';
            $this->result .= '心里不急躁，对待事情，你能够非常耐心地去解决，对自己多一份耐心，是智慧。你还是个能让子女省心的人，你能够很好地照顾好自己和丈夫，不让在外打拼的子女担忧，让他们能够全身心投入到事业中。';
        }

        return $this->result;
    }



}







