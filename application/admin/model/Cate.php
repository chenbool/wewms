<?php

namespace app\admin\model;

use think\Model;

class Cate extends Model
{
    // 获取父级
    public function parent()
    {
        return $this->hasOne('Cate','id','pid')->selfRelation();
    }
}
