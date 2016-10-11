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


class RolesTable 
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
    
    public function getRole($idRole)
    {
        $iIdRole = (int)$idRole;
        $rowset = $this->tableGateway->select(array('iduser' => $iIdRole));
        $row = $rowset->current();
        if(!$row) {
            throw new Exception("Could not find row");
        }
        return $row;
    }
}
