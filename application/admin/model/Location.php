<?php

namespace app\admin\model;

use think\Model;

class Location extends Model
{
    public function depot(){
        return $this->belongsTo('Depot','pid','id');
    }

}
