<?php

namespace app\admin\model;

use think\Model;

class Sale extends Model
{
    public function lists()
    {
        return $this->hasMany('SaleMain','sid')->selfRelation();
    }
}
