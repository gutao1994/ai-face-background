<?php

namespace App\Admin\Controllers;

use App\Models\ShareCommissionLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ShareCommissionLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ShareCommissionLog';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ShareCommissionLog());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('type', __('Type'));
        $grid->column('lower_user_id', __('Lower user id'));
        $grid->column('lower_user_nickname', __('Lower user nickname'));
        $grid->column('lower_order_id', __('Lower order id'));
        $grid->column('amount', __('Amount'));
        $grid->column('cashout_status', __('Cashout status'));
        $grid->column('cashout_remark', __('Cashout remark'));
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
        $show = new Show(ShareCommissionLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('type', __('Type'));
        $show->field('lower_user_id', __('Lower user id'));
        $show->field('lower_user_nickname', __('Lower user nickname'));
        $show->field('lower_order_id', __('Lower order id'));
        $show->field('amount', __('Amount'));
        $show->field('cashout_status', __('Cashout status'));
        $show->field('cashout_remark', __('Cashout remark'));
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
        $form = new Form(new ShareCommissionLog());

        $form->number('user_id', __('User id'));
        $form->switch('type', __('Type'));
        $form->number('lower_user_id', __('Lower user id'));
        $form->text('lower_user_nickname', __('Lower user nickname'));
        $form->number('lower_order_id', __('Lower order id'));
        $form->number('amount', __('Amount'));
        $form->switch('cashout_status', __('Cashout status'));
        $form->textarea('cashout_remark', __('Cashout remark'));

        return $form;
    }
}
