<?php

namespace app\admin\model;

use think\Model;

class Sale extends Model
{
    public function list()
    {
        return $this->hasMany('SaleMain','sid')->selfRelation();
    }
}
