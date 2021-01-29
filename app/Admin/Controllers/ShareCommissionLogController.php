<?php

namespace App\Admin\Controllers;

use App\Models\ShareCommissionLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Displayers\Actions;
use Encore\Admin\Grid\Filter;
use App\Admin\Actions\Commission\Cashout;

class ShareCommissionLogController extends AdminController
{

    protected $title = '分享佣金管理';

    protected function grid()
    {
        $grid = new Grid(new ShareCommissionLog());

        $grid->column('id', 'Id')->sortable();
        $grid->column('user.nickname', '分享者');
        $grid->column('type', '记录类型')->using([1 => '获得分享佣金', 2 => '提现分享佣金']);
        $grid->column('lower_user_nickname', '被分享者');
        $grid->column('lower_order_id', '被分享者订单')->display(function ($val) {
            return $val ? '<a href="/admin/order/' . $val . '">查看</a>' : '';
        });
        $grid->column('amount', '金额')->money();
        $grid->column('cashout_status', '提现状态')->display(function ($val) {
            return $val ? [1 => '提现中', 2 => '提现成功', 3 => '提现失败'][$val] : '';
        });
        $grid->column('cashout_remark', '提现备注')->stringMaxLength(8);
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '最近更新时间');

        $grid->disableExport();
        $grid->disableColumnSelector();
        $grid->disableCreateButton();
        $grid->disableRowSelector();

        $grid->actions(function (Actions $actions) {
            $actions->disableEdit();
            $actions->disableDelete();
            $actions->disableView();

            if ($actions->row->type == 2 && $actions->row->cashout_status == 1) //提现类型 且 状态为提现中
                $actions->add(new Cashout());
        });

        $grid->filter(function (Filter $filter) {
            $filter->disableIdFilter();

            $filter->equal('user_id', '分享者Id');
            $filter->equal('type', '记录类型')->select([1 => '获得分享佣金', 2 => '提现分享佣金']);
            $filter->equal('cashout_status', '提现状态')->select([1 => '提现中', 2 => '提现成功', 3 => '提现失败']);
        });

        return $grid;
    }



}














