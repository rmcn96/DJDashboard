/**
 * Manages the profiles
 */

$('document').ready(function() {

	var getCurrentUsersProfile = function() {
		$.ajax({
			type : "GET",
			url : "../php/scripts/getCurrentUserProfile.php"
		}).done(function(data) {
			var profile;
			try {
				profile = JSON.parse(data);
			} catch (e) {
				console.log(data);
				return;
			}
			var djNickName = profile['NickName'];
			var djMotto = profile['Motto'];
			var djBio = profile['Bio'];
			for ( var i = 0; i < profile.length; i++) {

			}

		});
	}

	var getFivePlaylists = function() {
		$.ajax({
			type : "GET",
			url : "../php/scripts/GetProfilePlaylists.php"
		}).done(function(data) {
			var profile;
			try {
				playlists = JSON.parse(data);
			} catch (e) {
				console.log(data);
				return;
			}
			for ( var i = 0; i < playlists.length; i++) {
				for ( var j = 0; j < playlists[i].length; j++) {
					var trackName = playlists[i][j]['TrackName'];
					console.log(playlists[i][j]);
					console.log(playlists[i][j]['Artist']);
					var artistName = playlists[i][j]['Artist']['ArtistName'];
				}
			}
			var playlistHTML = '';

		});

		/***********************************************************************
		 * <div class="panel-body">
		 * <ul>
		 * <li><a href="#">Song One</a></li>
		 * <li><a href="#">Song Two</a></li>
		 * <li><a href="#">Song Three</a></li>
		 * <li><a href="#">Song Four</a></li>
		 * <li><a href="#">Song Five</a></li>
		 * </ul>
		 * </div>
		 **********************************************************************/
	}

	getCurrentUsersProfile();
	getFivePlaylists();
});
