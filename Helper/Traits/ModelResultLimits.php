<?php 

namespace Helper\Traits;

trait ModelResultLimits
{
    private $limit;
    private $offset;
    
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
    
    public function getLimit()
    {
        return $this->limit;
    }
    
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }
    
    public function getOffset()
    {
        return $this->offset;
    }
    
    private function getQueryLimitByReusltLimit()
    {
        return \Helper\Db::getLimitQuery($this->getLimit(), $this->getOffset());
    }
}