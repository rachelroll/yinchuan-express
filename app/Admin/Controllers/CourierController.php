<?php

namespace App\Admin\Controllers;

use App\Courier;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CourierController extends Controller
{
    use HasResourceActions;

    private $header = '投票管理-';

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header($this->header.'Index')
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
            ->header($this->header.'Detail')
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
            ->header($this->header.'Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header($this->header.'Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Courier);
        $grid->id('Id')->sortable();
        $grid->name('参加人员姓名');
        $grid->company('所属单位');
        $grid->mobile('联系方式');
        $grid->years('工龄');
        $grid->recommendation('推荐单位');

        $grid->created_at('报名时间')->sortable();
        $grid->column('投票记录')->display(function () {
            $url = url("/votes/{$this->id}");
            return "<a class=\"btn btn-success btn-sm\" href={$url} target='_blank'>投票记录</a>";
        });

        // 设置text、color、和存储值
        $states = [
            'on'  => ['value' => 1, 'text' => '通过', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '不通过', 'color' => 'default'],
        ];
        $grid->status('状态')->switch($states)->sortAble();

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('mobile','联系方式');
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
        $show = new Show(Courier::findOrFail($id));

        $show->name('参加人员姓名');
        $show->sex('性别');
        $show->race('民族');
        $show->birth('出生日期');
        $show->political_grade('政治面貌');
        $show->title('职称');
        $show->recommendation('推荐单位');
        $show->mobile('联系方式');
        $show->company('所属单位');
        $show->years('工龄');
        $show->photos('照片')->setEscape(false)->as(function ($items)  {
            $items = json_decode($items,1);
            return collect($items)->filter()->map(function($item) {
                return '<a href="'.'http://' .env('CDN_DOMAIN').'/'.$item.'" > <img  style="margin: 0 5px;max-width:200px;max-height:200px" class="img" src="'.'http://' .env('CDN_DOMAIN').'/'.$item .'" /></a>';
            })->implode('&nbsp;');
        });

        $show->video('视频')->as(function ($video) {
            return '<video src="http://'.env('CDN_DOMAIN').'/'.$video.'" controls="controls">您的浏览器不支持 video。</video>';
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Courier);

        $form->text('name', "参加人员姓名");
        $form->radio('sex', '性别')->options([1 => '男', 2 => '女'])->default('1');
        $form->text('race', "民族");
        $form->date('birth', "出生日期")->format('YYYY-MM-DD');
        $form->text('political_grade', "政治面貌");
        $form->text('title', "职称");
        $form->text('recommendation', "推荐单位");
        $form->mobile('mobile', '联系方式')->options(['mask' => '999 9999 9999']);
        $form->text('company', '所属单位');
        $form->number('years', '工龄(未满一年填0)')->max(20);
        $form->multipleImage('photos','照片');
        $form->file('video','视频');
        $states = [
            'on'  => ['status' => 1, 'text' => '是', 'color' => 'success'],
            'off' => ['status' => 0, 'text' => '否', 'color' => 'danger'],
        ];
        $form->switch('status','状态')->states($states);

        $form->saving(function($form) {

        });

        return $form;
    }
}

