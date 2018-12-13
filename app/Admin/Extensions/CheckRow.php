<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;

class CheckRow
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function script()
    {
        return <<<SCRIPT

$('.grid-check-row').on('click', function () {
    $.ajax({
        type : "POST",
        url : "../api/drawMoney/check",
        dataType : "json",
        data : {
            'complaint_id':$(this).data('id'),
        },
        success : function(test) {
            window.location.reload();
        },
    });
});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a class='btn btn-xs btn-success fa fa-check grid-check-row' data-id='{$this->id}'>选择处理</a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}