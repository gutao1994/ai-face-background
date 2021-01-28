<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
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

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
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

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());

        $form->text('no', __('No'));
        $form->number('user_id', __('User id'));
        $form->number('share_user_id', __('Share user id'));
        $form->number('amount', __('Amount'));
        $form->image('img', __('Img'));
        $form->textarea('facialfeatures_data', __('Facialfeatures data'));
        $form->textarea('skinanalyze_data', __('Skinanalyze data'));
        $form->textarea('detect_data', __('Detect data'));
        $form->switch('api_error_count', __('Api error count'));
        $form->textarea('face_result', __('Face result'));
        $form->switch('status', __('Status'));

        return $form;
    }
}
