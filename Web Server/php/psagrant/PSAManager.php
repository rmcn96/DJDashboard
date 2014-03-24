<?php
/*
 * Created on Mar 15, 2014
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 include_once('../sqlconnect.php');
 include_once('PublicServiceAnnouncement.php');
 /**
  * Created to manage all PSAs.  Used for creating, managing, and deleting PSAs.
  * rclabou 3/15/14
  */
 class PSAManager
 {
 	
 	private $spInsertPSA;
 	private $spGetEligiblePSAs;
 	private $spGetAllPSAInfo;
 	
 	
 	public function __construct() {
		$this->initialize();
	}
	
	public function initialize()
	{
		$this->spInsertPSA = "AddPSA";
		$this->spGetEligiblePSAs = "GetEligiblePSAs";
		$this->spGetAllPSAInfo = "GetAllPSAInfo";
	}
 	
 	/**
 	 * Retrieves n number of PSAs to the calling application.  The exact amount is given by $numberOfPSA while this method retrieves
 	 * the highest priority ones from the DB.  Returns an array of PublicServiceAnnouncement objects.
 	 */
 	public function getPSAs($numberOfPSA)
 	{
 		
 		//Initial call: Get all PSAs that are eligible
 		try
 		{
 			$conn = new sqlConnect();
 			$results = $conn->callStoredProc($this->spGetEligiblePSAs, null);
 			$eligibleArr = array();
 			while ($rowInfo = mysqli_fetch_assoc($results)) 
 			{
 				$p = new PublicServiceAnnouncement();
 				
 				$p->setID(utf8_encode($rowInfo['PSAID']));
 				$p->setPlayCount(utf8_encode($rowInfo['PlayCount']));
 				$p->setCreateDate(utf8_encode($rowInfo['StartDate']));
 				$p->setEndDate(utf8_encode($rowInfo['EndDate']));
 				$p->setMaxPlayCount(utf8_encode($rowInfo['MaxPlayCount']));
 				$p->setTimeLeft(utf8_encode($rowInfo['TimeLeft']));
 				//Calculate the priority
				$playDiff = $p->getMaxPlayCount() - $p->getPlayCount();
				$dateDiff = $p->getTimeLeft();
				
				//RATIO
				if($dateDiff != 0)
				{
					$p->setPriority($playDiff * pow(10, 6) / $dateDiff);
				}
				else
				{
					$p->setPriority($playDiff * pow(10, 6));
				}
 				$eligibleArr[] = $p;
 			}
 			
 			//Sort the eligible PSAs
 			
			$arrLen = count($eligibleArr);
			
			//Get count to receive, if we are for more than are eligible, only return eligible ones
			if($numberOfPSA > count($arrLen))
			{
				$numberOfPSA = count($arrLen);
			}

			//Sort them by priority
			usort($eligibleArr, "PublicServiceAnnouncement::cmp");
			
			//Create finalized array
			$finalArr = array();
			
			//get remaining info for final ones
			for($i = 0; $i < $numberOfPSA; $i++)
			{
				$conn->freeResults();
				$results = $conn->callStoredProc($this->spGetAllPSAInfo, array($eligibleArr[$i]->getID()));
				while ($rowInfo = mysqli_fetch_assoc($results)) {
					$eligibleArr[$i]->setName(utf8_encode($rowInfo['Name']));
					$eligibleArr[$i]->setMessage(utf8_encode($rowInfo['Message']));
					$eligibleArr[$i]->setSponsor(utf8_encode($rowInfo['Sponsor']));
					$finalArr[] = $eligibleArr[$i];
				}
			}
			
			return $finalArr;
 		}
 		catch (Exception $e) {
			Publisher :: publishException($e->getTraceAsString(), $e->getMessage(), 0);
			return false;
		}
 		//Second call, take highest n number of PSAs and return them in an array.
 	}
 	
 	
 	/**
 	 * Used to add a PSA to the database.  $psa must be a PublicServiceAnnouncement object already, then this prepares it for the sql call.
 	 * Returns ID of new PSA.
 	 * rclabou 3/15/14
 	 */
 	public function addPSA($psa)
 	{
 		$args = array();
 		$args[] = $psa->getName();
 		$args[] = $psa->getMessage();
 		$args[] = $psa->getEndDate();
 		$args[] = $psa->getSponsor();
 		
 		$conn = new sqlConnect();
 		
 		$results = $conn->callStoredProc($this->spInsertPSA, $args);	
 		$id = 0;
 		while ($rowInfo = mysqli_fetch_assoc($results)) {
 			$id = utf8_encode($rowInfo['ID']);
 			
 		}
 		return $id;
 	}
 	
 }
?>