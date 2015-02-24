<?php

namespace Lastenrad\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;



class IndexController extends AbstractActionController
{
	public function indexAction()
	{
		$lastenraederList = array(
				'einser', 'zweier', 'dreier',
		);
		

		
		return new ViewModel( array(
				'lastenraederList' => $lastenraederList,
				
		));
		
	}
}

