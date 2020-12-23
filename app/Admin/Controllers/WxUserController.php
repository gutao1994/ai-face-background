<?php

namespace App\Admin\Controllers;

use App\Models\WxUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WxUserController extends AdminController
{

    protected $title = '用户列表';

    protected function grid()
    {
        $grid = new Grid(new WxUser());

        $grid->column('id', 'Id');
        $grid->column('nickname', '昵称');
        $grid->column('avatar', '头像')->image('', 56, 50);
        $grid->column('sex', '性别');
        $grid->column('country', '国家');
        $grid->column('province', '省份');
        $grid->column('city', '城市');
        $grid->column('language', '语言');
        $grid->column('share_permission', '是否有分享返现权限');
        $grid->column('share_commission', '分享返现佣金');
        $grid->column('share_total_commission', '分享返现总佣金');
        $grid->column('share_order_num', '分享成交订单数');
        $grid->column('created_at', '创建时间');

        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(WxUser::query()->findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('openid', __('Openid'));
        $show->field('nickname', __('Nickname'));
        $show->field('avatar', __('Avatar'));
        $show->field('sex', __('Sex'));
        $show->field('country', __('Country'));
        $show->field('province', __('Province'));
        $show->field('city', __('City'));
        $show->field('language', __('Language'));
        $show->field('share_permission', __('Share permission'));
        $show->field('share_commission', __('Share commission'));
        $show->field('share_total_commission', __('Share total commission'));
        $show->field('share_order_num', __('Share order num'));
        $show->field('remark', __('Remark'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }



}






