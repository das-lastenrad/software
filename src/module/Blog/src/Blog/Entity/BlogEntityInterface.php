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
namespace Blog\Entity;

use Zend\Stdlib\ArraySerializableInterface;

/**
 * Blog entity interface
 * 
 * @package    Blog
 */
interface BlogEntityInterface extends ArraySerializableInterface
{
    /**
     * Set id
     * 
     * @param integer $id
     */
    public function setId($id);
    
    /**
     * Get id
     * 
     * @return integer $id
     */
    public function getId();
    
    /**
     * Set cdate
     * 
     * @param string $cdate
     */
    public function setCdate($cdate);
    
    /**
     * Get cdate
     * 
     * @return string $cdate
     */
    public function getCdate();
    
    /**
     * Set title
     * 
     * @param string $title
     */
    public function setTitle($title);
    
    /**
     * Get title
     * 
     * @return string $title
     */
    public function getTitle();
    
    /**
     * Set teaser
     * 
     * @param string $teaser
     */
    public function setTeaser($teaser);
    
    /**
     * Get teaser
     * 
     * @return string $teaser
     */
    public function getTeaser();
    
    /**
     * Set content
     * 
     * @param string $content
     */
    public function setContent($content);
    
    /**
     * Get content
     * 
     * @return string $content
     */
    public function getContent();
    
    /**
     * Set url
     * 
     * @param string $url
     */
    public function setUrl($url);
    
    /**
     * Get url
     * 
     * @return string $url
     */
    public function getUrl();
}
