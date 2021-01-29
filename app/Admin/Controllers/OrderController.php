<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{

    protected $title = '订单管理';

    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->column('id', __('Id'));
        $grid->column('no', __('No'));
        $grid->column('user_id', __('User id'));
        $grid->column('share_user_id', __('Share user id'));
        $grid->column('amount', __('Amount'));
        $grid->column('img', __('Img'));
        $grid->column('facialfeatures_data', __('Facialfeatures data'));
        $grid->column('skinanalyze_data', __('Skinanalyze data'));
        $grid->column('detect_data', __('Detect data'));
        $grid->column('api_error_count', __('Api error count'));
        $grid->column('face_result', __('Face result'));
        $grid->column('status', __('Status'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('no', __('No'));
        $show->field('user_id', __('User id'));
        $show->field('share_user_id', __('Share user id'));
        $show->field('amount', __('Amount'));
        $show->field('img', __('Img'));
        $show->field('facialfeatures_data', __('Facialfeatures data'));
        $show->field('skinanalyze_data', __('Skinanalyze data'));
        $show->field('detect_data', __('Detect data'));
        $show->field('api_error_count', __('Api error count'));
        $show->field('face_result', __('Face result'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }



}













