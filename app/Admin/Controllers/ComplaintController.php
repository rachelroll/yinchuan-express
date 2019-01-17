<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\CheckRow;
use App\Company;
use App\Complaint;
use App\User;
use EasyWeChatComposer\EasyWeChat;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{
    use HasResourceActions;

    private $header = '投诉管理-';
    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header($this->header . 'Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header($this->header . 'Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header($this->header . 'Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Complaint());

        $grid->company('投诉公司');
        $grid->track_no('运单号');
        $grid->mobil('联系方式');
        $grid->created_at('投诉时间')->sortable();
        $grid->status('处理状态')->display(function ($status) {
            switch ($status) {
                case Complaint::STATUS_UNTREATED:
                    return Complaint::STATUS[0];
                case Complaint::STATUS_PROCESSING:
                    return Complaint::STATUS[1];
                case Complaint::STATUS_FINISHED:
                    return Complaint::STATUS[2];
                case Complaint::STATUS_CLOSED:
                    return Complaint::STATUS[3];
            }
        })->sortable();
        $grid->process('处理进度')->display(function($process) {
            if ($process < 3) {
                return '<a href="'.route('complaint.change',[
                        'id'=>$this->id,
                        'status'=>$this->status,
                    ]).'"><butten class="btn btn-info btn-sm">'. Complaint::PROCESS[$process] .'</butten></a>';
            }
        });

        $grid->actions(function ($actions) {
            // 添加操作
            $actions->append(new CheckRow($actions->getKey(), '选择处理'));
        });

        //禁用增加, 删除, 编辑按钮
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });
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
        $show = new Show(Complaint::findOrFail($id));

        $show->company('投诉公司');
        $show->track_no('运单号');
        $show->name('投诉人');
        $show->mobil('联系方式');
        $show->created_at('投诉时间');
        $show->status('处理状态')->as(function ($status) {
            switch ($status) {
                case Complaint::STATUS_UNTREATED:
                    return Complaint::STATUS[0];
                case Complaint::STATUS_PROCESSING:
                    return Complaint::STATUS[1];
                case Complaint::STATUS_FINISHED:
                    return Complaint::STATUS[2];
                case Complaint::STATUS_CLOSED:
                    return Complaint::STATUS[3];
            }
        });
        //$show->solution('处理进度');
        $show->content('投诉内容');
        $show->photos('照片')->setEscape(false)->as(function ($items)  {
            return collect($items)->filter()->map(function($item) {
                return '<a target="_blank" href="'.env('CDN_DOMAIN').'/'.$item.'" > <img  style="margin: 0 5px;max-width:200px;max-height:200px" class="img" src="'.env('CDN_DOMAIN').'/'.$item .'" /></a>';
            })->implode('&nbsp;');
        });

        $show->video('视频')->setEscape(false)->as(function ($video) {
            if ($video) {
                return '<video width="400" src="'.env('CDN_DOMAIN').'/'.$video.'" controls="controls">您的浏览器不支持 video。</video>';
            }
            return '没有相关视频';
        });

        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableDelete();
            });;

        return $show;
    }

    public function change()
    {
        $id = request('id');
        $status = request('status');
        $complaint = Complaint::find($id);
        $complaint->status = (int)$status + 1;

        $company = $complaint->company;
        $company_model = Company::where('company_name',$company)->first();
        if (!$company_model) {
            admin_toastr('没有找到相关企业,请在左侧企业管理中创建企业', 'error');
            return back();
        }

        if (!$openid = $company_model->open_id) {
            admin_toastr('请先绑定企业负责人微信ID', 'error');
            return back();
        }

        $type = $complaint->type;
        $type = Complaint::TYPE[ $type ];
        //todo
        //if ($status == 0) {
        //    $this->handleComplaintNotice($openid, $complaint->mobil, $type, $complaint->created_at, $complaint->content);
        //} elseif ($status == 2) {
        //    $this->closeComplaintNotice($openid, $type, $complaint->content, $complaint->created_at);
        //}
        if ($complaint->status <= 3) {
            $complaint->save();
        }
        admin_toastr('处理成功', 'success');
        return back();
    }

   private function handleComplaintNotice($openid, $phone, $type, $time, $content)
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志
        $app = app('wechat.official_account');
        Log::info('1');
        //$app->template_message->setIndustry(13, 14);
        Log::info('2');
        $app->template_message->send([
            'touser' => $openid,
            'template_id' => 'I0SnFsWHK3wkTIAmYghRA45BoTLlxcl1hXZqJRpxa3E',
            'data' => [
                'phone'   => $phone,
                'type'    => $type,
                'time'    => $time,
                'content' => $content,
            ],
        ]);
        Log::info('3');
    }

    private function closeComplaintNotice($openid, $type, $content, $time)
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');

        //$app->template_message->setIndustry(13, 14);

        $app->template_message->send([
            'touser' => $openid,
            'template_id' => 'y1-rpC1einBSaGStbMVJPHiuckNX-POwom9-hmURkQY',
            'data' => [
                'type' => $type, //类型
                'content' => $content, //内容
                'time' => $time, //时间
            ],
        ]);
    }

}
