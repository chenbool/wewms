<?php

namespace app\admin\model;

use think\Model;

class Purchase extends Model
{
    public function lists()
    {
        return $this->hasMany('PurchaseMain','sid')->selfRelation();
    }
}
