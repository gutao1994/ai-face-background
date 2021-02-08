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
        $grid = new Grid(new Order());

        $grid->column('id', 'Id')->sortable();
        $grid->column('no', '订单号');
        $grid->column('user.nickname', '订单用户');
        $grid->column('shareUser.nickname', '分享者用户');
        $grid->column('amount', '金额')->money();
        $grid->column('img', '照片')->image('', 50, 40);
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
        });

        return $grid;
    }

    protected function detail($id)
    {
        $order = Order::query()->findOrFail($id);
        $show = new Show($order);

        $show->field('id', 'Id');
        $show->field('no', '订单号');
        $show->field('user.nickname', '订单用户');
        $show->field('shareUser.nickname', '分享者用户');
        $show->field('amount', '金额')->money();
        $show->field('status', '订单状态')->using($this->status);
        $show->field('img', '照片')->image();

        if ($order->img && $order->status == 60) {
            $that = $this;

            $show->field('img-three-parts-five-eyes', '三庭五眼照片')->as(function () use ($that) {
                return $that->fileService->genOssUrl($that->drawLogic->threePartsFiveEyesSuffix($this->img));
            })->image();

            $show->field('img-face-structure', '脸部结构照片')->as(function () use ($that) {
                return $that->fileService->genOssUrl($that->drawLogic->faceStructureSuffix($this->img));
            })->image();

            $show->field('img-five-sense', '五官照片')->as(function () use ($that) {
                return $that->fileService->genOssUrl($that->drawLogic->fiveSenseSuffix($this->img));
            })->image();
        }

        $show->field('api_error_count', 'API调用错误次数');
        $show->field('face_result', '面相分析结果');
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '最近更新时间');
        $show->field('skinanalyze_data', '皮肤分析API的数据')->json();
        $show->field('facialfeatures_data', '面部特征分析API的数据')->json();
        $show->field('detect_data', 'Detect API的数据')->json();

        $show->panel()->tools(function (Tools $tools) {
            $tools->disableEdit();
            $tools->disableDelete();
        });

        return $show;
    }



}













