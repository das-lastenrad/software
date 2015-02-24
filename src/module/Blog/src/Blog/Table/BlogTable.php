<?php
/**
 * ZF2 Buch Kapitel 19
 * 
 * Das Buch "Zend Framework 2 - Das Praxisbuch"
 * von Ralf Eggert ist im Galileo-Computing Verlag erschienen. 
 * ISBN 978-3-8362-2610-3
 * 
 * @package    Blog
 * @author     Ralf Eggert <r.eggert@travello.de>
 * @copyright  Alle Listings sind urheberrechtlich geschützt!
 * @link       http://www.zendframeworkbuch.de/ und http://www.galileocomputing.de/3460
 */

/**
 * namespace definition and usage
 */
namespace Blog\Table;

use Blog\Entity\BlogEntityInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

/**
 * Blog table
 * 
 * Handles the blogs table for the Blog module 
 * 
 * @package    Blog
 */
class BlogTable extends TableGateway implements BlogTableInterface
{
    /**
     * Constructor
     * 
     * @param Adapter $adapter database adapter
     */
    public function __construct(Adapter $adapter, BlogEntityInterface $entity)
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype($entity);
        
        parent::__construct('blogs', $adapter, null, $resultSet);
    }
    
    /**
     * Fetch single blog by url
     * 
     * @param varchar $url url address of blog
     * @return BlogEntityInterface
     */
    public function fetchSingleByUrl($url)
    {
        $select = $this->getSql()->select();
        $select->where->equalTo('url', $url);
        
        return $this->selectWith($select)->current();
    }
    
    /**
     * Fetch single blog by id
     * 
     * @param integer $id id ofblog
     * @return BlogEntityInterface
     */
    public function fetchSingleById($id)
    {
        $select = $this->getSql()->select();
        $select->where->equalTo('id', $id);
        
        return $this->selectWith($select)->current();
    }
}
