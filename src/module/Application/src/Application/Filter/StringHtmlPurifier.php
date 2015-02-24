<?php

/**
 * namespace definition and usage
 */
namespace Application\Filter;

use Zend\Filter\AbstractFilter;
use \HTMLPurifier;
use \HTMLPurifier_Bootstrap;
use \HTMLPurifier_Config;

/**
 * String HtmlPurifier filter
 * 
 * Filters text with HTMLPurifier
 * 
 * @package    Application
 */
class StringHtmlPurifier extends AbstractFilter
{
    /**
     * HTMLPurifier instance
     *
     * @var \HTMLPurifier
     */
    protected $htmlPurifer = null;

    /**
     * Setup for HTMLPurifier
     *
     * @param  string $delimiter
     * @return void
     */
    public function __construct()
    {
        if (!class_exists('HTMLPurifier_Bootstrap', false)) {
            spl_autoload_register(array('HTMLPurifier_Bootstrap', 'autoload'));
        }
        $config = HTMLPurifier_Config::createDefault();
        $config->set(
            'Cache.SerializerPath', LASTENRAD_ROOT . '/data/htmlpurifier'
        );
        $def = $config->getHTMLDefinition(true);
        $this->htmlPurifer = new HTMLPurifier($config);
    }
 
    /**
     * Filter the text
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        return $this->htmlPurifer->purify($value);
    }
}
