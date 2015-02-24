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
namespace Blog\Service;

use Blog\Form\BlogFormInterface;
use Blog\Table\BlogTableInterface;

/**
 * Blog Service interface
 * 
 * @package    Blog
 */
interface BlogServiceInterface
{
    /**
     * Constructor
     * 
     * @param BlogTableInterface $table
     * @param AuthenticationService $authentication
     */
    public function __construct(BlogTableInterface $table);
    
    /**
     * Get blog table
     * 
     * @return BlogTableInterface
     */
    public function getTable();
    
    /**
     * Set blog table
     * 
     * @param BlogTableInterface $table
     * @return BlogServiceInterface
     */
    public function setTable(BlogTableInterface $table);
    
    /**
     * Get service message
     * 
     * @return array
     */
    public function getMessage();
    
    /**
     * Clear service message
     */
    public function clearMessage();
    
    /**
     * Add service message
     * 
     * @param string new message
     */
    public function setMessage($message);
    
    /**
     * Get form with triggering the Event-Manager
     * 
     * @param  string $type form type
     * @return BlogFormInterface
     */
    public function getForm($type = 'create');

    /**
     * Set form
     * 
     * @param BlogFormInterface $form
     * @param string $type form type
     */
    public function setForm(BlogFormInterface $form, $type = 'create');

    /**
     * Fetch single by url
     *
     * @param varchar $url
     * @return BlogEntityInterface
     */
    public function fetchSingleByUrl($url);
    
    /**
     * Fetch single by id
     *
     * @param varchar $id
     * @return BlogEntityInterface
     */
    public function fetchSingleById($id);
    
    /**
     * Fetch list of blogs
     *
     * @param integer $page number of page
     * @return Paginator
     */
    public function fetchList($page = 1, $perPage = 15);
    
    /**
     * Save a blog
     *
     * @param array $data input data
     * @param integer $id id of blog entry
     * @return BlogEntityInterface
     */
    public function save(array $data, $id = null);
    
    /**
     * Delete existing blog
     *
     * @param integer $id blog id
     * @param array $data input data
     * @return BlogEntityInterface
     */
    public function delete($id);
}
