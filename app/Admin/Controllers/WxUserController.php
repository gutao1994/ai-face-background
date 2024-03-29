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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use EasyWeChat\Kernel\Http\StreamResponse;
use Illuminate\Support\Facades\Log;

class WxUserController extends AdminController
{

    protected $title = '用户列表';

    protected function grid()
    {
        $grid = new Grid(new WxUser());
        $grid->model()->withCount('orders');

        $grid->column('id', 'Id')->sortable();
        $grid->column('nickname', '昵称')->display(fn($val) => "<a href='/admin/wx_users/{$this->id}'>{$val}</a>");
        $grid->column('avatar', '头像')->image('', 50, 40);
        $grid->column('sex', '性别')->using([0 => '未知', 1 => '男', 2 => '女']);
        $grid->column('country', '国家');
        $grid->column('province', '省份');
        $grid->column('city', '城市');
        $grid->column('language', '语言');
        $grid->column('orders_count', '订单数')->display(fn($val) => "<a target='_blank' href='/admin/order?_sort[column]=id&_sort[type]=desc&user_id={$this->id}'>{$val}</a>");
        $grid->column('share_permission', '分享返现权限')->using([0 => '没有', 1 => '有']);
        $grid->column('share_per_price', '每单返现金额')->sortable()->money();
        $grid->column('share_commission', '分享佣金')->sortable()->money();
        $grid->column('share_total_commission', '分享总佣金')->sortable()->money();
        $grid->column('share_order_num', '分享订单数')->sortable()->display(fn($val) => "<a target='_blank' href='/admin/order?_sort[column]=id&_sort[type]=desc&share_user_id={$this->id}'>{$val}</a>");
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
        $user->loadCount('orders');
        $show = new Show($user);

        $show->field('id', 'Id')->unescape()->as(fn($val) => "<span style='margin-right: 10px;'>{$val}</span><a target='_blank' href='/admin/wx_users/{$this->id}/share/mini_program/code/1280'>查看专属分享小程序码</a>");
        $show->field('nickname', '昵称');
        $show->field('avatar', '头像')->zoom();
        $show->field('sex', '性别')->using([0 => '未知', 1 => '男', 2 => '女']);
        $show->field('country', '国家');
        $show->field('province', '省份');
        $show->field('city', '城市');
        $show->field('language', '语言');
        $show->field('orders_count', '订单数')->unescape()->as(fn($val) => "<a target='_blank' href='/admin/order?_sort[column]=id&_sort[type]=desc&user_id={$this->id}'>{$val}</a>");
        $show->field('share_permission', '分享返现权限')->using([0 => '没有', 1 => '有']);
        $show->field('share_per_price', '每单返现金额')->money();
        $show->field('share_commission', '分享佣金')->money();
        $show->field('share_total_commission', '分享总佣金')->money();
        $show->field('share_order_num', '分享订单数')->unescape()->as(fn($val) => "<a target='_blank' href='/admin/order?_sort[column]=id&_sort[type]=desc&share_user_id={$this->id}'>{$val}</a>");
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

    /**
     * 用户的 分享 小程序码
     */
    public function miniProgramCode($userId, $size, Request $request)
    {
        $size = (int)$size;
        $user = WxUser::query()->findOrFail($userId);

        if ($size < 280 || $size > 1280)
            throw new \Exception('不合法的二维码尺寸');

        $dir = 'share' . (app()->isProduction() ? '' : '-test');
        $fileName = "{$user->id}-{$size}.png";
        $fullName = $dir . '/' . $fileName;

        if (!Storage::exists($fullName)) { //不存在分享的小程序码

            Log::debug("开始生成分享的小程序码: $fullName");

            $app = \EasyWeChat::miniProgram();

            $res = $app->app_code->get("pages/index/index?share_user_id={$user->id}", [
                'width' => $size,
            ]);

            if ($res instanceof StreamResponse) {
                Storage::put($fullName, $res->getBodyContents());
            } else {
                dd($res);
            }
        }

        $content = Storage::get($fullName);
        header('Content-type: image/png');
        echo $content;
    }



}






