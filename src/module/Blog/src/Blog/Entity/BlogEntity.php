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

/**
 * Blog entity
 * 
 * @package    Blog
 */
class BlogEntity implements BlogEntityInterface
{
    protected $id;
    protected $cdate;
    protected $title;
    protected $teaser;
    protected $content;
    protected $url;
    
    /**
     * Set id
     * 
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * Get id
     * 
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set cdate
     * 
     * @param string $cdate
     */
    public function setCdate($cdate)
    {
        $this->cdate = $cdate;
    }
    
    /**
     * Get cdate
     * 
     * @return string $cdate
     */
    public function getCdate()
    {
        return $this->cdate;
    }
    
    /**
     * Set title
     * 
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * Get title
     * 
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set teaser
     * 
     * @param string $teaser
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;
    }
    
    /**
     * Get teaser
     * 
     * @return string $teaser
     */
    public function getTeaser()
    {
        return $this->teaser;
    }
    
    /**
     * Set content
     * 
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
    
    /**
     * Get content
     * 
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Set url
     * 
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    /**
     * Get url
     * 
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * Exchange internal values from provided array
     *
     * @param  array $array
     * @return void
     */
    public function exchangeArray(array $array)
    {
        foreach ($array as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $method = 'set' . ucfirst($key);
            if (!method_exists($this, $method)) {
                continue;
            }
            $this->$method($value);
        }
    }

    /**
     * Return an array representation of the object
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return array(
            'id'      => $this->getId(),
            'cdate'   => $this->getCdate(),
            'title'   => $this->getTitle(),
            'teaser'  => $this->getTeaser(),
            'content' => $this->getContent(),
            'url'     => $this->getUrl(),
        );
    }
}
