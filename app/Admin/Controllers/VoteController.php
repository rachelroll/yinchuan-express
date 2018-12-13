<?php

namespace App\Admin\Controllers;

use App\Vote;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoteController extends Controller
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
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Vote());

        $grid->id('Id')->sortable();
        $grid->uid('快递员ID')->sortable();
        $grid->phone('投票人手机号');

        $grid->created_at('Created at')->sortable();

        // 禁用编辑删除按钮
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
        });

        //禁用创建按钮
        $grid->disableCreateButton();

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
        $show = new Show(Vote::findOrFail($id));

        $show->uid('快递员ID')->sortable();
        $show->phone('投票人手机号');
        $show->openId('openId');
        $show->avatar('头像')->image();
        $show->nickName('昵称');

        $show->created_at('Created at')->sortable();
        return $show;
    }
}
