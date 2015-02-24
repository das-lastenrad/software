<?php

/**
 * namespace definition and usage
 */
namespace Application\View\Helper;

use Zend\Form\Element\Hidden;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\View\Helper\AbstractHelper;

/**
 * Show form view helper
 * 
 * Outputs a form in Twitter Bootstrap style
 * 
 * @package    Application
 */
class ShowForm extends AbstractHelper
{
    /**
     * Outputs message depending on flag
     *
     * @return string 
     */
    public function __invoke(Form $form, $url, $class = 'form-horizontal', $role = 'form')
    {
        $form->setAttribute('action', $url);
        $form->setAttribute('class', $class);
        $form->setAttribute('role', $role);
        $form->prepare();
        
        $output = $this->getView()->form()->openTag($form);
        
        $submitElements = array();
        
        foreach ($form as $element) {
            if ($element instanceof Submit) {
                $submitElements[] = $element;
            } elseif ($element instanceof Csrf 
                || $element instanceof Hidden
            ) {
                $output .= $this->getView()->formElement($element);
            } else {
                $element->setLabelAttributes(array('class' => ''));
                
                $output .= '<div class="form-group">';
                $output .= $this->getView()->formLabel($element);
                //$output .= '<div class="controls">';
                $output .= $this->getView()->formElement($element);
                $output .= $this->getView()->formElementErrors($element);
                //$output .= '</div>';
                $output .= '</div>';
            }
        }
        
        $output .= '<div class="form-actions">';
        foreach ($submitElements as $element) {
            $output .= $this->getView()->formElement($element) . '&nbsp;';
        }
        $output .= '</div>';
        
        $output .= $this->getView()->form()->closeTag();
        
        return $output;
    }
}
