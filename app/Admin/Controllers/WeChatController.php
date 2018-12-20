<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class WeChatController extends Controller
{
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');

        //修改所属行业
        //$app->template_message->setIndustry(13, 14);

        //$app->template_message->addTemplate('I0SnFsWHK3wkTIAmYghRA45BoTLlxcl1hXZqJRpxa3E');
        $app->template_message->send([
            'touser' => 'oi-uR0ktJyvXx8HP5-5DVHF_kUgQ',
            'template_id' => 'I0SnFsWHK3wkTIAmYghRA45BoTLlxcl1hXZqJRpxa3E',
            'data' => [
                'phone'   => '18513183115',
                'type'    => '延迟派送',
                'time'    => '2018年11月25日',
                'content' => '太晚了'
                ],
            ]);
    }

}
