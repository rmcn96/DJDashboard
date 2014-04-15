<?php
/*
 * Created on Mar 3, 2014
 *
 * Created by Robert Clabough
 */
 
 /**
  * Container for grant info for PHP
  */
 class Grant implements JsonSerializable
 {
 	private $grantID;
 	private $name;
 	private $message;
 	private $playCount;
 	private $maxPlayCount;
 	private $startDate;
 	private $endDate;
 	private $priority; //Double to keep track of the priority
 	private $timeLeft;
 	private $isActive;
 	
 	public function __construct() {
 		$this->grantID = "";
 		$this->name = "";
 		$this->message = "";
 		$this->playCount = "";
 		$this->maxPlayCount = "";
 		$this->startDate = "";
 		$this->endDate = "";
 		$this->priority = "";
 		$this->timeLeft = "";
 		$this->isActive = "";
 		
 	}
 	
 	public function jsonSerialize()
	{
		$arr = array();
		$arr['GrantID'] = $this->grantID;
		$arr['GrantName'] = $this->name;
		$arr['Message'] = $this->message;
		$arr['PlayCount'] = $this->playCount;
		$arr['MaxPlayCount'] = $this->maxPlayCount;
		$arr['StartDate'] = $this->startDate;
		$arr['EndDate'] = $this->endDate;
		$arr['Priority'] = $this->priority;
		$arr['TimeLeft'] = $this->timeLeft;
		$arr['Active'] = $this->isActive;
		return $arr;
	}
 	
 	//COMPARER
 	static function cmp($a, $b)
	{
    	return $a->priority < $b->priority;
	}
	
	public static function BuildFromBasicInfoProc($table){
		$g = new Grant();
		$g->grantID = utf8_encode($table['GrantID']);
		$g->name = utf8_encode($table['GrantName']);
		$g->startDate = utf8_encode($table['CreateDate']);
		$g->playCount = utf8_encode($table['PlayCount']);
		$g->isActive = utf8_encode($table['Active']);
		return $g;
	}
 	
 	public function setMaxPlayCount($mpc)
 	{
 		$this->maxPlayCount = $mpc;
 	}
 	public function getMaxPlayCount()
 	{
 		return $this->maxPlayCount;
 	}
 	public function setTimeLeft($timeLeft)
 	{
 		$this->timeLeft = $timeLeft;
 	}
 	public function getTimeLeft()
 	{
 		return $this->timeLeft;
 	}
 	
 	public function setPriority($priority)
 	{
 		$this->priority = $priority;
 	}
 	public function getPriority()
 	{
 		return $this->priority;
 	}
 	
 	public function setGrantID($ID)
 	{
 		$this->grantID = $ID;
 	}
 	public function getGrantID()
 	{
 		return $this->grantID;
 	}
 	public function setGrantName($name)
 	{
 		$this->name = $name;
 	}
 	public function getGrantName()
 	{
 		return $this->name;
 	}
 	public function setMessage($message)
 	{
 		$this->message = $message;
 	}
 	public function getMessage()
 	{
 		return $this->message;
 	}
 	public function setPlayCount($playCount)
 	{
 		$this->playCount = $playCount;
 	}
 	public function getPlayCount()
 	{
 		return $this->playCount;
 	}
 	public function setStartDate($startDate)
 	{
 		$this->startDate = $startDate;
 	}
 	public function getStartDate()
 	{
 		return $this->startDate;
 	}
 	public function setEndDate($endDate)
 	{
 		$this->endDate = $endDate;
 	}
 	public function getEndDate()
 	{
 		return $this->endDate;
 	}
 }
 
?>
