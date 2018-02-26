<?php

function manage_egyrssfeeder() {
    $egyrssfeeder_options = getdata();
	//var_dump($egyrssfeeder_options);
	echo "<table style=\"width:100%\">
			<caption><h3>Your Feeds</h3></caption>
			<tr>
				<th>Title</th>
				<th>Auto update</th>
				<th>Author</th>
				<th>Date</th>
			</tr>";

	foreach ($egyrssfeeder_options as $key => $value){
		if($key == 'count'){
			if($value < 1){
				echo "<tr><td align=\"center\">No Feeds Sources ".$value."</td><td></td><td></td></tr><br />";
			}
			continue;
			//echo "this is count : ".$value."<br />";
		}else{
			//echo "this is key : ".$key."<br />";
			//var_dump($value);
			echo "<tr><td align=\"center\"><a href=\"".menu_page_url('egyrssfeeder_feed',false).'&action=edit&feed='.$key."\">".$value['title']."</a><br />
			        <a href=\"".menu_page_url('egyrssfeeder_feed',false).'&action=run&feed='.$key."\">Run</a>|
					<a href=\"".menu_page_url('egyrssfeeder_feed',false).'&action=delete&feed='.$key."\">Delete</a>|
					<a href=\"".menu_page_url('egyrssfeeder_feed',false).'&action=edit&feed='.$key."\">Edit</a></td>
					<td align=\"center\">".$value['autoupdate']."</td>
					<td align=\"center\">".get_userdata($value['author'])->user_login."</td>
					<td align=\"center\">".$value['date']."</td>
					</tr><br />";	
		}
	}
	echo "</table>";
}  
manage_egyrssfeeder();
?>