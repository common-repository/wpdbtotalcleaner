<?php
	if (!current_user_can('manage_options')) return;
	global $wpdb;
 	$sql="SELECT substring(md5(blog_id),1,3) as hash, blog_id FROM {$wpdb->blogs} ORDER BY blog_id ASC";
	$blogs = $wpdb->get_results($sql);
	if ( is_multisite() ) {
	$network_type="Wordpress Multisite network with " . count($blogs) . " sites";} else
	{
	$network_type="Single Wordpress site";
	}
	// Want To Delete Un-approved Comments?
	$newdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
	if($_POST['DeleteUnApproved']){
		wp_cache_flush(); $unapprovedmessage = 'No comments Available!';
		foreach($blogs as $details) {
			$blog_id = 	$details->blog_id;
			$table_comments = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_comments";
			// Then Delete Those!
			$query = $newdb->query("DELETE FROM " . $table_comments . " WHERE comment_approved = '0';");
					// Show Confirmation Upon Successful Delete Query
			if($query)
				{$unapprovedmessage = 'Deleted Successfully!';$query = $newdb->query("OPTIMIZE TABLE  " . $table_comments . ";");}
			// Else Show No Comment Available Message
		}
	}
	
	// Want To Delete Approved Comments?
	if($_POST['DeleteApproved']){
		wp_cache_flush();$approvedmessage = 'No Comments Available!';
		foreach($blogs as $details) {
			$blog_id = 	$details->blog_id;
			$table_comments = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_comments";
			// Then Delete Those!
			$query = $newdb->query("DELETE FROM " . $table_comments . " WHERE comment_approved = '1';");
			// Show Confirmation Upon Successful Delete Query
			if($query)
				{$approvedmessage = 'Deleted Successfully!';$query = $newdb->query("OPTIMIZE TABLE  " . $table_comments . ";");}
			// Else Show No Comment Available Message
		}
	}

	// Want To Delete SPAM Comments?
	if($_POST['DeleteSPAM']){
		wp_cache_flush();$spammessage = 'No Comments Available!';
		foreach($blogs as $details) {
			$blog_id = 	$details->blog_id;
			$table_comments = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_comments";
			// Then Delete Those!
			$query = $newdb->query("DELETE FROM " . $table_comments . " WHERE comment_approved = 'spam';");
			// Show Confirmation Upon Successful Delete Query
			if($query)
				{$spammessage = 'Deleted Successfully!';$query = $newdb->query("OPTIMIZE TABLE  " . $table_comments . ";");}
			// Else Show No Comment Available Message
		}		
	}
	// Want To Empty Comments Agent?
	if($_POST['DeleteCommentsAgent']){
		wp_cache_flush();$commentsagentmessage = 'No Comments Available!';
		foreach($blogs as $details) {
			$blog_id = 	$details->blog_id;
			$table_comments = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_comments";
			// Then Delete Those!
			$query = $newdb->query("UPDATE " . $table_comments . " set comment_agent ='' ;");
			// Show Confirmation Upon Successful Delete Query
			if($query)
				{$commentsagentmessage = 'Empty agents Successfully!';$query = $newdb->query("OPTIMIZE TABLE  " . $table_comments . ";");}
			// Else Show No Comment Available Message
		}		
	}

	// Want To Delete unlinked commentmeta rows?
	if($_POST['DeleteUnlinked']){
		wp_cache_flush();$unlinkedmessage = 'No unlinked rows!';
		foreach($blogs as $details) {
			$blog_id = 	$details->blog_id;
			$table_comments = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_comments";
			$table_commentmeta = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_commentmeta";			
			$query = $newdb->query("DELETE FROM " . $table_commentmeta . " WHERE comment_id NOT IN (SELECT comment_id FROM " . $table_comments . ");");
			// Show Confirmation Upon Successful Delete Query
			if($query)
			{$unlinkedmessage = 'Deleted Successfully!';$query = $newdb->query("OPTIMIZE TABLE  " . $table_commentmeta . ";");}
			// Else Show No unlinked rows
		}		
	}	
	// Want To Delete Akismet commentmeta rows?
	if($_POST['DeleteAkismet']){
		wp_cache_flush();$akismetmessage = 'No Akismet rows!';
		foreach($blogs as $details) {
			$blog_id = 	$details->blog_id;
			$table_commentmeta = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_commentmeta";			
			$query = $newdb->query("DELETE FROM " . $table_commentmeta . " WHERE meta_key LIKE '%akismet%';");
			// Show Confirmation Upon Successful Delete Query
			if($query)
			{$akismetmessage = 'Deleted Successfully!';$query = $newdb->query("OPTIMIZE TABLE  " . $table_commentmeta . ";");}
			// Else Show No Akismet Rows
		}		
	}	
	// Want To Delete Transient orphan options?
	if($_POST['DeleteTransient']){
		wp_cache_flush();$transientmessage = 'No Transient rows!';
		foreach($blogs as $details) {
			$blog_id = 	$details->blog_id;
			$table_options = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_options";			
			$query = $newdb->query("DELETE FROM " . $table_options . " WHERE option_name LIKE ('_transient_%');");
			// Show Confirmation Upon Successful Delete Query
			if($query)
			{$transientmessage = 'Deleted Successfully!';$query = $newdb->query("OPTIMIZE TABLE  " . $table_options . ";");}
			// Else No Transient rows
		}		
	}	
	// Want To Delete Transient feed orphan options?
	if($_POST['DeleteTransient_Feed']){
		wp_cache_flush();$transientfeedmessage = 'No Transient_feed rows!';
		foreach($blogs as $details) {
			$blog_id = 	$details->blog_id;
			$table_options = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_options";			
			$query = $newdb->query("DELETE FROM " . $table_options . " WHERE option_name LIKE ('_transient%_feed_%');");
			// Show Confirmation Upon Successful Delete Query
			if($query)
			{$transientfeedmessage = 'Deleted Successfully!';$query = $newdb->query("OPTIMIZE TABLE  " . $table_options . ";");}
			// Else No Transient_Feed rows
		}		
	}	
	// Want To Delete Revisions?
	if($_POST['DeleteRevisions']){
		wp_cache_flush();$revisionsmessage = 'No Revisions!';
		foreach($blogs as $details) {
			$blog_id = 	$details->blog_id;
			$table_posts = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_posts";
			$table_term_relationships = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_term_relationships";
			$table_postmeta = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_postmeta";
			$query = $newdb->query("DELETE a,b,c FROM " . $table_posts . " a LEFT JOIN " . $table_term_relationships . " b ON (a.ID = b.object_id) LEFT JOIN " . $table_postmeta . " c ON (a.ID = c.post_id) WHERE a.post_type = 'revision';");
			// Show Confirmation Upon Successful Delete Query
			if($query)
			{
			$revisionsmessage = 'Deleted Successfully!';
			$query = $newdb->query("OPTIMIZE TABLE  " . $table_posts . ";");
			$query = $newdb->query("OPTIMIZE TABLE  " . $table_term_relationships . ";");
			$query = $newdb->query("OPTIMIZE TABLE  " . $table_postmeta . ";");
			}
			// Else No Revisions
		}		
	}	
	// Want To Delete Drafts?
	if($_POST['DeleteDrafts']){
		wp_cache_flush();$draftsmessage = 'No Drafts!';
		foreach($blogs as $details) {
			$blog_id = 	$details->blog_id;
			$table_posts = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_posts";
			$table_term_relationships = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_term_relationships";
			$table_postmeta = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_postmeta";
			$query = $newdb->query("DELETE a,b,c FROM " . $table_posts . " a LEFT JOIN " . $table_term_relationships . " b ON (a.ID = b.object_id) LEFT JOIN " . $table_postmeta . " c ON (a.ID = c.post_id) WHERE a.post_status = 'draft';");
			// Show Confirmation Upon Successful Delete Query
			if($query)
			{
			$draftsmessage = 'Deleted Successfully!';
			$query = $newdb->query("OPTIMIZE TABLE  " . $table_posts . ";");
			$query = $newdb->query("OPTIMIZE TABLE  " . $table_term_relationships . ";");
			$query = $newdb->query("OPTIMIZE TABLE  " . $table_postmeta . ";");
			}
			// Else No Drafts
		}		
	}	
	// Want To Delete Unused Tags?
	if($_POST['DeleteTags']){
		wp_cache_flush();$tagsmessage = 'No Unused Tags!';
		foreach($blogs as $details) {
			$blog_id = 	$details->blog_id;
			$table_terms = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_terms";
			$table_term_taxonomy = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_term_taxonomy";
			$table_term_relationships = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_term_relationships";
			$query = $newdb->query("DELETE FROM " . $table_terms . " WHERE term_id IN (SELECT term_id FROM " . $table_term_taxonomy . " WHERE count = 0 );");
			// Show Confirmation Upon Successful Delete Query
			if($query)
			$tagsmessage = 'Deleted Successfully!';
			$query = $newdb->query("DELETE FROM " . $table_term_taxonomy . " WHERE term_id not IN (SELECT term_id FROM " . $table_terms . ");");
			// Show Confirmation Upon Successful Delete Query
			if($query)
			$tagsmessage = 'Deleted Successfully!';
			$query = $newdb->query("DELETE FROM " . $table_term_relationships . " WHERE term_taxonomy_id not IN (SELECT term_taxonomy_id FROM " . $table_term_taxonomy . ");");
			// Show Confirmation Upon Successful Delete Query
			if($query)
			{
			$tagsmessage = 'Deleted Successfully!';
			$query = $newdb->query("OPTIMIZE TABLE  " . $table_terms . ";");
			$query = $newdb->query("OPTIMIZE TABLE  " . $table_term_taxonomy . ";");
			$query = $newdb->query("OPTIMIZE TABLE  " . $table_term_relationships . ";");
			}
			// Else No Revisions
		}		
	}
	foreach($blogs as $details) {
		$blog_id = 	$details->blog_id;
		$table_comments = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_comments";
		$table_commentmeta = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_commentmeta";
		$table_options = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_options";
		$table_posts = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_posts";
		$table_term_relationships = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_term_relationships";
		$table_postmeta = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_postmeta";
		$table_terms = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_terms";
		$table_term_taxonomy = substr( $wpdb->get_blog_prefix( $blog_id ), 0, -1 ) . "_term_taxonomy";
		// Comments rows
		$unapprovedcount = $newdb->query("SELECT * FROM " . $table_comments . " WHERE comment_approved = '0';");
		$unapprovedcomments = $unapprovedcomments + $unapprovedcount;				
		// Get SPAM Comments Count
		$spamcount = $newdb->query("SELECT * FROM " . $table_comments . " WHERE comment_approved = 'spam';");
		$spamcomments = $spamcomments + $spamcount;				
		// Get Approved Comments Count
		$approvedcount= $newdb->query("SELECT * FROM " . $table_comments . " WHERE comment_approved = '1';");
		$approvedcomments = $approvedcomments + $approvedcount;		
		// Get Comments Agent
		$commentagentcount= $newdb->query("SELECT * FROM " . $table_comments . " WHERE comment_agent<>'';");
		$commentagent = $commentagent + $commentagentcount;				
		// Commentmeta unlinked rows
		$unlinkcount = $newdb->query("SELECT * FROM " . $table_commentmeta . " WHERE comment_id NOT IN (SELECT comment_id FROM " . $table_comments . ");");
		$unlink_commentmeta = $unlink_commentmeta + $unlinkcount;
		// Commentmeta Akismet rows
		$akismetcount = $newdb->query("SELECT * FROM " . $table_commentmeta . " WHERE meta_key LIKE '%akismet%';");
		$akismet_commentmeta = $akismet_commentmeta + $akismetcount;
		// Options _transient_ orphan rows
		$transientcount = $newdb->query("SELECT * FROM " . $table_options . " WHERE option_name LIKE '_transient_%';");
		$transient_options = $transient_options + $transientcount;
		// Options _transient_feeds orphan rows
		$transientfeedcount = $newdb->query("SELECT * FROM " . $table_options . " WHERE option_name LIKE ('_transient%_feed_%');");
		$transient_feed_options = $transient_feed_options + $transientfeedcount;
		// Revisions rows
		$revisionscount = $newdb->query("SELECT * FROM " . $table_posts . " a LEFT JOIN " . $table_term_relationships . " b ON (a.ID = b.object_id) LEFT JOIN " . $table_postmeta . " c ON (a.ID = c.post_id) WHERE a.post_type = 'revision';");
		$revisions = $revisions + $revisionscount;
		// Drafts rows
		$draftscount = $newdb->query("SELECT * FROM " . $table_posts . " a LEFT JOIN " . $table_term_relationships . " b ON (a.ID = b.object_id) LEFT JOIN " . $table_postmeta . " c ON (a.ID = c.post_id) WHERE a.post_status = 'draft';");
		$drafts = $drafts + $draftscount;
		// Unused Tags
		$tagscount = $newdb->query("SELECT * FROM " . $table_terms . " WHERE term_id IN (SELECT term_id FROM " . $table_term_taxonomy . " WHERE count = 0 );");
		$tags = $tags + $tagscount;
		$tagscount = $newdb->query("SELECT * FROM " . $table_term_taxonomy . " WHERE term_id not IN (SELECT term_id FROM " . $table_terms . ");");
		$tags = $tags + $tagscount;
		$tagscount = $newdb->query("SELECT * FROM " . $table_term_relationships . " WHERE term_taxonomy_id not IN (SELECT term_taxonomy_id FROM " . $table_term_taxonomy . ");");
		$tags = $tags + $tagscount;

	}	

	$rows = mysql_query("SHOW table STATUS"); $dbsize = 0;
	while ($row = mysql_fetch_array($rows)) 
		{$dbsize += $row['Data_length'] + $row['Index_length']; } 
	$dbsize = $dbsize / 1048576;
	// Visual Stuffs Of Plugin Admin Page
	print '<script LANGUAGE="JavaScript">
function confirmWPDBCleaner(typE){
	var agree=confirm("Are you sure to clear under "+typE+" criteria. Proceed?");
	if (agree)
		return true;
	else
		return false;
}
</script>

<div class="wrap">
	<div class="WPDBCleanerHeading">Wordpress Database Total Cleaner</div>
	<div><strong>IMPORTANT:</strong> Before using this plugin make a backup of your Database and inform site administrators. Otherwise, There is no guarantee for your database.</div>
	<div id="WPDBCleaner">
		<form method="post" action="">
			<table>
				<thead>
					<tr>
						<th>Status</th>
						<th>Total rows</th>
						<th class="nopadding">Action</th>
						<th>Result</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Un-Approved Comments:</td>
						<th>'.$unapprovedcomments.'</th>
						<td class="nopadding">
							<input type="submit" onclick="return confirmWPDBCleaner(\'Un-Approved\')" name="DeleteUnApproved" class="WPDBCleanerInput" value="DELETE" />
						</td>
						<td class="fixedwidth">'.$unapprovedmessage.'</td>
					</tr>
					<tr>
						<td>SPAM Comments:</td>
						<th>'.$spamcomments.'</th>
						<td class="nopadding">
							<input type="submit" onclick="return confirmWPDBCleaner(\'SPAM\')" name="DeleteSPAM" class="WPDBCleanerInput" value="DELETE">
						</td>
						<td class="fixedwidth">'.$spammessage.'</td>
					</tr>
					<tr>
						<td>Approved Comments:</td>
						<th>'.$approvedcomments.'</th>
						<td class="nopadding">
							<input type="submit" onclick="return confirmWPDBCleaner(\'Approved\')" name="DeleteApproved" class="WPDBCleanerInput" value="DELETE">
						</td>
						<td class="fixedwidth">'.$approvedmessage.'</td>
					</tr>
					<tr>
						<td>Comments agent:</td>
						<th>'.$commentagent.'</th>
						<td class="nopadding">
							<input type="submit" onclick="return confirmWPDBCleaner(\'CommentsAgent\')" name="DeleteCommentsAgent" class="WPDBCleanerInput" value="EMPTY">
						</td>
						<td class="fixedwidth">'.$commentsagentmessage.'</td>
					</tr>
					<tr>
						<td>Unlinked commentmeta:</td>
						<th>'.$unlink_commentmeta.'</th>
						<td class="nopadding">

							<input type="submit" onclick="return confirmWPDBCleaner(\'Unlinked\')" name="DeleteUnlinked" class="WPDBCleanerInput" value="DELETE">
						</td>
						<td class="fixedwidth">'.$unlinkedmessage.'</td>

					</tr>
					<tr>
						<td>Akismet commentmeta:</td>
						<th>'.$akismet_commentmeta.'</th>
						<td class="nopadding">

							<input type="submit" onclick="return confirmWPDBCleaner(\'Akismet\')" name="DeleteAkismet" class="WPDBCleanerInput" value="DELETE">
						</td>
						<td class="fixedwidth">'.$akismetmessage.'</td>

					</tr>
					<tr>
						<td>_transient_ options:</td>
						<th>'.$transient_options.'</th>
						<td class="nopadding">

							<input type="submit" onclick="return confirmWPDBCleaner(\'Transient\')" name="DeleteTransient" class="WPDBCleanerInput" value="DELETE">
						</td>
						<td class="fixedwidth">'.$transientmessage.'</td>

					</tr>
					<tr>
						<td>_transient_feed options:</td>
						<th>'.$transient_feed_options.'</th>
						<td class="nopadding">

							<input type="submit" onclick="return confirmWPDBCleaner(\'Transient_Feed\')" name="DeleteTransient_Feed" class="WPDBCleanerInput" value="DELETE">
						</td>
						<td class="fixedwidth">'.$transientfeedmessage.'</td>

					</tr>
					<tr>
						<td>Revisions:</td>
						<th>'.$revisions.'</th>
						<td class="nopadding">

							<input type="submit" onclick="return confirmWPDBCleaner(\'Revisions\')" name="DeleteRevisions" class="WPDBCleanerInput" value="DELETE">
						</td>
						<td class="fixedwidth">'.$revisionsmessage.'</td>

					</tr>
					<tr>
						<td>Drafts:</td>
						<th>'.$drafts.'</th>
						<td class="nopadding">

							<input type="submit" onclick="return confirmWPDBCleaner(\'Drafts\')" name="DeleteDrafts" class="WPDBCleanerInput" value="DELETE">
						</td>
						<td class="fixedwidth">'.$draftsmessage.'</td>

					</tr>
					<tr>
						<td>Unused Tags/Categories:</td>
						<th>'.$tags.'</th>
						<td class="nopadding">

							<input type="submit" onclick="return confirmWPDBCleaner(\'UnusedTags\')" name="DeleteTags" class="WPDBCleanerInput" value="DELETE">
						</td>
						<td class="fixedwidth">'.$tagsmessage.'</td>

					</tr>
					<tr>
						<td>Wordpress type:</td>
						<th colspan="3">'.$network_type.'</th>
					</tr>
					<tr>
						<td>DataBASE size:</td>
						<th colspan="3">'.(int) $dbsize.' MB</th>
					</tr>

				<tbody>
			</table>
		</form>
	</div>
</div>
<div style="padding:5px;float:left;"><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="6FGWKZDHK8PR6">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<p>Donate to help us to create more free projects.</p>
</div>
<div style="float:left;padding:10px;">Visit plugin <a href="http://bgextensions.bgvhod.com">home</a></div>';
?>