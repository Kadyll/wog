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

class ContentPageTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getContentPageById($idPage) {
        $iIdPage = (int) $idPage;
        $rowset = $this->tableGateway->select(array('idPage' => $iIdPage));
        $row = $rowset->current();
        return $row;
    }

    /**
     * retourne l'article pour l'id passé en parametre
     * @param type $idContentPage
     * @return type
     * @throws Exception
     */
    public function getArticle($idContentPage)
    {
        $iIdContentPage = (int)$idContentPage;
        $rowset = $this->tableGateway->select(array('idContentPage' => $iIdContentPage));
        

        return $rowset->current();
    }
    
    /**
     * retourne le contenu de la page passée en parametre
     * @param type $idPage
     */
    public function getContentByPageId($idPage) {
        $iIdPage = (int) $idPage;
        $rowset = $this->tableGateway->select(array('idPage' => $iIdPage));
        return $rowset;
    }

    /**
     * recupere la liste des articles
     * @return type
     */
    public function getListeArticles()
    {
        $rowset = $this->tableGateway->select(array('type'=> 'article'));
        return $rowset;
    }
    
    /**
     *  update le contenu de la page passée en parametre
     * @param type $content
     * @param type $idPage
     * @return type
     */
    public function saveContentPage($content, $idPage) {
        $iIdPage = (int) $idPage;
        $where = new \Zend\Db\Sql\Where();
        $where->equalTo('idPage', $iIdPage);
        $rowset = $this->tableGateway->update(array('content' => $content), $where);
        return $rowset;
    }

    /**
     * enregistre l'article passé en parametre
     * @param array $article
     * @return type
     */
    public function saveArticle(array $article) {
        $query = $this->tableGateway->select(array('idContentPage'=>$article['idContentPage']));
        $row = $query->current();
        if($row){
            $query = $this->updateArticle($article);
        }else{
            $article['idContentPage'] = null;
            
            $query = $this->tableGateway->insert($article);
        }
            
        return $query;
    }

    /**
     * supprime l'article pour l'id passé en parametre
     * @param type $idContentPage
     * @return type
     */
    public function deleteArticle($idContentPage) {
        $iIdContentPage = (int) $idContentPage;
        $where = new \Zend\Db\Sql\Where();
        $where->equalTo('idContentPage', $iIdContentPage);
        $query = $this->tableGateway->delete($where);

        return $query;
    }

    /**
     * 
     * @param array $article
     * @return type
     */
    public function updateArticle(array $article) {
        $iIdContentPage = (int)$article['idContentPage'];
        $where = new \Zend\Db\Sql\Where();
        $where->equalTo('idContentPage', $iIdContentPage);
        
        $query = $this->tableGateway->update($article, $where);
        
        return $query;
    }

}
