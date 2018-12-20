<?php

namespace App\Admin\Controllers;

use App\Courier;
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
    private $header = '投票记录-';

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        $id = request('courier_id');
        return $content
            ->header($this->header . 'Index')
            ->description('description')
            ->body($this->grid($id));
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
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($id=0)
    {

        $grid = new Grid(new Vote());

        $grid->id('ID')->sortable();
        $grid->courier_id('快递员名称')->display(function($courier_id) {
            return Courier::find($courier_id)->name;
        })->sortable();
        $grid->openId('投票人微信ID');

        $grid->created_at('投票时间')->sortable();

        // 禁用编辑删除按钮
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
        });
        if ($id) {
            $grid->model()->where('courier_id', '=', $id);
        }


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

        $show->uid('快递员名称')->sortable();
        $show->openId('openId');
        $show->avatar('头像')->image();
        $show->nickName('昵称');

        $show->created_at('Created at')->sortable();
        return $show;
    }
}
