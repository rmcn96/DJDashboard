package libraryManager;


//import sqlConnect.DBINFO;


public class Track
{

        private String name;
        private String artist;
        private String album;
        private int ID;
        private String path;
        private int trackNum;
        private int iTunesID;
        private long totalTime;
        private long sampleRate;
        private boolean FCC;
        private int playCount;
        private boolean recommended;

        public String getName() {
            return name;
        }

        public Track()
        {
        	FCC = false;
        	recommended = false;
        }
        
        public void setName(String name) {
            this.name = name;
            if(name.contains("EXPLICIT"))
            {
            	setFCC(true);
            }
            if(name.toUpperCase().contains("RECO"))
            {
            	setRecommended(true);
            }
        }
        
        public void setRecommended(boolean value)
        {
        	recommended = value;
        }
        
        public void setFCC(boolean value)
        {
        	FCC = value;
        }

        public String getArtist() {
            return artist;
        }

        public void setArtist(String artistName) {
            this.artist = artistName;
        }

        public String getAlbum() {
            return album;
        }

        public void setAlbum(String albumName) {
            this.album = albumName;
        }

        public int getPlayCount() {
            return playCount;
        }

        public void setPlayCount(int playCount) {
            this.playCount = playCount;
        }
        
        public void setID(int newId)
        {
        	ID = newId;
        }
        
        public int getID()
        {
        	return ID;
        }
        
        /*public String dbQuery()
        {
        	//Escape apostrophes
        	this.name = this.name.replaceAll("'","");
        	this.name = this.name.length() > 100 ? this.name.substring(0, 99) : this.name;
        	this.artist = this.artist.replaceAll("'","");
        	this.artist = this.artist.length() > 100 ? this.artist.substring(0, 99) : this.artist;
        	this.album = this.album.replaceAll("'","");
        	this.album = this.album.length() > 100 ? this.album.substring(0, 99) : this.album;
        	this.path = this.path.replaceAll("'", "");
        	
        	//escape %20
        	this.path = this.path.replaceAll("%20", " ");
        	
        	//Query goes : Name, Artist, Album, PlayCount, FCCFlag, Recommended, ItunesID, ReleaseDate, EndDate
        	String query = "Call " + DBINFO.DATABASE + "." + DBINFO.ADDTRACK + "('" + this.name + "','" +
        			this.artist + "','" + this.album + "'," + this.playCount + "," + this.FCC + "," + 
        			this.recommended + "," + 
        			iTunesID + ",null, null, '" + this.path + "');"; 
        	return query;
        	
        }*/

		public void setLocation(String tempVal) {
			path = tempVal;
			
		}

		public void setTrackNumber(Integer value) {
			trackNum = value;
			
		}

		public void setITL(Integer value) {
			iTunesID = value;
			
		}
		
		public void setPath(String value)
		{
			path = value;
		}

		public void setTotalTime(Integer value) {
			totalTime = value;
			
		}

		public void setSampleRate(Integer value) {
			sampleRate = value;
			
		}
		
		public int getITunesID()
		{
			return iTunesID;
		}
		
		public boolean equalsITunesID(Track other)
		{
			return other.getITunesID() == this.iTunesID;
		}
		
		@Override
		public boolean equals(Object obj)
		{
			if(!(obj instanceof Track))
			{
				return false;
			}
			
			Track other = (Track) obj;
			return other.getID() == this.ID;
		}
		
}
