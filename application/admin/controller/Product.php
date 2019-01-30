<?php
namespace app\admin\controller;

class Product extends Base
{
    public function select(){
        return $this->object->select();
    }
}
