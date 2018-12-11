<?php

namespace App\Admin\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserController extends Controller
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
        $grid = new Grid(new User);
        $grid->id('Id')->sortable();
        $grid->email('邮箱');
        $grid->mobile('手机号');
        $grid->nick_name('昵称');
        $grid->avatar('头像')->image('',50);

        $grid->email_verified('邮箱验证');
        $grid->login_time('登录时间');
        $grid->login_ip('登录IP');
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
        $show = new Show(User::findOrFail($id));

        $show->id('Id');
        $show->email('邮箱');
        $show->mobile('手机号');
        $show->password('密码');
        $show->name('姓名');
        $show->nick_name('昵称');
        $show->wechat_name('微信昵称');
        $show->avatar('头像');
        $show->email_verified('邮箱验证');
        $show->login_time('登录时间');
        $show->login_ip('登录IP');
        $show->created_ip('创建IP');
        $show->invite_code('邀请码');
        $show->from_user_id('邀请人');
        $show->register_type('注册来源');
        $show->register_way('注册设备来源');
        $show->uuid('Uuid');
        $show->uuid_type('uuid类型');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->email('email', 'Email');
        $form->mobile('mobile', 'Mobile');
        $form->text('nick_name', 'Nick name');
        $form->image('avatar', 'Avatar');

        return $form;
    }
}
