<?php 

namespace Helper\Traits;

trait Db
{
    public function db()
    {
        return \Helper\Db::getInstance();
    }
}