<?php

namespace app\admin\model;

use think\Model;

class Product extends Model
{
    // 获取分类
    public function cate()
    {
        return $this->hasOne('Cate','id','cate')->selfRelation();
    }

    // 获取供应商
    public function supplier()
    {
        return $this->hasOne('Supplier','id','supplier')->selfRelation();
    }

    public function customer()
    {
        return $this->hasOne('Customer','id','customer')->selfRelation();
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
