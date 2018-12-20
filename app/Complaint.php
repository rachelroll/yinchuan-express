<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $appends = [
        'process',
    ];

    const STATUS_UNTREATED = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_FINISHED = 2;
    const STATUS_CLOSED = 3;

    const STATUS = [
        self::STATUS_UNTREATED => '未处理',
        self::STATUS_PROCESSING => '处理中',
        self::STATUS_FINISHED => '已处理',
        self::STATUS_CLOSED => '投诉完成',
    ];

    const ACTION_TOCOMPANY = 0;
    const ACTION_FINISHED = 1;
    const ACTION_CLOSED = 2;

    const PROCESS = [
        self::ACTION_TOCOMPANY => '转交企业',
        self::ACTION_FINISHED => '已处理',
        self::ACTION_CLOSED => '投诉完成',

    ];

    const DELAY = 'delay';
    const BROKEN = 'broken';
    const LOST = 'lost';
    const CHANGED = 'changed';
    const SERVICE = 'service';
    const OTHERS = 'others';


    const TYPE = [
        self::DELAY => '邮件延误投诉',
        self::BROKEN => '邮件损毁投诉',
        self::LOST => '邮件丢失投诉',
        self::CHANGED => '邮件内件不符投诉',
        self::SERVICE => '邮件投递服务投诉',
        self::OTHERS => '其他投诉类'
    ];

    public function getProcessAttribute()
    {
        return $this->status;
    }

}
