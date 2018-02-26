<?php

function add_feed_egyrssfeeder() {
	$egyrssfeeder_options = getdata();
	//var_dump($egyrssfeeder_options);
	if(empty($_GET['action'])) {
    //submitting the data
		if(!empty($_POST['Submit'])) {
			checkpost($egyrssfeeder_options);
		}
		include("feedstemp.php");
	}else{
		switch (addslashes($_GET['action'])){
			case "edit":
				if(empty($_GET['feed'])){
					echo "error in edit no feed index";
				}else{
					if(!empty($_POST['Submit'])) {
						checkpost($egyrssfeeder_options);
					}
					include("feedstemp.php");
				}
				break;
			case "run":
				if(empty($_GET['feed'])){
					echo "error in run no feed index";
				}else{
					$egyrssfeederss = getdata();
					rssfeed($egyrssfeeder_options[$_GET['feed']]);
				}
				break;
			case "delete":
				if(empty($_GET['feed'])){
					echo "error in delete no feed index";
				}else{
					unset($egyrssfeeder_options[$_GET['feed']]);
					if($egyrssfeeder_options['count'] > 0){
						$egyrssfeeder_options['count'] = $egyrssfeeder_options['count'] - 1;
					}
					savedata($egyrssfeeder_options);
					echo "feed has been deleted";
				}
				break;
		}
	}
}  

function checkpost($egyrssfeeder_options){
	if(isset($_POST['useimages'])){
				$useimages = 1;
			}else{
				$useimages = 0;
			}
			if(isset($_POST['overwriteposts'])){
				$overwriteposts = 1;
			}else{
				$overwriteposts = 0;
			}
			if(isset($_POST['autoupdate'])){
				$autoupdate = 1;
			}else{
				$autoupdate = 0;
			}
			if(isset($_POST['useframe'])){
				$useframe = 1;
			}else{
				$useframe = 0;
			}
			if(isset($_GET['feed'])){
				$egyrssfeeder_options['count'] = addslashes($_POST['egycount']);
			}else{
				$egyrssfeeder_options['count'] = addslashes($_POST['egycount']) + 1;
			}
			$egyrssfeeder_feeds = array("title"=>addslashes($_POST['egytitle']) , 
				"url"=>addslashes($_POST['egysurl']), 
				"author"=>addslashes($_POST['author']), 
				"status"=>addslashes($_POST['status']), 
				"type"=>addslashes($_POST['type']), 
				"ufrequency"=>addslashes($_POST['ufrequency']),
				"cat"=>addslashes($_POST['cat']),
				"useimages"=>addslashes($useimages),
				"overwriteposts"=>addslashes($overwriteposts),
				"postslimit"=>addslashes($_POST['postslimit']),
				"useframe"=>addslashes($useframe),
				"autoupdate"=>addslashes($autoupdate),
				"date"=>date('Y/m/j')
				);
			if(isset($_GET['feed'])){
				$egyrssfeeder_options[$_GET['feed']] = $egyrssfeeder_feeds;
				$relink = "<meta http-equiv=\"refresh\" content=\"0; url=".menu_page_url('egyrssfeeder_feed',false)."&action=edit&feed=".addslashes($_GET['feed'])."\" />";
			}else{
				$egyrssfeeder_options['feed'.$egyrssfeeder_options['count']] = $egyrssfeeder_feeds;
				$relink = "<meta http-equiv=\"refresh\" content=\"0; url=".menu_page_url('egyrssfeeder_feed',false)."&action=edit&feed="."feed".$egyrssfeeder_options['count']."\" />";
			}
			savedata($egyrssfeeder_options);
			echo $relink;
}

function rssfeed($egyarray){
	$EGYrss = simplexml_load_file($egyarray['url'], 'SimpleXMLElement', LIBXML_NOWARNING);
	if(($egyarray['postslimit'] == "0") || ($EGYrss == False)){
		echo "there is Something wrong";
		return;
	}else{
		$i = 0;
	}
	echo '<h1>'. $EGYrss->channel->title . '</h1>';
	foreach ($EGYrss->channel->item as $item) {
		if($i < $egyarray['postslimit']){
			inspost($egyarray,$item);
			$i++;
		}else{
			echo "this the end of feeds fitched ".$i;
			return ;
		}
	}
}

function inspost($egyarray,$item){
	$postID = post_exists($item->title); // check if post is inserted in wordpress database
	//var_dump($item);
	if($postID != 0){
		echo "Not stored in wordpress";
	}else{
		echo "this is the ".$postID;
	}
	echo '<h2><a href="'. $item->link .'">' . $item->title . "</a></h2>";
	echo "<p>" . $item->pubDate . "</p>";
	echo "<p>" . $item->description . "</p>";
	$wpcont = $item->description;
	echo "<p>" . $item->guid . "</p>";
	if($egyarray['useimages'] == "1"){
		$wpcont = $wpcont."<img src=\"".$item->enclosure['url'][0]."\"></img><br />";
		echo "<img src=\"".$item->enclosure['url'][0]."\"></img><br />";
	}
	if($egyarray['useframe'] == "1"){
		$wpcont = "<iframe height=\"400\" width=\"800\" src=".$item->link."></iframe>";
		echo "<iframe height=\"400\" width=\"800\" src=".$item->link."></iframe>"; 
	}
	if($egyarray['overwriteposts'] != "1"){
		$postID = 0;
	}
	// Create post object
	$my_post = array(
		'ID'   => $postID,
		'post_type' => $egyarray['type'],
		'post_title'    => wp_strip_all_tags((string) $item->title),
		//'post_content'  => (string) $item->description,
		'post_content'  => $wpcont,
		'post_status'   => $egyarray['status'],
		'post_author'   => $egyarray['author'],
		'post_category' => array($egyarray['cat'])
	);
 
	// Insert the post into the database
	wp_insert_post( $my_post );
}

add_feed_egyrssfeeder()
?>
