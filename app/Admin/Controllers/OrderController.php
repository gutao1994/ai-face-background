<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Grid\Displayers\Actions;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Show\Tools;
use App\Common\HandyClass;
use Illuminate\Support\Facades\Storage;

/**
 * @property \App\Services\FileService $fileService
 * @property \App\Logics\DrawLogic $drawLogic
 */
class OrderController extends AdminController
{

    use HandyClass;

    protected $title = '订单管理';

    protected $status = [
        10 => '已支付',
        20 => '完成上传头像',
        30 => '完成皮肤分析API',
        40 => '完成面部特征分析API',
        50 => '完成DetectAPI',
        60 => '完成面相分析',
        70 => '失败',
    ];

    protected function grid()
    {
        $that = $this;
        $grid = new Grid(new Order());

        $grid->column('id', 'Id')->sortable();
        $grid->column('no', '订单号')->display(function ($val) {
            return "<a href='/admin/order/{$this->id}'>{$val}</a>";
        });
        $grid->column('user.nickname', '订单用户')->display(fn($val) => "<a target='_blank' href='/admin/wx_users/{$this->user_id}'>{$val}</a>");
        $grid->column('shareUser.nickname', '分享者用户')->display(fn($val) => "<a target='_blank' href='/admin/wx_users/{$this->share_user_id}'>{$val}</a>");
        $grid->column('amount', '金额')->money();
//        $grid->column('img', '照片')->display(fn($val) => $val ? $that->fileService->genOssUrl($val) : '')->image('', 50, 40);
        $grid->column('api_error_count', 'API调用错误次数');
        $grid->column('status', '订单状态')->using($this->status);
        $grid->column('created_at', '创建时间');

        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->disableColumnSelector();
        $grid->disableExport();

        $grid->actions(function (Actions $actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });

        $grid->filter(function (Filter $filter) {
            $filter->disableIdFilter();
            $filter->equal('share_user_id', '分享者Id');
            $filter->equal('user_id', '订单用户Id');
            $filter->equal('status', '订单状态')->select($this->status);
            $filter->between('created_at', '创建时间')->datetime();
        });

        return $grid;
    }

    protected function detail($id)
    {
        $that = $this;
        $order = Order::query()->findOrFail($id);
        $show = new Show($order);

        $show->field('id', 'Id');
        $show->field('no', '订单号');
        $show->field('user.nickname', '订单用户')->unescape()->as(fn($val) => "<a target='_blank' href='/admin/wx_users/{$this->user_id}'>{$val}</a>");
        $show->field('shareUser.nickname', '分享者用户')->unescape()->as(fn($val) => "<a target='_blank' href='/admin/wx_users/{$this->share_user_id}'>{$val}</a>");
        $show->field('amount', '金额')->money();
        $show->field('status', '订单状态')->using($this->status);
        $show->field('api_error_count', 'API调用错误次数');
        $show->field('img', '照片')->as(fn($val) => $val ? $that->fileService->genOssUrl($val) : '')->zoom();

        if ($order->status == 60) {
            $show->field('img-three-parts-five-eyes', '三庭五眼照片')->as(function () use ($that) {
                return $that->fileService->genOssUrl($that->drawLogic->threePartsFiveEyesSuffix($this->img));
            })->zoom();

            $show->field('img-face-structure', '脸部结构照片')->as(function () use ($that) {
                return $that->fileService->genOssUrl($that->drawLogic->faceStructureSuffix($this->img));
            })->zoom();

            $show->field('img-five-sense', '五官照片')->as(function () use ($that) {
                return $that->fileService->genOssUrl($that->drawLogic->fiveSenseSuffix($this->img));
            })->zoom();
        }

        $show->field('face_result', '面相分析结果');
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '最近更新时间');

        if ($order->status == 60) {
            $order->skinanalyze_data = json_decode($order->skinanalyze_data, true);
            $order->facialfeatures_data = json_decode($order->facialfeatures_data, true);
            $order->detect_data = json_decode($order->detect_data, true);

            $show->divider();

            $show->field('age', '年龄')->as(function () {
                return $this->detect_data['faces'][0]['attributes']['age']['value'];
            });

            $show->field('gender', '性别')->as(function () {
                return $this->detect_data['faces'][0]['attributes']['gender']['value'] == 'Male' ? '男' : '女';
            });

            $show->field('emotion', '情绪')->as(function () {
                return [
                    'anger' => '愤怒', 'disgust' => '厌恶', 'fear' => '恐惧', 'happiness' => '高兴', 'neutral' => '平静', 'sadness' => '伤心', 'surprise' => '惊讶'
                ][array_search(max($this->detect_data['faces'][0]['attributes']['emotion']), $this->detect_data['faces'][0]['attributes']['emotion'])];
            });

            $show->field('smile', '笑容')->as(function () {
                return $this->detect_data['faces'][0]['attributes']['smile']['value'] >= $this->detect_data['faces'][0]['attributes']['smile']['threshold'] ? '有笑容' : '没有笑容';
            });

            $show->field('beauty', '颜值')->as(function () {
                return max($this->detect_data['faces'][0]['attributes']['beauty']['male_score'], $this->detect_data['faces'][0]['attributes']['beauty']['female_score']);
            });

            $show->field('skinstatus', '面部特征是否健康')->as(function () {
                return $this->detect_data['faces'][0]['attributes']['skinstatus']['health'] >= 60 ? '是' : '否';
            });

            $show->divider();

            $show->field('faceup_result', '上庭判断结果')->as(function () {
                return [
                    'faceup_normal' => '上庭标准', 'faceup_long' => '上庭偏长', 'faceup_short' => '上庭偏短'
                ][$this->facialfeatures_data['result']['three_parts']['one_part']['faceup_result']];
            });

            $show->field('facemid_result', '中庭判断结果')->as(function () {
                return [
                    'facemid_normal' => '中庭标准', 'facemid_long' => '中庭偏长', 'facemid_short' => '中庭偏短'
                ][$this->facialfeatures_data['result']['three_parts']['two_part']['facemid_result']];
            });

            $show->field('facedown_result', '下庭判断结果')->as(function () {
                return [
                    'facedown_normal' => '下庭标准', 'facedown_long' => '下庭偏长', 'facedown_short' => '下庭偏短'
                ][$this->facialfeatures_data['result']['three_parts']['three_part']['facedown_result']];
            });

            $show->field('eyein_result', '内眼角间距判断结果')->as(function () {
                return [
                    'eyein_normal' => '内眼角间距适中', 'eyein_short' => '内眼角间距偏窄', 'eyein_long' => '内眼角间距偏宽'
                ][$this->facialfeatures_data['result']['five_eyes']['three_eye']['eyein_result']];
            });

            $show->field('righteye_empty_result', '五眼右侧判断结果')->as(function () {
                return [
                    'righteye_empty_normal' => '右眼外侧适中', 'righteye_empty_short' => '右眼外侧偏窄', 'righteye_empty_long' => '右眼外侧偏宽'
                ][$this->facialfeatures_data['result']['five_eyes']['one_eye']['righteye_empty_result']];
            });

            $show->field('lefteye_empty_result', '五眼左侧判断结果')->as(function () {
                return [
                    'lefteye_empty_normal' => '左眼外侧适中', 'lefteye_empty_short' => '左外外侧偏窄', 'lefteye_empty_long' => '左眼外侧偏宽'
                ][$this->facialfeatures_data['result']['five_eyes']['five_eye']['lefteye_empty_result']];
            });

            $show->field('golden_triangle', '黄金三角度数')->as(function () {
                return $this->facialfeatures_data['result']['golden_triangle'];
            });

            $show->field('face_type', '脸型判断结果')->as(function () {
                return [
                    'pointed_face' => '瓜子脸', 'oval_face' => '椭圆脸', 'diamond_face' => '菱形脸', 'round_face' => '圆形脸', 'long_face' => '长形脸', 'square_face' => '方形脸', 'normal_face' => '标准脸'
                ][$this->facialfeatures_data['result']['face']['face_type']];
            });

            $show->field('jaw_type', '下巴判断结果')->as(function () {
                return [
                    'flat_jaw' => '圆下巴', 'sharp_jaw' => '尖下巴', 'square_jaw' => '方下巴'
                ][$this->facialfeatures_data['result']['jaw']['jaw_type']];
            });

            $show->field('eyebrow_type', '眉型判断结果')->as(function () {
                $eyebrowType = $this->facialfeatures_data['result']['eyebrow']['eyebrow_type'];

                if (empty($eyebrowType))
                    return '无法判断';

                return [
                    'bushy_eyebrows' => '粗眉', 'eight_eyebrows' => '八字眉', 'raise_eyebrows' => '上挑眉', 'straight_eyebrows' => '一字眉', 'round_eyebrows' => '拱形眉', 'arch_eyebrows' => '柳叶眉', 'thin_eyebrows' => '细眉'
                ][$eyebrowType];
            });

            $show->field('eyes_type', '眼型判断结果')->as(function () {
                return [
                    'round_eyes' => '圆眼', 'thin_eyes' => '细长眼', 'big_eyes' => '大眼', 'small_eyes' => '小眼', 'normal_eyes' => '标准眼'
                ][$this->facialfeatures_data['result']['eyes']['eyes_type']];
            });

            $show->field('nose_type', '鼻翼判断结果')->as(function () {
                return [
                    'normal_nose' => '标准鼻', 'thick_nose' => '宽鼻', 'thin_nose' => '窄鼻'
                ][$this->facialfeatures_data['result']['nose']['nose_type']];
            });

            $show->field('mouth_type', '唇型判断结果')->as(function () {
                return [
                    'thin_lip' => '薄唇', 'thick_lip' => '厚唇', 'smile_lip' => '微笑唇', 'upset_lip' => '态度唇', 'normal_lip' => '标准唇'
                ][$this->facialfeatures_data['result']['mouth']['mouth_type']];
            });

            $show->divider();

            $show->field('eye_pouch', '眼袋检测结果')->as(function () {
                return $this->skinanalyze_data['result']['eye_pouch']['value'] ? '有眼袋' : '无眼袋';
            });

            $show->field('dark_circle', '黑眼圈检测结果')->as(function () {
                return $this->skinanalyze_data['result']['dark_circle']['value'] ? '有黑眼圈' : '无黑眼圈';
            });

            $show->field('forehead_wrinkle', '抬头纹检测结果')->as(function () {
                return $this->skinanalyze_data['result']['forehead_wrinkle']['value'] ? '有抬头纹' : '无抬头纹';
            });

            $show->field('crows_feet', '鱼尾纹检测结果')->as(function () {
                return $this->skinanalyze_data['result']['crows_feet']['value'] ? '有鱼尾纹' : '无鱼尾纹';
            });

            $show->field('eye_finelines', '眼部细纹检测结果')->as(function () {
                return $this->skinanalyze_data['result']['eye_finelines']['value'] ? '有眼部细纹' : '无眼部细纹';
            });

            $show->field('glabella_wrinkle', '眉间纹检测结果')->as(function () {
                return $this->skinanalyze_data['result']['glabella_wrinkle']['value'] ? '有眉间纹' : '无眉间纹';
            });

            $show->field('nasolabial_fold', '法令纹检测结果')->as(function () {
                return $this->skinanalyze_data['result']['nasolabial_fold']['value'] ? '有法令纹' : '无法令纹';
            });

            $show->field('acne', '痘痘检测结果')->as(function () {
                return $this->skinanalyze_data['result']['acne']['value'] ? '有痘痘' : '无痘痘';
            });
        }

        $show->panel()->tools(function (Tools $tools) {
            $tools->disableEdit();
        });

        return $show;
    }

    public function destroy($id)
    {
        try {
            $order = Order::query()->findOrFail($id);

            if ($order->img) {
                $threePartsFiveEyesPath = $this->drawLogic->threePartsFiveEyesSuffix($order->img);
                $faceStructurePath = $this->drawLogic->faceStructureSuffix($order->img);
                $fiveSensePath = $this->drawLogic->fiveSenseSuffix($order->img);

                if (Storage::exists($threePartsFiveEyesPath)) Storage::delete($threePartsFiveEyesPath);
                if (Storage::exists($faceStructurePath)) Storage::delete($faceStructurePath);
                if (Storage::exists($fiveSensePath)) Storage::delete($fiveSensePath);
                if (Storage::exists($order->img)) Storage::delete($order->img);
            }

            $order->delete();

            return response()->json([
                'status' => true,
                'message' => '删除成功',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }



}













