<?php

/**
 * Das Lastenrad GRaz
 *
 * www.das-lastenrad.at
 *
 * @package    Rentals
 * @author     Andi Zobl <andreas@zobl.at>
 * @copyright  Copyright Hinweis
 * @link       https://www.das-lastenrad.at/ und https://www.github.com/xxx
 */

/**
 * namespace definition and usage
 */
namespace Rentals\Service;

use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\TableGateway\TableGateway;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Filter\StaticFilter;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Rentals\Entity\RentalsEntityInterface;
use Rentals\Entity\RentalsEntity;
use Rentals\Form\RentalsFormInterface;
use Rentals\Table\RentalsTableInterface;

use Rentals\Entity\OpeninghoursEntityInterface;
use Rentals\Entity\OpeninghoursEntity;
use Rentals\Table\OpeninghoursTableInterface;

use User\Entity\UserEntityInterface;

use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

use Zend\Mime\Mime as Mime;


/**
 * Rentals Service
 * 
 * @package    Rentals
 */
class RentalsService implements EventManagerAwareInterface, RentalsServiceInterface
{
    /**
     * @var EventManagerInterface
     */
    protected $eventManager = null;
    
    /**
     * @var RentalsFormInterface[]
     */
    protected $forms = array();

    /**
     * @var TableGateway[]
     */
    protected $tables = array();

    /**
     * @var string
     */
    protected $message = null;
    
    /**
     * @var UserEntityInterface
     */
    protected $identity = null;
    
    /**
     * @var SmtpTransport
     */
    protected $transport = null;
    
    /**
     * Constructor
     * 
     * @param RentalsTableInterface $rentalsTable
     */
    public function __construct(RentalsTableInterface $rentalsTable, OpeninghoursTableInterface $openinghoursTable, UserEntityInterface $identity = null,  SmtpTransport $transport = null)
    {
        $this->setTable($rentalsTable, 'rentals');
        $this->setTable($openinghoursTable, 'openinghours');
        $this->setIdentity($identity);
        $this->setTransport($transport);
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
     * Get table with triggering the Event-Manager
     * 
     * @param  string $type table type
     * @return TableGateway
     */
    public function getTable($type = 'rentals')
    {
        if (!isset($this->tables[$type])) {
            $this->getEventManager()->trigger(
                'set-rentals-table', __CLASS__, array('type' => $type)
            );
        }
        
        return $this->tables[$type];
    }

    /**
     * Set table
     * 
     * @param TableGateway $table
     * @param string $type table type
     */
    public function setTable(TableGateway $table, $type = 'rentals')
    {
        $this->tables[$type] = $table;
    }
    
    /**
     * Set table
     *
     * @param TableGateway $table
     * @param string $type table type
     */
    public function isAvailableInPeriode($from, $to, $exclude_id = 0)
    {
   	
    	$select = $this->getTable('rentals')->getSql()->select();
    	
    	$select->order('from ASC');
    	
    	$select->where->greaterThan('to', $from)
    	              ->lessThan('from', $to)
    	              ->notLike('id', $exclude_id);
    	
    	foreach ($this->getTable('rentals')->selectWith($select) as $row) {
    		return false;
    	}
    	
    	return true;
    }
    

    
    /**
     * Get user identity
     *
     * @return UserEntityInterface
     */
    public function getIdentity()
    {
    	return $this->identity;
    }
    
    /**
     * Set user identity
     *
     * @param UserEntityInterface $identity
     * @return OrderServiceInterface
     */
    public function setIdentity(UserEntityInterface $identity = null)
    {
    	$this->identity = $identity;
    	return $this;
    }
    
    /**
     * Get transport
     *
     * @return UserEntityInterface
     */
    public function getTransport()
    {
    	return $this->transport;
    }
    
    /**
     * Set user transport
     *
     * @param UserEntityInterface $identity
     * @return OrderServiceInterface
     */
    public function setTransport(SmtpTransport $transport = null)
    {
    	$this->transport = $transport;
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
    public function setMessage($message, $type = 'info')
    {   	
        $this->message = array('message' => $message, 'type' => $type);
    }
    
    /**
     * Get form with triggering the Event-Manager
     * 
     * @param  string $type form type
     * @return RentalsFormInterface
     */
    public function getForm($type = 'create')
    {
        if (!isset($this->forms[$type])) {
            $this->getEventManager()->trigger(
                'set-rentals-form', __CLASS__, array('type' => $type)
            );
        }
        
        return $this->forms[$type];
    }

    /**
     * Set form
     * 
     * @param RentalsFormInterface $form
     * @param string $type form type
     */
    public function setForm(RentalsFormInterface $form, $type = 'create')
    {
        $this->forms[$type] = $form;
    }


    /**
     * Get openinghours
     *
     * @param varchar $id
     * @return RentalsEntityInterface
     */
    public function checkIfStationIsOpen($date)
    {
    	
    	$rentals = $this->getTable('openinghours')->isStationOpen($date, array(1));
    	
    	return $rentals->count();
    	
    }
    
    /**
     * Fetch single by id
     *
     * @param varchar $id
     * @return RentalsEntityInterface
     */
    public function fetchSingleById($id)
    {
        $rentals = $this->getTable('rentals')->fetchSingleById($id);        
        return $rentals;
    }
    
    public function fetchAll()
    {
    	
    }
    
    /**
     * Fetch list of rentalss
     *
     * @param integer $page number of page
     * @return Paginator
     */
    public function fetchList($page = 1, $perPage = 4, $params = array())
    {
        // Initialize select
        $select = $this->getTable('rentals')->getSql()->select();
        
        if ($this->getIdentity())
        {
        	$select->where->equalTo('user', $this->getIdentity()->getId());
        }
        else
        {
          return false;
        }
          
        $select->order('from DESC');
        
        // loop through params
        foreach ($params as $param => $value) {
            $select->where->equalTo($param, $value);
        }
        
        // Initialize paginator
        $adapter = new DbSelect(
            $select, 
            $this->getTable('rentals')->getAdapter(), 
            $this->getTable('rentals')->getResultSetPrototype()
        );
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($perPage);
        $paginator->setPageRange(9);
        
        // return paginator
        return $paginator;
    }
    
    /**
     * Save a rentals
     *
     * @param array $data input data
     * @param integer $id id of rentals entry
     * @return BlogEntityInterface
     */
    public function save(array $data, $id = null)
    {
        // check mode
        $mode = is_null($id) ? 'create' : 'update';
        
        // create new rentals entity
        if ($mode == 'create') {
            $rentals = new RentalsEntity();
        } else {
            $rentals = $this->fetchSingleById($id);
        }
        
        
        // get form and set data
        $form = $this->getForm($mode);
        $form->setData($data);
        
        // check for invalid data
        if (!$form->isValid()) {
        	$this->setMessage('Bitte Eingaben überprüfen!', 'warning');
        	return false;
        }
        
        // get form data
        $formData = $form->getData();
        
        // get valid rentals entity object
        $rentals->exchangeArray($formData);
        
        // get insert data
        $rentals->setUser($this->getIdentity()->getId());
        $saveData = $rentals->getArrayCopy();
        // FIXX ME - hack
        $saveData['from'] = date('Y-m-d H:i:00', strtotime($rentals->getFrom()));
        $saveData['to'] = date('Y-m-d H:i:00', strtotime($rentals->getTo()));
        
        $length = strtotime($rentals->getTo()) - strtotime($rentals->getFrom());  
        if ( $length < 0 )
        {
        	$this->setMessage('Die Rückgabe kann nicht vor dem Ausleihtermin sein.', 'warning');
        	return false;
        } 
        
        $max_days_to_rent = 2;
        if ((date('N', strtotime($rentals->getFrom()))==5))
        {
        	$max_days_to_rent = 3;
        }
        	
        if ( $length > (24 * 3600 * $max_days_to_rent) )
        {
        	$this->setMessage('Das Lastenrad kann maximal zwei Tage ausgeliehen werden.', 'warning');
        	return false;
        }
        
        if ( !$this->checkIfStationIsOpen( $saveData['from'] ) )
        {
        	$this->setMessage('Die Station ist zum Ausleih-Zeitpunkt nicht geöffnet. Bitte wähle eine andere Zeit. Du kannst uns auch eine E-Mail schreiben, vielleicht findet sich eine Lösung.', 'warning');
        	return false;
        }
                
        if ( !$this->checkIfStationIsOpen( $saveData['to'] ) )
        {
        	$this->setMessage('Die Station ist zum Rückgabe-Zeitpunkt nicht geöffnet. Bitte wähle eine andere Zeit. Du kannst uns auch eine E-Mail schreiben, vielleicht findet sich eine Lösung.', 'warning');
        	return false;
        }
        
        //$this->getRentalsService()->sendMail("andreas@zobl.at");
        
        // check if period is available
        // todo: wenn update des termins -> schon verfuegbar
        if (!$this->isAvailableInPeriode($saveData['from'], $saveData['to'], $id))
        {
        	$this->setMessage('Das Lastenrad ist in diesem Zeitraum leider schon gebucht.', 'warning');
        	return false;
        }
        
        
        // insert new rentals
        try {
            if ($mode == 'create') {
                $this->getTable('rentals')->insert($saveData);
                
                // get last insert value
                $id = $this->getTable('rentals')->getLastInsertValue();
            } else {
                $this->getTable('rentals')->update($saveData, array('id' => $id));
            }
        } catch (InvalidQueryException $e) {
            $this->setMessage('Die Reservierung konnte nicht vorgenommen werden!', 'error');
            \Zend\Debug\Debug::dump($e->__toString());
            return false;
        }

        
        
        // reload rentals
        $rentals = $this->fetchSingleById($id);
        
        $rentals_data = array(
        	'from' => $rentals->getFrom(), 
        	'to' => $rentals->getTo()
        );
        
        // set success message
        $this->setMessage('Die Reservierung wurde gespeichert!', 'success');
        $mail_content = <<<"EOT"

!!! Achtung - Die neue Verleihstation ist das Büro der Nachbarschaften - siehe Website !!!
         
Hallo, 

wir freuen uns, dass du das Lastenrad nutzen möchtest und bestätigen dir hiermit die Reservierung von {$saveData['from']} bis {$saveData['to']}.

Bitte hol das Lastenrad pünktlich bei der Verleihstation ab und bring es auch pünktlich wieder zurück.

Bitte bring bei der Abholung einen Lichtbildausweis mit, da wir uns aus rechtlichen Gründen deine Daten notieren müssen. Auf unserer Website [1] findest du die Nutzungsbedingungen für Das Lastenrad Graz, die du bei der Abholung akzeptierst.        

Der Verleih des Lastenrades ist selbstverständlich kostenlos. Wenn du das Lastenrad gut findest und unseren Verein zur Förderung von Lastenrädern unterstützen möchtest, freuen wir uns über eine kleine Spende, die wir zur Bezahlung von Wartung und Reparaturen verwenden. (Bei der Verleihstation findest du eine Spendenbox.)

Wir würden uns freuen, wenn du uns ein Foto und ein paar Erfahrungen zu deinem Lastenrad-Transport/Ausflug per E-Mail schicken (team@das-lastenrad.at) oder auf unsere Facebook-Seite posten würdest (www.facebook.com/DasLastenrad). Damit möchten wir anderen Menschen zeigen, was man mit einem Lastenrad alles machen kann.

Solltest du Fragen haben, schreib uns bitte ein E-Mail: team@das-lastenrad.at

		
Viel Spaß mit dem Lastenrad und eine gute Fahrt wünscht dir

Das Lastenrad-Team


[1] https://www.das-lastenrad.at/downloads/nutzungsbedingungen_freies_lastenrad.pdf

-- 
Verein zur Förderung von Lastenrädern
ZVR-Zahl 576525291
        
EOT;
        
        ;
        
        $mail_content_html = <<<"EOT"
        
<h2 style="color: red;">!!! Achtung - Die neue Verleihstation ist das Büro der Nachbarschaften - siehe Website !!!</h2>
        
<p>Hallo,<br><br>
        
wir freuen uns, dass du das Lastenrad nutzen möchtest und bestätigen dir hiermit die Reservierung von <b>{$saveData['from']}</b> bis <b>{$saveData['to']}</b>.</p>
        
<p>Bitte hol das Lastenrad pünktlich bei der Verleihstation ab und bring es auch pünktlich wieder zurück.</p>
        
<p>Bitte bring bei der Abholung einen Lichtbildausweis mit, da wir uns aus rechtlichen Gründen deine Daten notieren müssen. Auf unserer Website [1] findest du die Nutzungsbedingungen für Das Lastenrad Graz, die du bei der Abholung akzeptierst.</p>
        
<p>Der Verleih des Lastenrades ist selbstverständlich kostenlos. Wenn du das Lastenrad gut findest und unseren Verein zur Förderung von Lastenrädern unterstützen möchtest, freuen wir uns über eine kleine Spende, die wir zur Bezahlung von Wartung und Reparaturen verwenden. (Bei der Verleihstation findest du eine Spendenbox.)</p>
        
<p>Wir würden uns freuen, wenn du uns ein Foto und ein paar Erfahrungen zu deinem Lastenrad-Transport/Ausflug per E-Mail schicken (team@das-lastenrad.at) oder auf unsere Facebook-Seite posten würdest (www.facebook.com/DasLastenrad). Damit möchten wir anderen Menschen zeigen, was man mit einem Lastenrad alles machen kann.</p>
        
<p>Solltest du Fragen haben, schreib uns bitte ein E-Mail: team@das-lastenrad.at</p>
        
        
<p>Viel Spaß mit dem Lastenrad und eine gute Fahrt wünscht dir</p>
        
<p>Das Lastenrad-Team</p>
        
        
<p>[1] <a href="https://www.das-lastenrad.at/downloads/nutzungsbedingungen_freies_lastenrad.pdf" title="Nutzungsbedingungen">https://www.das-lastenrad.at/downloads/nutzungsbedingungen_freies_lastenrad.pdf</a></p>
        
--
Verein zur Förderung von Lastenrädern
ZVR-Zahl 576525291
        
EOT;
        
        ;
        
        
        
        $mail_settings = array();
        //$mail_settings['email_receiver'] = "station@das-lastenrad.at"; //$this->getServiceLocator()->get('config')['email_receiver'];
        $mail_settings['email_sender'] = "team@das-lastenrad.at"; //$this->getServiceLocator()->get('config')['email_sender'];
        
        $mail_settings['email_receiver'] = "andi@das-lastenrad.at";
        
        $this->sendMail($this->getIdentity()->getEmail(), "Reservierungsbestätigung für Das Lastenrad Graz", $mail_content, $mail_content_html, $rentals_data, $mail_settings);
        
        // return rentals
        return $rentals;
    }
    
    /**
     * Delete existing rentals
     *
     * @param integer $id rentals id
     * @param array $data input data
     * @return RentalsEntityInterface
     */
    public function delete($id)
    {
        // fetch rentals entity
        $rentals = $this->fetchSingleById($id);
        
        // delete existing rentals
        try {
            $result = $this->getTable('rentals')->delete(array('id' => $id));
        } catch (InvalidQueryException $e) {
            return false;
        }

        // set success message
        $this->setMessage('Die Ausleihe wurde gelöscht!', 'success');
        
        // return result
        return true;
    }
    
    /**
     * Send Mail to User
     *
     * @param integer $id rentals id
     * @param array $data input data
     * @return RentalsEntityInterface
     */
    public function sendMail($address, $subject, $content_text, $content_html, $rentals_data, $mail_settings)
    {
    	
    	$message = new Message();
    	$message->addTo($address)
    	   ->setEncoding("UTF-8")   	
    	   ->addFrom($mail_settings['email_sender'])
    	   ->addBcc($mail_settings['email_receiver'])
    	   
    	   ->setSubject($subject);
    	
    	// Set UTF-8 charset
    	$headers = $message->getHeaders();
    	$headers->removeHeader('Content-Type');
    	$headers->addHeaderLine('Content-Type', 'text/plain; charset=UTF-8');
    	
    	$uid = "daslastenradat"; // setting this to an existing uid updates event, a new uid adds event
    	$summary = 'Lastenrad ausleihen';
    	$tstart = date('Ymd\THis', strtotime($rentals_data['from']));
    	$tend = date('Ymd\THis', strtotime($rentals_data['to']));
    	$tstamp = gmdate("Ymd\THis\Z");

    	    	
$ical = "BEGIN:VCALENDAR
VERSION:2.0
METHOD:PUBLISH
BEGIN:VEVENT
METHOD:PUBLISH
DTSTART;TZID=Europe/Vienna;VALUE=DATE-TIME:$tstart
DTEND;TZID=Europe/Vienna;VALUE=DATE-TIME:$tend
LOCATION:Verleihstation - siehe Website 
SEQUENCE:0
UID:$uid
DTSTAMP:$tstamp
SUMMARY:$summary
PRIORITY:5
X-MICROSOFT-CDO-IMPORTANCE:1
CLASS:PUBLIC
END:VEVENT
END:VCALENDAR";

    	
    	$at = new MimePart($ical);
    	$at->type = 'text/calendar';
    	$at->disposition = Mime::DISPOSITION_INLINE;
    	$at->encoding = Mime::ENCODING_8BIT;
    	$at->filename = 'termin.ics';
    	
    	//Content-Type: text/calendar; method=REQUEST
    	//Content-Transfer-Encoding: Base64
    	//Content-Disposition: attachment; filename=iCal-20140915-070904.ics
    	
    	//$message->addAttachment($at);
    	
    	$content  = new MimeMessage();
    	
    	$htmlPart = new MimePart($content_html);
    	$htmlPart->type = 'text/html';
    	
    	$textPart = new MimePart($content_text);
    	$textPart->type = 'text/plain';
    	
    	$content->setParts(array($textPart, $htmlPart));
    	
    	$contentPart = new MimePart($content->generateMessage());
    	$contentPart->type = 'multipart/alternative;' . PHP_EOL . ' boundary="' . $content->getMime()->boundary() . '"';
    	
    	$attachment = new MimePart(fopen('C:\projekte\lastenrad\public\downloads\nutzungsbedingungen_freies_lastenrad.pdf', 'r'));
    	$attachment->type = 'application/pdf';
    	$attachment->encoding    = Mime::ENCODING_BASE64;
    	$attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
    	
    	
    	$body = new MimeMessage();
        $body->setParts(array($contentPart, $attachment, $at));
    	
    	$message->setBody($body);
    	
    	
    	//$transport->setOptions($options);
    	$this->transport->send($message);
    	return true;
    }
    
    
}
