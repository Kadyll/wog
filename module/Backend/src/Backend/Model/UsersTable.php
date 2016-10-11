<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonAccueil for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Backend\Model;



use Zend\Db\TableGateway\TableGateway;


class UsersTable 
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function getUserById($idUser)
    {
        $iIdUser = (int)$idUser;
        $rowset = $this->tableGateway->select(array('iduser' => $iIdUser));
        $row = $rowset->current();
        if(!$row) {
            throw new Exception("Could not find row");
        }
        
        return $row;
    }
    
    public function isUser($pseudo,$password)
    {
        $sPseudo = $pseudo;
        $sPassword = $password;
        $where = new \Zend\Db\Sql\Where();
        $where->equalTo('UserName', $sPseudo);
        $where->AND->equalTo('Password', $password);
        
        $rowset = $this->tableGateway->select($where);
        
        $row = $rowset->current();
        if(!$row) {
            return false;
        }
        
        return true;
    }
}
