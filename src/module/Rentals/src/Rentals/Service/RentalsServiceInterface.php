<?php


/**
 * namespace definition and usage
 */
namespace Rentals\Service;

use Zend\Db\TableGateway\TableGateway;
use Rentals\Entity\RentalsEntityInterface;
use Rentals\Form\RentalsFormInterface;
use Rentals\Table\RentalsTableInterface;
use Rentals\Table\OpeninghoursTableInterface;
use User\Entity\UserEntityInterface;
use Application\View\Helper;
use Zend\Mail\Transport\Smtp as SmtpTransport;

/**
 * Rentals Service interface
 * 
 * @package    Rentals
 */
interface RentalsServiceInterface
{
    /**
     * Constructor
     * 
     * @param RentalsTableInterface $rentalsTable
     */
    public function __construct(RentalsTableInterface $rentalsTable, OpeninghoursTableInterface $openinghoursTable, UserEntityInterface $identity = null, SmtpTransport $transport = null);
    
    /**
     * Get table with triggering the Event-Manager
     * 
     * @param  string $type table type
     * @return TableGateway
     */
    public function getTable($type = 'rentals');

    /**
     * Set table
     * 
     * @param TableGateway $table
     * @param string $type table type
     */
    public function setTable(TableGateway $table, $type = 'rentals');
    
    public function getIdentity();
    public function setIdentity(UserEntityInterface $identity = null);

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
    public function setMessage($message, $type);
    
    /**
     * Get form with triggering the Event-Manager
     * 
     * @param  string $type form type
     * @return RentalsFormInterface
     */
    public function getForm($type = 'create');

    /**
     * Set form
     * 
     * @param RentalsFormInterface $form
     * @param string $type form type
     */
    public function setForm(RentalsFormInterface $form, $type = 'create');

    
    
    /**
     * Fetch single by id
     *
     * @param varchar $id
     * @return RentalsEntityInterface
     */
    public function fetchSingleById($id);
    
    /**
     * Fetch list of rentalss
     *
     * @param integer $page number of page
     * @return Paginator
     */
    public function fetchList($page = 1, $perPage = 4, $params = array());
    
    public function fetchAll();
    
    /**
     * Save a rentals
     *
     * @param array $data input data
     * @param integer $id id of rentals entry
     * @return RentalsEntityInterface
     */
    public function save(array $data, $id = null);
    
    /**
     * Delete existing rentals
     *
     * @param integer $id rentals id
     * @param array $data input data
     * @return RentalsEntityInterface
     */
    public function delete($id);
}
