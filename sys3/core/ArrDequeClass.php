<?php
/**
 * ArrDeque v1.0.0
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2015-11-25
 */
class ArrDeque extends BaseStatic{}
class ArrDequeClass
{ 
    public $queue = array(); 
    
    /**（尾部）入队  **/ 
    public function push($value)  
    { 
        return array_push($this->queue, $value); 
    } 

    /**（尾部）出队**/ 
    public function pop()  
    { 
        return array_pop($this->queue); 
    } 

    /**（头部）入队**/ 
    public function unshift($value)  
    { 
        return array_unshift($this->queue,$value); 
    } 

    /**（头部）出队**/ 
    public function shift()  
    { 
        return array_shift($this->queue); 
    } 

    /**清空队列**/ 
    public function remove()  
    { 
        unset($this->queue);
    } 

    /**获取列头**/
    public function getFirst()  
    { 
        return reset($this->queue); 
    }
    
    /** 获取列尾 **/
    public function getLast()  
    { 
        return end($this->queue); 
    }

    /** 获取长度 **/
    public function getLength()  
    { 
        return count($this->queue); 
    }          
}