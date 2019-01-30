<?php

namespace app\admin\model;

use think\Model;

class Depot extends Model
{
    public function location()
    {
        return $this->hasMany('Location','id')->selfRelation();
    }
}
