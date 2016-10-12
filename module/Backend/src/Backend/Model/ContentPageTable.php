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


class ContentPageTable 
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
    
    public function getContentPageById($idContentPage)
    {
        $iIdContentPage = (int)$idContentPage;
        $rowset = $this->tableGateway->select(array('idPage' => $iIdContentPage));
        $row = $rowset->current();
        if(!$row) {
            throw new Exception("Could not find row");
        }
        
        return $row;
    }
    
    /**
     * retourne le contenu de la page passée en parametre
     * @param type $idPage
     */
    public function getContentByPageId($idPage)
    {
        $iIdPage = (int)$idPage;
        $rowset = $this->tableGateway->select(array('idPage' => $iIdPage));
        return $rowset;
    }
    
    /**
     *  update le contenu de la page passée en parametre
     * @param type $content
     * @param type $idPage
     * @return type
     */
    public function saveContentPage($content,$idPage)
    {
        $iIdPage= (int)$idPage;
        $where = new \Zend\Db\Sql\Where();
        $where->equalTo('idPage', $iIdPage);
        $rowset = $this->tableGateway->update(array('content'=>$content), $where);
        return $rowset;
    }
}
