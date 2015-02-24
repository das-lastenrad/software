<?php


/**
 * namespace definition and usage
 */
namespace Rentals\Form;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\File;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Date;
use Zend\Form\Element\DateTime;
use Zend\Form\View\Helper\FormDateTime;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\Form\FormInterface;

use Zend\Form\Element;


/**
 * Rentals Form
 * 
 * @package    Rentals
 */
class RentalsForm extends Form implements RentalsFormInterface
{
    /**
     * Add csrf element
     */
    public function addCsrfElement($name = 'tick')
    {
        $element = new Csrf($name);
        $this->add($element);
    }
        
    /**
     * Add id element
     */
    public function addIdElement($name = 'id')
    {
        $element = new Hidden($name);
        $this->add($element);
    }

    
    /**
     * Add name element
     */
    public function addNameElement($name = 'name')
    {
        $element = new Text($name);
        $element->setLabel('Name');
        $element->setAttribute('class', 'span3');
        $this->add($element);
    }
    
    /**
     * Add name element
     */
    public function addFromElement($from = 'from')
    {
    	
    	$dateTime = new Element\DateTime($from);
    	$dateTime
    	  ->setLabel('Von')
    	  ->setAttributes(array(
    	  		'class' => 'datepicker form-control',
    			//'min'  => '2010-01-01T00:00:00Z',
    			//'max'  => '2020-01-01T00:00:00Z',
    			//'step' => '1', // minutes; default step interval is 1 min
    	));
    	
    	//$dateTime->setFormat('Y-m-d\TH:iP'); // 'd.m.Y H:i'
    	$dateTime->setFormat('d.m.Y H:i'); // 'd.m.Y H:i'
    	
    	$this->add($dateTime);	

    }
    
    /**
     * Add name element
     */
    public function addToElement($to = 'to')
    {
    	$element = new Text($to);
    	$element->setLabel('bis');
    	$element->setAttribute('class', 'datepicker form-control' );
    	$this->add($element);
    }
    
    /**
     * Add description element
     */
    public function addDescriptionElement($name = 'description')
    {
        $element = new Textarea($name);
        $element->setLabel('Was hast du mit dem Rad vor? (optional -  falls du das den anderen Nutzern mitteilen magst)');
        $element->setAttribute('class', 'form-control');
        $this->add($element);
    }
    
    /**
     * Add submit element
     */
    public function addSubmitElement($name = 'save', $label = 'Speichern')
    {
        $element = new Submit($name);
        $element->setValue($label);
        $element->setAttribute('class', 'btn btn-primary');
        $this->add($element);
    }
    
    /**
     * Bind an object to the form
     */
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        parent::bind($object, $flags);
      
    }
}
