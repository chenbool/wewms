<?php

namespace app\admin\model;

use think\Model;

class AdminNode extends Model
{
    // 获取父级
    public function parent()
    {
        return $this->hasOne('AdminNode','id','pid')->selfRelation();
    }
}
