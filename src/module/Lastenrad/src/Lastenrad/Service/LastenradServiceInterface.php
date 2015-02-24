<?php

namespace Lastenrad\Service;

use Lastenrad\Table\RentalsTableInterface;

interface LastenradServiceInterface
{
	public function __construct(RentalsTableInterface $table);
	public function getTable();
	public function setTable(RentalsTableInterface $table);
	public function fetchSingleById($id);
	public function fetchList($page = 1, $perPage = 15);
	
}