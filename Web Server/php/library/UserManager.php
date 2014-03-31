<?php
include_once ('../sqlconnect.php');
include_once ('../publisher.php');
include_once ("../classes/authConfig.php");
include_once ("../classes/authUtil.php");

class UserManager {
	private $spOnAirLogin;
	private $spOnAirLogout;
	private $spUserLogin;
	private $spUserLogout;
	private $spAddUser;
	private $spAddUserType;
	private $spGetUserFromName;
	private $spEndSession;
	private $spStartOnAirSession;
	private $spCurrentOnAirUser;

	public function __construct(){
		$this->initialize();
	}

	/**
	 * Build constant procedure names
	 */
	private function initialize(){
		// Register failure procedure
		register_shutdown_function("Publisher::fatalHandler");
		
		$this->spOnAirLogin = "OnAirLogin";
		$this->spOnAirLogout = "OnAirLogout";
		$this->spUserLogin = "UserLogin";
		$this->spUserLogout = "UserLogout";
		$this->spAddUser = "AddUser";
		$this->spAddUserType = "AddUserType";
		$this->spGetUserFromName = "GetUserFromName";
		$this->spEndSession = "EndUserSession";
		$this->spCurrentOnAirUser = "GetCurrentOnAirUser";
	}

	public function addUser($user, $pass, $type, $first, $last){
		$salt = authUtil::makeSalt(saltSize);
		$hash = authUtil::makePassHash(hashAlgo, $salt, $user, $pass);
		$proc = "AddUser";
		$conn = new SqlConnect();
		$arr = array (
				$user,
				$hash,
				$salt,
				"NULL",
				$first,
				$last,
				$type);
		$results = $conn->callStoredProc($this->spAddUser, $arr);
	}

	public function login($user, $pass){
		
		// Connect to the database
		$conn = new SqlConnect();
		
		$results;
		// Get all information by username
		$results = $conn->callStoredProc($this->spGetUserFromName, array (
				$user ));
		
		// If username does not exist
		if ($results === true || $results === false) {
			echo json_encode(array (
					"error" => "Username and password did not match." ));
			//adding in user error logging, we want to keep track of failed logins.
			Publisher::publishUserError(0, 'Username and password did not match', 'UserManager.php -> login');
			session_unset();
			exit();
		}
		
		if (!$userinfo = mysqli_fetch_assoc($results)) {
			// error
		}
		
		// Get stored username, hashed password, and salt
		else {
			$user = $userinfo ['username'];
			$hash = $userinfo ['passwordhash'];
			$salt = $userinfo ['salt'];
		}
		
		// Verify supplied username hash and salt
		$success = authUtil::verifyPass(hashAlgo, $hash, $salt, $user, $pass);
		
		// Successful login, start a new session with all info
		if ($success) {
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			$_SESSION ['user'] = $userinfo ['username'];
			$_SESSION ['userid'] = $userinfo ['iduser'];
			$_SESSION ['usertype'] = $userinfo ['idusertype'];
			$_SESSION ['firstname'] = $userinfo ['firstname'];
			$_SESSION ['lastname'] = $userinfo ['lastname'];
			//Modified stored proc to only take in userID, since this is being done already in php.
			//rclabou - 3/26/2014
			$conn->freeResults();
			$results = $conn->callStoredProc($this->spUserLogin, array ($_SESSION ['userid']));
			if ($results === true || $results === false) {
				Publisher::publishException('UserManager : '.mysqli_connect_errno($conn), 'Session did not start correctly', $_SESSION['userid']);
			}
			else
			{
				$row = mysqli_fetch_assoc($results);
				$_SESSION ['sessionid'] = $row['ID'];
				$_SESSION['usertypename'] = $row['UserTypeName'];
				$_SESSION['plibraryview'] = $row['LibraryView'];
				$_SESSION['plibrarymanage'] = $row['LibraryManage'];
				$_SESSION['ppsaview'] = $row['PSAView'];
				$_SESSION['ppsamanage'] = $row['PSAEdit'];
				$_SESSION['pgrantview'] = $row['GrantView'];
				$_SESSION['pgrantedit'] = $row['GrantEdit'];
				$_SESSION['pmanageusers'] = $row['ManageUsers'];
				$_SESSION['pplaylistedit'] = $row['PlaylistEdit'];
				$_SESSION['ppermissionedit'] = $row['EditPermissions'];
				$_SESSION['peditusertype'] = $row['EditUserType'];
				$_SESSION['ponairsignon'] = $row['OnAirSignOn'];
			}			
			exit();
			
		}
		
		// Incorrect password for username
		else {
			echo json_encode(array (
					"error" => "Username and password did not match." ));
			session_unset();
		}
	}
	public static function endSession($sessionID)
	{
		$conn = new SqlConnect();
		
		$results;
		// Get all information by username
		$results = $conn->callStoredProc($this->spEndSession, array (
				$sessionID ));
		if ($results === true || $results === false) {
			Publisher::publishException($_SESSION['userid'], 'Session did not end correctly', 'UserManager -> (static) endSession');
		}
	}
	
	public function startOnAirSession()
	{
		$conn = new SqlConnect();
		//$results = $conn->callStoredProc($this->spOnAirLogin, array($_SESSION['userid']));
		//if ($results === true || $results === false) {
			//Publisher::publishException($_SESSION['userid'], 'OnAir Session did not start correctly', 'UserManager -> (static) startOnAirSession');
		//}
		$results = $conn->executeScalar($this->spOnAirLogin, array($_SESSION['userid']), 'OnAirID');
	}
	
	public static function endOnAirSession()
	{
		$conn = new SqlConnect();
		$results = $conn->callStoredProc($this->spOnAirLogoff, array($_SESSION['userid']));
		if ($results === true || $results === false) {
			Publisher::publishException($_SESSION['userid'], 'OnAir Session did not end correctly', 'UserManager -> (static) startOnAirSession');
		}
	}
	
	public static function getOnAirUser()
	{
		$conn = new SqlConnect();
		$results = $conn->callStoredProc($this->spCurrentOnAirUser, null);
		if ($results === true || $results === false) {
			Publisher::publishException($_SESSION['userid'], 'Session did not end correctly', 'UserManager -> (static) endSession');
			return false;
		}
		$row = mysqli_fetch_assoc($results);
		$ret = array();
		$ret['UserID'] = utf8_encode($row['UserID']);
		$ret['UserName'] = utf8_encode($row['UserName']);
		$ret['FirstName'] = utf8_encode($row['FirstName']);
		$ret['LastName'] = utf8_encode($row['LastName']);
		return ret;
	}
}

?>