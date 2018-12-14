<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\CheckRow;
use App\Complaint;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $grid->name('投诉人');
        $grid->mobile('联系方式');
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
        $show->complain_at('投诉时间');
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
        $show->photos('照片')->setEscape(false)->as(function ($items) {
            $items = json_decode($items,1);
            return collect($items)->filter()->map(function($item) {
                return '<a href="'.'http://' .env('CDN_DOMAIN').'/'.$item.'" > <img  style="margin: 0 5px;max-width:200px;max-height:200px" class="img" src="'.'http://' .env('CDN_DOMAIN').'/'.$item .'" /></a>';
            })->implode('&nbsp;');
        });
        $show->video('视频')->setEscape(false)->as(function ($video) {
            return '<video src="http://'.env('CDN_DOMAIN').'/'.$video.'" controls="controls">您的浏览器不支持 video。</video>';
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
        if ($complaint->status <= 3) {
            $complaint->save();
        }
        return back();

    }

}
