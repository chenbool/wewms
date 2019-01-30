<?php

namespace app\admin\model;

use think\Model;

class SaleMain extends Model
{
    //
    public function product()
    {
        return $this->hasOne('Product','id','pid')->selfRelation();
    }

}
