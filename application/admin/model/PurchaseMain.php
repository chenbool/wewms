<?php

namespace app\admin\model;

use think\Model;

class PurchaseMain extends Model
{
    //
    public function product()
    {
        return $this->hasOne('Product','id','pid')->selfRelation();
    }

    public function brand()
    {
        return $this->hasOne('Brand','id','brand')->selfRelation();
    }

    public function unit()
    {
        return $this->hasOne('Unit','id','unit')->selfRelation();
    }

    public function color()
    {
        return $this->hasOne('Color','id','color')->selfRelation();
    }

}
