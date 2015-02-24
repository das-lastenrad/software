<?php

namespace Lastenrad\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Lastenrad\Service\LastenradServiceInterface;


class KalenderController extends AbstractActionController
{
	protected $lastenradService;
	
	public function setLastenradService(
			LastenradServiceInterface $lastenradService
	)
	{
		$this->lastenradService = $lastenradService;
		return $this;	
	}
	
	
	public function getLastenradService()
	{
		return $this->lastenradService;		
	}
	
	public function indexAction()
	{
		$page = (int) $this->params()->fromRoute('page');
		$maxPage = 10;
		
		return new ViewModel( array(
				'rentalList' => $this->getLastenradService()->fetchList($page, $maxPage),
		));
		
	}
}

