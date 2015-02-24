<?php

/**
 * namespace definition and usage
 */
namespace Blog\Controller;

use Zend\Feed\Writer\Feed;
use Zend\View\Model\FeedModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Blog\Service\BlogServiceInterface;

/**
 * Blog controller
 * 
 * Handles the blog pages
 * 
 * @package    Blog
 */
class BlogController extends AbstractActionController
{
    /**
     * @var BlogServiceInterface
     */
    protected $blogService;
    
    /**
     * set the blog service
     * 
     * @param BlogServiceInterface
     */
    public function setBlogService(BlogServiceInterface $blogService)
    {
        $this->blogService = $blogService;

        return $this;
    }
    
    /**
     * Get the blog service
     * 
     * @return BlogServiceInterface
     */
    public function getBlogService()
    {
        return $this->blogService;
    }
    
    /**
     * Handle blog page
     */
    public function indexAction()
    {
        // read page from route
        $page = (int) $this->params()->fromRoute('page');
        
        // set max blog per page
        $maxPage = 10;
        
        // read data and pass to view
        return new ViewModel(array(
            'blogList' => $this->getBlogService()->fetchList($page, $maxPage),
        ));
    }
    
    /**
     * Handle show page
     */
    public function showAction()
    {
        // read url from route
        $url = $this->params()->fromRoute('url');
        
        // fetch data
        $blogData = $this->getBlogService()->fetchSingleByUrl($url);
        
        // check data
        if (!$blogData) {
            // Redirect to blog page
            return $this->redirect()->toRoute('blog');
        }
        
        // read data and pass to view
        return new ViewModel(array(
            'blogData' => $blogData,
        ));
    }
    
    /**
     * Handle rss page
     */
    public function rssAction()
    {
        // set page and max blog per page
        $page    = 1;
        $maxPage = 10;
        
        // get blog entries
        $blogList = $this->getBlogService()->fetchList($page, $maxPage);
        
        // create feed
        $feed = new Feed();
        $feed->setTitle('Luigis Pizza-Blog');
        $feed->setFeedLink('http://luigis-pizza.local/blog/rss', 'atom');
        $feed->addAuthor(array(
            'name'  => 'Luigi Bartoli',
            'email' => 'luigi@luigis-pizza.de',
            'uri'   => 'http://luigis-pizza.local',
        ));
        $feed->setDescription('Luigis Pizza-Blog Beiträge');
        $feed->setLink('http://luigis-pizza.local');
        $feed->setDateModified(time());
        
        // add blog entries
        foreach ($blogList as $blog) {
            $entry = $feed->createEntry();
            $entry->setTitle($blog->getTitle());
            $entry->setLink('http://luigis-pizza.local/blog/' . $blog->getUrl());
            $entry->setDescription($blog->getContent());
            $entry->setDateCreated(strtotime($blog->getCdate()));
            
            $feed->addEntry($entry);
        }
        
        // create feed model
        $feedmodel = new FeedModel();
        $feedmodel->setFeed($feed);
        
        return $feedmodel;
    }
}
