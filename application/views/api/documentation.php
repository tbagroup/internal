w<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>API Documentation | internal.makerspace.se</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Stockholm Makerspace">

	<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	<style>.logo {margin: 40px 60px;}</style>
	
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
<div class="container">
	<div class="row">

		<div class="span10" style="text-align: center">	
			<a href="/"><img src="/assets/img/logo.png" alt="Stockholm Makerspace" class="logo"></a>
		</div>
		
		<div class="span6">
			
			<h1>API Documentation</h1>
			<p>REST API for Internal - Version <?php echo $version; ?></p>

			<h3>Information</h3>
			<p>Resources response-data is returned as JSON.<br>
			Standard HTTP Status Codes are used for all responses:</p>
			<ul>
				<li>200 = OK</li>
				<li>201 = Created</li>
				<li>202 = Accepted</li>
				<li>400 = Bad Request</li>
				<li>403 = Forbidden</li>
				<li>404 = Not found</li>
				<li>405 = Method Not Allowed</li>
				<li>409 = Conflict (Resource already exists)</li>
				<li>501 = Not Implemented</li>
				<li>503 = Service Unavailable</li>
			</ul>
			<p>Read more on Wikipedia - <a href="https://en.wikipedia.org/wiki/List_of_HTTP_status_codes">List of HTTP status codes</a></p>
		</div>
		
		<div class="span6">

			<h3>Public resources</h3>
			<ul>
				<li>GET /api/coffee</li>
			</ul>

			<h3>Protected resources (Requires authentication)</h3>
			<p>Use the HTTP Headers <strong>X-Email</strong> and <strong>X-Password</strong> to authenticate.<br>
			<em>Note: These resources also requires your account to be in the the "API" group.</em></p>

			<ul>
				<li>POST <a href="#api/auth">/api/auth</a> - Try authentication for member</li>
				<br>
				<li>GET <a href="#api/get_member">/api/get_member/*uid*</a> - Get member by id</li>
				<li>GET <a href="#api/get_member">/api/get_member/*key*/*value*</a> - Get member where key=value</li>
				<li>GET <a href="#api/get_member_groups">/api/get_member_groups/*uid*</a> - Get groups for member id</li>
				<br>
				<li>POST <a href="#api/add_member">/api/add_member</a> - Add a new member</li>
				<li>POST <a href="#api/update_member">/api/update_member/*uid*</a> - Update member by id</li>
				<br>
				<li>GET <a href="#api/get_groups">/api/get_groups</a> - Get all groups</li>
				<li>GET <a href="#api/get_group_members">/api/get_group_members/*gid*</a> - Get members in group by group id</li>
				<br>
				<li>GET <a href="#api/add_to_group">/api/add_to_group/*uid*/*group_name*</a> - Add a member to the specified group</li>
				<li>GET <a href="#api/remove_from_group">/api/remove_from_group/*uid*/*group_name*</a> - Remove a member from the specified group</li>
				
			</ul>
			
		</div>
	</div>
	<br>
	
	<div class="row">
		<div class="span12">
			<hr><h2>Resource documentation</h2><hr>
		</div>
		
		<div class="span12">
			<h3 id="api/auth">POST /api/auth</h3>
			<p>
				Try to authentication with provided credentials.<br>
				<strong>Required POST fields:</strong>
			</p>
			<ul>
				<li>email - The e-mail to try to authenticate with</li>
				<li>password - Password (sent as clear-text, not PBKDF2)</li>
			</ul>
			<p>
				Returns member object as JSON or HTTP 404 if authentication failed.<br>
				<em><strong>Optional:</strong> Provide a "X-Real-IP" HTTP header for the actual member if possible, for logging purposes.</em>
			</p>
		</div>
		
		<div class="span12">
			<h3 id="api/get_member">GET /api/get_member/*uid* and /*key*/*value*</h3>
			<p>
				Get member by ID. For example: <strong>/api/get_member/1000</strong> for member id 1000<br>
				Returns member object as JSON with all avaiable fields (NULL fields are NOT included) or HTTP 404 if not found.
			</p>
			<p>
				<strong>Optional:</strong> Supports key/value requests. For example: <strong>/api/get_member/email/test%40example.com</strong>
				<br><em>Note: This requires URL-encoded special chars as above (@ = %40)</em>
			<p>
		</div>
		
		<div class="span12">
			<h3 id="api/get_member_groups">GET /api/get_member_groups/*uid*</h3>
			<p>
				Get members groups by member ID. For example: <strong>/api/get_member_groups/1000</strong> for member id 1000<br>
				Returns all members groups as JSON object or HTTP 404 if no groups exists.
			</p>
		</div>
		
		<div class="span12">
			<h3 id="api/get_groups">GET /api/get_groups</h3>
			<p>
				Gets all available groups.<br>
				Returns a list of all groups names, their description and id as an JSON object.
			</p>
		</div>
		
		<div class="span12">
			<h3 id="api/get_group_members">GET /api/get_group_members/*gid*</h3>
			<p>
				Get a list of all members in a group, by the group id.<br>
				Returns list of all member id's in the group.
			</p>
		</div>
		
		<div class="span12">
			<h3 id="api/add_to_group">GET /api/add_to_group/*uid*/*group_name*</h3>
			<p>
				Add a members to a group by their member ID (*uid*). For example: <strong>/api/add_to_group/1000/labaccess</strong> adds member id 1000 to the group "labaccess"<br>
				Returns HTTP 200 (Ok) on success and HTTP 400 (Bad request) if the request failed (if the user already was in that group or similar).
			</p>
		</div>
		
		<div class="span12">
			<h3 id="api/remove_from_group">GET /api/remove_from_group/*uid*/*group_name*</h3>
			<p>
				Removes a members from a group by their member ID. For example: <strong>/api/remove_from_group/1000/labaccess</strong> removes member id 1000 from the group "labaccess"<br>
				Returns HTTP 200 (Ok) on success and HTTP 400 (Bad request) if the request failed (if the user already was in that group or similar).
			</p>
		</div>
		
		<div class="span12">
			<h3 id="api/add_member">POST /api/add_member</h3>
			<p>
				Adds a new member to the database<br>
				Returns full member object in JSON if successful, HTTP 409 Conflict if the member (e-mail) already exists and if there are missing fields or fields in wrong format - it returns 400 Bad Request.<br><br>
				<strong>Required POST fields:</strong>
			</p>
			<ul>
				<li>civicregno - Members civic reg. number (Format: YYYYMMDD-XXXX)</li>
				<li>email - The e-mail to try to authenticate with</li>
				<li>password - Password (As clear-text, NOT hashed)</li>
				<li>firstname - Members firstname</li>
				<li>lastname - Members lastname</li>
				<li>address - Members postal address</li>
				<li>zipcode - Members zipcode (XXXYY, no spaces)</li>
				<li>city - Members city</li>
			</ul>
			<p>
				<strong>Optional POST fields:</strong>
			</p>
			<ul>
				<li>address2 - Member address2 (C/O or similar)</li>
				<li>phone - Members phone number</li>
				<li>twitter - Members twitter name</li>
				<li>skype - Members skype account</li>
			</ul>
		</div>
		
		<div class="span12">
			<h3 id="api/update_member">POST /api/update_member/*uid*</h3>
			<p>
				Updates a existing member in the database, based upon their member ID.<br>
				Returns full member object in JSON if successful or HTTP 404 if request failed (for instance, if the member doesn't exists).<br>If anything else is wrong (fields format or similar), it returns HTTP 400 Bad Request.<br><br>
				<strong class="span6">See <a href="#api/add_member">/api/add_member</a> for available fields in this method.<br>Please note that ALL fields are optional. If a field isn't provided, it's NOT updated.</strong>
			</p>
		</div>
		
		
	</div>
</div>
<br>
</body>
</html>
