<?php

namespace App\Admin\Actions\Commission;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cashout extends RowAction
{

    public $name = '提现管理';

    public function handle(Model $model, Request $request)
    {
        if ($model->type != 2 || $model->cashout_status != 1)
            return $this->response()->error('操作失败')->refresh();

        DB::beginTransaction();

        $model->cashout_status = $request->cashout_status;
        $model->cashout_remark = (string)$request->cashout_remark;

        if ($model->cashout_status == 3) { //不允许
            $model->user->share_commission += $model->amount;
            $model->user->save();
        }

        $model->save();

        DB::commit();

        return $this->response()->success('操作成功')->refresh();
    }

    public function form()
    {
        $this->select('cashout_status', '是否允许提现')->options([2 => '允许', 3 => '拒绝'])->rules(['required', 'in:2,3'], [
            'required' => '请选择提现操作',
            'in' => '不合法的提现操作',
        ]);

        $this->textarea('cashout_remark', '提现备注');
    }

    public function authorize($user, $model)
    {
        return $user->isAdministrator();
    }



}









