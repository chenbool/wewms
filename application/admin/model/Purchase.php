<?php

namespace app\admin\model;

use think\Model;

class Purchase extends Model
{
    public function list()
    {
        return $this->hasMany('PurchaseMain','sid')->selfRelation();
    }
}
