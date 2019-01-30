<?php
namespace app\admin\controller;

class Depot extends Base
{
    public function getLocation(){
        return $this->object->getLocation();
    }
}
