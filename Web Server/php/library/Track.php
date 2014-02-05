<?php
	class Track
	{
		private $ID;
		private $Name;
		private $FCC;
		private $Recommended;
		private $PlayCount;
		private $CreateDate;
		
		//Data Fetching
		public function getPlays()
		{
			
		}
		
		//'Plays' the track, adds to the plays table
		public function Play()
		{
			$lib = new LibraryManager();
			$lib->PlayTrack($this->ID);
		}
		
		
		
		//Getters / Setters
		public function getID()
		{
			return $this->ID;
		}
		public function setID($ID)
		{
			$this->ID = $ID;
		}
		
		public function getName()
		{
			return $this->Name;
		}
		public function setName($Name)
		{
			$this->Name = $Name;
		}
		
		public function getFCC()
		{
			return $this->FCC;
		}
		public function setFCC($FCC)
		{
			$this->FCC = $FCC;
		}
		
		public function getRecommended()
		{
			return $this->Recommended;
		}
		public function setRecommended($Reco)
		{
			$this->Recommended = $Reco;
		}
		
		public function getPlayCount()
		{
			return $this->PlayCount;
		}
	}
?>