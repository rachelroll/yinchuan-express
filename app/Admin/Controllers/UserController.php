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
        $grid->avatar('头像');
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
        $show->email('Email');
        $show->mobile('Mobile');
        $show->password('Password');
        $show->nick_name('Nick name');
        $show->title('Title');
        $show->avatar('Avatar');
        $show->point('Point');
        $show->coin('Coin');
        $show->email_verified('Email verified');
        $show->login_time('Login time');
        $show->login_ip('Login ip');
        $show->new_message_num('New message num');
        $show->new_notification_num('New notification num');
        $show->created_ip('Created ip');
        $show->invite_code('Invite code');
        $show->register_type('Register type');
        $show->register_way('Register way');
        $show->uuid('Uuid');
        $show->uuid_type('Uuid type');
        $show->remember_token('Remember token');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

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
        $form->password('password', 'Password');
        $form->text('nick_name', 'Nick name');
        $form->text('title', 'Title');
        $form->image('avatar', 'Avatar');
        $form->text('point', 'Point');
        $form->text('coin', 'Coin');
        $form->text('email_verified', 'Email verified');
        $form->datetime('login_time', 'Login time')->default(date('Y-m-d H:i:s'));
        $form->number('login_ip', 'Login ip');
        $form->text('new_message_num', 'New message num');
        $form->number('new_notification_num', 'New notification num');
        $form->text('created_ip', 'Created ip');
        $form->text('invite_code', 'Invite code');
        $form->switch('register_type', 'Register type');
        $form->switch('register_way', 'Register way');
        $form->text('uuid', 'Uuid');
        $form->text('uuid_type', 'Uuid type');
        $form->text('remember_token', 'Remember token');

        return $form;
    }
}
