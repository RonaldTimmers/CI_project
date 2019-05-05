<?php

namespace CI;

/*
 *  Needed for the function: connect_searchd
 */
use Foolz\SphinxQL\Connection;

class Db {
    
    public $thuis;
    public $linode;
    public $wp;
    public $searchd;
    
    
    public function connect_thuis() {
        global $baseURL;
        
        if ($baseURL == 'http://test.bimmith.com/') {
                $host = 'localhost';
        } else {
                $host = '87.213.252.132';
        }

        $this->thuis = new \PDO('mysql:host='. $host .';dbname=local_db;charset=utf8', 'root', 'debaas');
        $this->thuis->exec("set names utf8");
        $this->thuis->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
    }
		
               
    public function connect_linode() {
        global $baseURL;
        
        if ( $baseURL == 'http://test.bimmith.com/' ) {
                $host = '23.239.9.21';
                $user = '';
                $pw = '';
        } else {
                $host = 'localhost';
                $user = '';
                $pw = '';
        }

        try {        
            $this->linode = new \PDO('mysql:host='. $host .';dbname=CI;charset=utf8', $user, $pw);
            $this->linode->exec("set names utf8");
            $this->linode->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        catch(PDOException $e) 
        {
            echo $e->getMessage();
        }
    }
				
				
    public function connect_wp() {
        global $baseURL;
        
        if ($baseURL == 'http://test.bimmith.com/') {
                $host = '23.239.9.21';
                $user = '';
                $pw = '';
        } else {
                $host = 'localhost';
                $user = '';
                $pw = '';
        }
					
        try 
        {        
                $this->wp = new \PDO('mysql:host='. $host .';dbname=ddz0r_wrdp1;charset=utf8', $user, $pw);
                $this->wp->exec("set names utf8");
                $this->wp->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        catch(PDOException $e) 
        {
                echo $e->getMessage();
        }
    }
    
    public static function connect_searchd () {
        
        
        if (BASE_URL == 'http://test.bimmith.com/') {
            $host = '23.239.9.21';
        } else {
            $host = 'localhost';
        }
        
        try 
        {        
            $searchd = new Connection();
            $searchd->setParams(array('host' => $host, 'port' => 9306));
            
            return $searchd;
        }

        catch(PDOException $e) 
        {
            echo $e->getMessage();
        }  
           
    }
                
		
		
    public function close_thuis() {
        $this->thuis = null;
        unset($this->thuis);
    }

    public function close_linode() {
        $this->linode = null;
        unset($this->linode);
    }

    public function close_wp() {
        $this->wp = null;
        unset($this->wp);
    }	
    
    public function close_searchd() {
        $this->searchd = null;
        unset($this->searchd);
    }
}

?>