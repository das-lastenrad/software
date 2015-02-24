<?php

namespace Lastenrad\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Lastenrad\Service\LastenradServiceInterface;


class KalenderJsonController extends AbstractActionController
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
		
		$rentalList = $this->getLastenradService()->fetchList(1, 300);
		
		foreach ($rentalList as $rental)
		{
			$items[] = array(
					'id' => $rental->getId(),
					'title' => $rental->getDescription(),
					'start' => $rental->getfrom(),
					'end' => $rental->getto(),
					'color' => '#ff0000',
					'borderColor ' => 'green',					
			);
		}
		
		/*
		$jsonModel = new JsonModel();
		$jsonModel->setVariables(
				$items
		)->setTerminal(true);
		
		return $jsonModel;
		*/
		
		$content = json_encode($items);
		
		$response = $this->getResponse();
		$headers = $response->getHeaders();
		$headers->addHeaderLine('Content-Type', 'application/json');
		$headers->addHeaderLine('Content-Disposition', "attachment; filename=\"kalender.json\"");
		$headers->addHeaderLine('Accept-Ranges', 'bytes');
		$headers->addHeaderLine('Content-Length', strlen($content));
		
		$response->setContent($content);
		return $response;
		
	}
}

