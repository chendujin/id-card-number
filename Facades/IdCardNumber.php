<?php
// +----------------------------------------------------------------------
// | 云盾风控审批系统
// +----------------------------------------------------------------------
// | Version: v1.0.0
// +----------------------------------------------------------------------
// | Author: 托马斯 <chendujin@zujibao.net>
// +----------------------------------------------------------------------
// | Date: 2023/6/25
// +----------------------------------------------------------------------
namespace Chendujin\IdCardNumber\Facades;

use Illuminate\Support\Facades\Facade;

class IdCardNumber extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'IdCardNumber';
    }
}