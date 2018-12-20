<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeChatController extends Controller
{
    public function serve()
    {
        dd(37937);
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');

        $app->template_message->setIndustry(13, 14);

        $app->template_message->send([
            'touser' => 'oi-uR0ktJyvXx8HP5-5DVHF_kUgQ',
            'template_id' => 'oeR93ZHiU_fMd21zH9l8XFsa9SyAIQmyajWp-Hw7uxY',
            'url' => 'https://easywechat.org',
            'data' => [
                'key1' =>    'hao',
                'key2' => 'hao',
                'key3' => 'hao',
                ],
            ]);
    }

}
