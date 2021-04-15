<?php

namespace App\Admin\Actions\User;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use App\Models\WxUser;

class ShareSetting extends Action
{

    public $name = '分享返现配置';

    protected $selector = '.share-setting';

    protected $row;

    public function __construct($row = null)
    {
        $this->row = $row;

        parent::__construct();
    }

    public function handle(Request $request)
    {
        $user = WxUser::query()->findOrFail($request->id);

        if (empty($user))
            return $this->response()->error('用户不存在');

        $sharePermission = $request->share_permission;
        $sharePerPrice = $request->share_per_price;

        if (!in_array($sharePermission, [0, 1]))
            return $this->response()->error('不合法的返现权限');

        if (!is_numeric($sharePerPrice) || !($sharePerPrice > 0 && $sharePerPrice < 2))
            return $this->response()->error('不合法的返现金额配置');

        $user->share_permission = $sharePermission;
        $user->share_per_price = $sharePerPrice * 100;
        $user->remark = (string)$request->remark;
        $user->save();

        return $this->response()->success('操作成功')->refresh();
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-info share-setting" style="margin-right: 10px;">分享返现配置</a>
HTML;
    }

    public function form()
    {
        if ($this->row) {

            $this->hidden('id')->value($this->row->id);

            $this->select('share_permission', '分享返现权限')->options([
                0 => '没有', 1=> '有'
            ])->value($this->row->share_permission);

            $this->text('share_per_price', '每单返现金额(元)')->value($this->row->share_per_price / 100);

            $this->textarea('remark', '备注')->value($this->row->remark);
        }
    }

    public function authorize($user, $model)
    {
        return $user->isAdministrator();
    }



}








