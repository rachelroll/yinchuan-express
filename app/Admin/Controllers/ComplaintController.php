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

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
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
            ->header('Detail')
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
            ->header('Edit')
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
        $grid->created_at('投诉时间');
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
        });
        $grid->process('选择处理');

        $grid->actions(function ($actions) {
            // 添加操作
            $actions->append(new CheckRow($actions->getKey(), '选择处理'));
        });

        //禁用增加, 删除, 编辑按钮
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
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
        $show->mobile('联系方式');
        $show->created_at('投诉时间');
        $show->status('处理状态');
        $show->solution('处理方案');
        $show->content('投诉内容');
        $show->photos('照片')->as(function ($items) {
            $items = json_decode($items);
            if (is_array($items)) {
                foreach ($items as $item) {
                    //$item = env('CDN_DOMAIN').'/'.$item;
                    $item = 'http://jkwedu-new.oss-cn-beijing.aliyuncs.com/'.$item;
                    echo  "<img src=\'$item\' class=\'img'\ />";
                }
            }
            //return "<img src='$items' class='img' />";
        });
        $show->video('视频')->file();

        $show->created_at('Created at')->sortable();

        return $show;
    }
}
