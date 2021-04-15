<?php

namespace App\Admin\Controllers;

use App\Models\WxUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Displayers\Actions;
use App\Admin\Actions\User\ShareSetting;
use Encore\Admin\Show\Tools;

class WxUserController extends AdminController
{

    protected $title = '用户列表';

    protected function grid()
    {
        $grid = new Grid(new WxUser());

        $grid->column('id', 'Id')->sortable();
        $grid->column('nickname', '昵称')->display(function ($val) {
            return "<a href='/admin/wx_users/{$this->id}'>{$val}</a>";
        });
        $grid->column('avatar', '头像')->image('', 50, 40);
        $grid->column('sex', '性别')->using([0 => '未知', 1 => '男', 2 => '女']);
        $grid->column('country', '国家');
        $grid->column('province', '省份');
        $grid->column('city', '城市');
        $grid->column('language', '语言');
        $grid->column('share_permission', '分享返现权限')->using([0 => '没有', 1 => '有']);
        $grid->column('share_per_price', '每单返现金额')->sortable()->money();
        $grid->column('share_commission', '分享佣金')->sortable()->money();
        $grid->column('share_total_commission', '分享总佣金')->sortable()->money();
        $grid->column('share_order_num', '分享订单数')->sortable();
        $grid->column('remark', '备注')->stringMaxLength(8);
        $grid->column('created_at', '创建时间');

        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();

        $grid->filter(function (Filter $filter) {
            $filter->like('nickname', '昵称');
            $filter->equal('share_permission', '分享返现权限')->select([0 => '没有', 1 => '有']);
        });

        $grid->actions(function (Actions $actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });

        return $grid;
    }

    protected function detail($id)
    {
        $user = WxUser::query()->findOrFail($id);
        $show = new Show($user);

        $show->field('id', 'Id');
        $show->field('nickname', '昵称');
        $show->field('avatar', '头像')->image();
        $show->field('sex', '性别')->using([0 => '未知', 1 => '男', 2 => '女']);
        $show->field('country', '国家');
        $show->field('province', '省份');
        $show->field('city', '城市');
        $show->field('language', '语言');
        $show->field('share_permission', '分享返现权限')->using([0 => '没有', 1 => '有']);
        $show->field('share_per_price', '每单返现金额')->money();
        $show->field('share_commission', '分享佣金')->money();
        $show->field('share_total_commission', '分享总佣金')->money();
        $show->field('share_order_num', '分享订单数');
        $show->field('remark', '备注')->unescape()->as(fn($val) => "<pre>{$val}</pre>");
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '最近更新时间');

        $show->panel()->tools(function (Tools $tools) use ($user) {
            $tools->disableEdit();
            $tools->disableDelete();
            $tools->append(new ShareSetting($user));
        });;

        return $show;
    }



}






