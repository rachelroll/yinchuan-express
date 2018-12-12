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
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
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
        $grid->name('姓名');
        $grid->sex('性别');
        $grid->mobile('手机号');
        $grid->company('快递公司');
        // 设置text、color、和存储值
        $states = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
        ];
        $grid->disabled('是否禁用')->switch($states)->sortAble();

        $grid->created_at('Created at')->sortable();

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

        $show->id('Id')->sortable();
        $show->name('姓名');
        $show->sex('性别');
        $show->race('民族');
        $show->birth('出生日期');
        $show->political_grade('政治面貌');
        $show->title('职称');
        $show->recommendation('推荐单位');
        $show->mobile('手机号');
        $show->company('快递公司');
        $show->years('从业年限');
        $show->photos('照片')->display(function ($items) {
            //$items = explode('|', $items);
            foreach ($items as $item) {
                return  '< img src="' .$item. ' ">';
            }
        });
        $show->video('视频');

        $show->created_at('Created at')->sortable();
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

        $form->text('name', "姓名");
        $form->radio('sex', '性别')->options([1 => '男', 2 => '女'])->default('1');
        $form->text('race', "民族");
        $form->date('birth', "出生日期")->format('YYYY-MM-DD');
        $form->text('political_grade', "政治面貌");
        $form->text('title', "职称");
        $form->text('recommendation', "推荐单位");
        $form->mobile('mobile', '手机号')->options(['mask' => '999 9999 9999']);
        $form->text('company', '快递公司');
        $form->number('years', '从业年限(未满一年填0)')->max(20);
        $form->multipleImage('photos','照片');
        $form->file('video','视频');
        $states = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
        ];
        $form->switch('status','启用禁用')->states($states);

        $form->saving(function($form) {

        });

        return $form;
    }
}

