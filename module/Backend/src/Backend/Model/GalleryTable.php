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

class GalleryTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $select = new \Zend\Db\Sql\Select('gallery');
        $select->order('idElement DESC');

        $rowset = $this->tableGateway->selectWith($select);
        return $rowset;
    }

    /**
     * enregistre les informations de l'élement passées en parametre
     * @param array $element
     * @return type
     */
    public function saveElement(array $element) {
        $element['idElement'] = null;
        $query = $this->tableGateway->insert($element);
        
        return $query;
    }

}
