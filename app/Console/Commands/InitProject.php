<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitProject extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '填充基础数据';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        switch ($name) {
            case 'data':
                if ($this->confirm('确定要填充你的基础数据吗?原有数据将被覆盖')) {
                    $this->call('db:seed');
                    $this->initMenu();
                }
                break;
            case 'menu':
                $this->initMenu();
                break;
            case 'project':

                if ($this->confirm('确定要初始化你的laravel-admin组件吗?')) {
                    $this->initProject();
                    $this->initMenu();
                    $this->call('db:seed');
                }
                break;
            default:
                break;

        }
    }

    private function initMenu()
    {
        $this->info('正在初始化菜单...');
        \Cache::forget('admin_menu');
        $this->menuItems();
        $this->info('菜单初始化完毕');
    }

    /**
     * 初始化菜单
     */
    private function menuItems()
    {
        $menu = config('menu');

        \DB::table('admin_menu')->truncate();
        \DB::table('admin_menu')->insert($menu);
    }

    /**
     * 初始化laravel-admin
     */
    private function initProject()
    {
        $this->info('初始化laravel-admin...');
        $this->call('vendor:publish', [
            '--provider' => 'Encore\Admin\AdminServiceProvider',
        ]);
        $this->call('admin:install');
        $this->info('初始化laravel-admin 完毕');
    }
}
