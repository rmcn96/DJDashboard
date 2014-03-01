package libraryManager;

import java.util.Map;
import sqlConnect.DBINFO;

public class Track
{
	private static final String EXPLICIT_TAG = "[EXPLICIT]";
	private static final String RECO_TAG = "[RECO]";
	private String name;
    private String artist;
    private String album;
    private int ID;
    private String path;
    private int trackNum;
    private int iTunesID;
    private String primaryGenre;
    private String secondaryGenre;
    private long totalTime;
    private long sampleRate;
    private boolean FCC;
    private int playCount;
    private boolean recommended;

    public Track()
    {
    	FCC = false;
    	recommended = false;
    }
        
    public String getName() 
    {
    	return name;
    }

    public void setName(String name) 
    {
    	this.name = name;
        if(name.toUpperCase().contains(EXPLICIT_TAG))
        {
        	setFCC(true);
        	stripTag(EXPLICIT_TAG);
        }
        if(name.toUpperCase().contains(RECO_TAG))
        {
        	setRecommended(true);
        	stripTag(RECO_TAG);
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

     public String getArtist() 
     {
    	 return artist;
     }

     public void setArtist(String artistName) 
     {
    	 this.artist = artistName;
     }

     public String getAlbum() 
     {
    	 return album;
     }

     public void setAlbum(String albumName) 
     {
    	 this.album = albumName;
     }

     public int getPlayCount() 
     {
    	 return playCount;
     }

     public void setPlayCount(int playCount) 
     {
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
        
     public String dbQuery(Map<String, Pair<Integer, String>> genres)
     {
        //Escape apostrophes
        this.name = this.name.replaceAll("'","");
        this.name = this.name.length() > 100 ? this.name.substring(0, 99) : this.name;
        this.artist = this.artist.replaceAll("'","");
        this.artist = this.artist.length() > 100 ? this.artist.substring(0, 99) : this.artist;
        this.album = this.album.replaceAll("'","");
        this.album = this.album.length() > 100 ? this.album.substring(0, 99) : this.album;
        this.path = this.path.replaceAll("'", "");
        this.path = this.path.replaceAll("%20", " "); //escape %20
        	
        int pGenre = this.primaryGenre != null ? verifyGenre(genres, this.primaryGenre) : -1;
        int sGenre = this.secondaryGenre != null ? verifyGenre(genres, this.secondaryGenre) : -1;
        		
        //Query goes : Name, Artist, Album, PlayCount, FCCFlag, Recommended, ItunesID, ReleaseDate, EndDate
        String query = "Call " + DBINFO.DATABASE + "." + DBINFO.ADDTRACK + "('" + this.name + "','" +
        		this.artist + "','" + this.album + "'," + this.playCount + "," + this.FCC + "," + 
        		this.recommended + "," + 
        		iTunesID + ",null, null," + (pGenre != -1 ? pGenre : "null") + "," + (sGenre != -1 ? sGenre : "null") + ",'" + this.path + "');"; 
        return query;
        	
     }

	 public void setTrackNumber(Integer value) 
	 {
		 trackNum = value;	
	 }
		
	 public int getTrackNumber()
	 {
		 return trackNum;
	 }

	 public void setITL(Integer value) 	
	 {
		 iTunesID = value;	
	 }
		
	 public void setPath(String value)
	 {
		 path = value;
	 }
			
	 public String getPath()
	 {
		 return path;
	 }

	 public void setTotalTime(Integer value) 
	 {
		 totalTime = value;	
	 }
		
	 public void setPrimaryGenre(String pGenre)
	 {
		 primaryGenre = pGenre;
	 }
		
	 public String getPrimaryGenre()
	 {
		 return primaryGenre;
	 }
		
	 public void setSecondaryGenre(String sGenre)
	 {
		 secondaryGenre = sGenre;
	 }
		
	 public String getSecondaryGenre()
	 {
		 return secondaryGenre;
	 }

	 public void setSampleRate(Integer value) 
	 {
		 sampleRate = value;
	 }
		
	 public long getSampleRate()
	 {
		 return sampleRate;
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
		 if(obj == null) { return false; }
		 if(!(obj instanceof Track)) { return false; }
			
		 Track other = (Track) obj;
		 return other.getID() == this.ID;
	 }
		
	 /**
	  * Removes given tag from the track name
	  * @param tag
	  * the tag to be removed
	  */
	 private void stripTag(String tag)
	 {
		 String temp = name;
		 String word = null;
		 int lBracket = -1;
		 int rBracket = -1;
			
		 while(temp.length() != 0)
		 {
			 lBracket = temp.indexOf("[");
			 rBracket = temp.indexOf("]");
			
			 if(lBracket == -1 || rBracket == -1) { break; } //no potential tags left, so exit
			
			 word = temp.substring(lBracket, rBracket + 1); //any word in the for of [word]
			 temp = temp.substring(Math.min(temp.length(), (rBracket + 1))); //only keeps everything to the right of ']'
			 if(word.equalsIgnoreCase(tag))
			 {
				 name = name.replace(word, "");
			 }
		 }
		 name = name.trim();
	 }
		
	 /**
	  * Makes sure that genre for the track is a valid genre in the database
	  * @param genres
	  * collection of genres in the form of COMPARISON_GENRE_NAME=[Database_Genre_id, Database_Genre_name]
	  * @param genre
	  * genre of the track
	  * @return
	  * returns the database id of the genre if the genre is valid and -1 if it is not
	  */	
	 public int verifyGenre(Map<String, Pair<Integer, String>> genres, String genre)	
	 {
		 if(genres == null || genre == null) { return -1; }
			
		 Pair<Integer, String> pair = genres.get(genre.toUpperCase());
		 if(pair != null) 
		 { 
			 return pair.getFirst(); 
		 }
		 return -1;
	 }
}

