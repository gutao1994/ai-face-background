<?php

namespace App\Admin\Actions\User;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use App\Models\WxUser;

class SharePermission extends Action
{

    protected $selector = '.share_permission';

    protected $row;

    public function __construct($row = null)
    {
        $this->row = $row;

        parent::__construct();
    }

    public function handle(Request $request)
    {
        $user = WxUser::query()->findOrFail($request->id);

        if ($user->share_permission == $request->share_permission)
            return $this->response()->error('请勿重复操作')->refresh();

        $user->share_permission = $request->share_permission;
        $user->remark = (string)$request->remark;
        $user->save();

        return $this->response()->success('操作成功')->refresh();
    }

    public function html()
    {
        $name = $this->row->share_permission ? '禁用分享返现权限' : '开启分享返现权限';

        return <<<HTML
        <a class="btn btn-sm btn-warning share_permission" style="margin-right: 10px;">
            {$name}
        </a>
HTML;
    }

    public function form()
    {
        if ($this->row) {
            $this->hidden('id')->value($this->row->id);
            $this->hidden('share_permission')->value($this->row->share_permission ? 0 : 1);
            $this->textarea('remark', '备注')->value($this->row->remark);
        }
    }



}








