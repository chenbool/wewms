<?php

namespace app\admin\model;

use think\Model;

class Indepot extends Model
{

    // 获取供应商
    public function supplier()
    {
        return $this->hasOne('Supplier','id','supplier')->selfRelation();
    }

    public function customer()
    {
        return $this->hasOne('Customer','id','customer')->selfRelation();
    }    

    public function list()
    {
        return $this->hasMany('IndepotMain','sid')->selfRelation();
    }


}
