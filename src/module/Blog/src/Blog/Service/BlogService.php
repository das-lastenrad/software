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

use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Filter\StaticFilter;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Blog\Entity\BlogEntity;
use Blog\Entity\BlogEntityInterface;
use Blog\Form\BlogFormInterface;
use Blog\Table\BlogTableInterface;

/**
 * Blog Service
 * 
 * @package    Blog
 */
class BlogService implements 
    EventManagerAwareInterface, 
    BlogServiceInterface
{
    /**
     * @var EventManagerInterface
     */
    protected $eventManager = null;
    
    /**
     * @var BlogTableInterface
     */
    protected $table = null;
    
    /**
     * @var BlogFormInterface[]
     */
    protected $forms = array();

    /**
     * @var string
     */
    protected $message = null;
    
    /**
     * Constructor
     * 
     * @param BlogTableInterface $table
     * @param AuthenticationService $authentication
     */
    public function __construct(BlogTableInterface $table)
    {
        $this->setTable($table);
    }
    
    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(__CLASS__));
        $this->eventManager = $eventManager;
    }
    
    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
    
    /**
     * Get blog table
     * 
     * @return BlogTableInterface
     */
    public function getTable()
    {
        return $this->table;
    }
    
    /**
     * Set blog table
     * 
     * @param BlogTableInterface $table
     * @return BlogServiceInterface
     */
    public function setTable(BlogTableInterface $table)
    {
        $this->table = $table;
        return $this;
    }
    
    /**
     * Get service message
     * 
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Clear service message
     */
    public function clearMessage()
    {
        $this->message = null;
    }
    
    /**
     * Add service message
     * 
     * @param string new message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
    
    /**
     * Get form with triggering the Event-Manager
     * 
     * @param  string $type form type
     * @return BlogFormInterface
     */
    public function getForm($type = 'create')
    {
        if (!isset($this->forms[$type])) {
            $this->getEventManager()->trigger(
                'set-blog-form', __CLASS__, array('type' => $type)
            );
        }
        
        return $this->forms[$type];
    }

    /**
     * Set form
     * 
     * @param BlogFormInterface $form
     * @param string $type form type
     */
    public function setForm(BlogFormInterface $form, $type = 'create')
    {
        $this->forms[$type] = $form;
    }

    /**
     * Fetch single by url
     *
     * @param varchar $url
     * @return BlogEntityInterface
     */
    public function fetchSingleByUrl($url)
    {
        return $this->getTable()->fetchSingleByUrl($url);
    }
    
    /**
     * Fetch single by id
     *
     * @param varchar $id
     * @return BlogEntityInterface
     */
    public function fetchSingleById($id)
    {
        return $this->getTable()->fetchSingleById($id);
    }
    
    /**
     * Fetch list of blogs
     *
     * @param integer $page number of page
     * @return Paginator
     */
    public function fetchList($page = 1, $perPage = 15)
    {
        // Initialize select
        $select = $this->getTable()->getSql()->select();
        $select->order('cdate DESC');
        
        // Initialize paginator
        $adapter = new DbSelect(
            $select, 
            $this->getTable()->getAdapter(), 
            $this->getTable()->getResultSetPrototype()
        );
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($perPage);
        $paginator->setPageRange(9);
        
        // return paginator
        return $paginator;
    }
    
    /**
     * Save a blog
     *
     * @param array $data input data
     * @param integer $id id of blog entry
     * @return BlogEntityInterface
     */
    public function save(array $data, $id = null)
    {
        // check mode
        $mode = is_null($id) ? 'create' : 'update';
        
        // get blog entity
        if ($mode == 'create') {
            $blog = new BlogEntity();
        } else {
            $blog = $this->fetchSingleById($id);
        }
        
        // get form and set data
        $form = $this->getForm($mode);
        $form->setData($data);
        
        // check for invalid data
        if (!$form->isValid()) {
            $this->setMessage('Bitte Eingaben überprüfen!');
            return false;
        }
        
        // get valid blog entity object
        $blog->exchangeArray($form->getData());
        
        // set values
        if ($mode == 'create') {
            $blog->setCdate(date('Y-m-d H:i:s'));
        }
        $blog->setUrl(StaticFilter::execute($blog->getTitle(), 'StringToUrl'));
        
        // get insert data
        $saveData = $blog->getArrayCopy();
        
        // save blog
        try {
            if ($mode == 'create') {
                $this->getTable()->insert($saveData);
                
                // get last insert value
                $id = $this->getTable()->getLastInsertValue();
            } else {
                $this->getTable()->update($saveData, array('id' => $id));
            }
        } catch (InvalidQueryException $e) {
            $this->setMessage('Blogbeitrag wurde nicht gespeichert!');
            return false;
        }

        // reload blog
        $blog = $this->fetchSingleById($id);
        
        // set success message
        $this->setMessage('Blogbeitrag wurde gespeichert!');
        
        // return blog
        return $blog;
    }
    
    /**
     * Delete existing blog
     *
     * @param integer $id blog id
     * @param array $data input data
     * @return BlogEntityInterface
     */
    public function delete($id)
    {
        // fetch blog entity
        $blog = $this->fetchSingleById($id);
        
        // delete existing blog
        try {
            $result = $this->getTable()->delete(array('id' => $id));
        } catch (InvalidQueryException $e) {
            return false;
        }

        // set success message
        $this->setMessage('Der Blogbeitrag wurde gelöscht!');
        
        // return result
        return true;
    }
}
