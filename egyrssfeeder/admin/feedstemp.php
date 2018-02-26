<?php
	//if(!empty($_GET['feed'])){
		//var_dump($egyrssfeeder_options[$_GET['feed']]);
	//}
	echo "<div>
    <form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\">
	<input type=\"hidden\" name=\"egycount\" value=\"".$egyrssfeeder_options['count']."\"><br>
            <div>
                <div>
                    <div><br></div>
                    <h3><span>General Options</span></h3>
                    <div class=\"inside\">
					<label><b>Title</b></label>
            <div id=\"titlediv\">
                <input type=\"text\" name=\"egytitle\" size=\"80\" id=\"title\" value=\"";
							if(isset($_GET['feed'])){
								echo $egyrssfeeder_options[$_GET['feed']]['title'];
							}
							echo "\" />
            </div>
                        <label><b>Feed URL</b></label>
                        <div>
                            <input name=\"egysurl\" size=\"100\" value=\"";
							if(isset($_GET['feed'])){
								echo $egyrssfeeder_options[$_GET['feed']]['url'];
							}
							echo "\"/>
                        </div>
                        <table width=\"100%\">
                            <tr>
                                <td>
                                    <div>
                                        <label><b>Default Author</b></label>
                                        <div><select name=\"author\">";
										//echo wp_dropdown_users(array('selected'=>get_current_user_id(), 'name'=>'author', 'id'=>'egyrssfeed'));
										$blogusers = get_users( array( 'fields' => array( 'display_name' , 'id') ) );
										foreach ( $blogusers as $user ) {
											echo "<option value=\"".esc_html( $user->id )."\" ";
											if((isset($_GET['feed'])) && ($user->id == $egyrssfeeder_options[$_GET['feed']]['author'])){
												echo " selected=\"selected\" ";
											}
											echo ">".esc_html( $user->display_name ) ."</option>";
										}
                                     echo "</select></div>
                                    </div>

                                    <div>
                                        <label><b>Default Status</b></label>
                                        <div>
                                            <select name=\"status\">";
                                                    $stateop = get_post_statuses();
													foreach ( $stateop as $k=>$statue ) {
														echo "<option value=\"".$k."\" ";
														if((isset($_GET['feed'])) && ($k == $egyrssfeeder_options[$_GET['feed']]['status'])){
															echo " selected=\"selected\" ";
														}
														echo ">".$statue."</option>";
													}
                                            echo "</select>
                                        </div>
                                    </div>

                                    <div>
                                        <label><b>Post type</b></label>
                                        <div>
                                            <select name=\"type\">";
													$posttype = get_post_types();
                                                    foreach ( $posttype as $ptypes ) {
														echo "<option value=\"".$ptypes."\"";
														if((isset($_GET['feed'])) && ($ptypes == $egyrssfeeder_options[$_GET['feed']]['type'])){
															echo " selected=\"selected\" ";
														}
														echo ">".$ptypes."</option>";
													}
                                            echo "</select>
                                        </div>
                                    </div>

                                    <div>
                                        <label><b>Update frequency</b></label>
                                        <div>
                                            <select name=\"ufrequency\">";
											$tarray = array("600"=>"10 minutes" , "1200"=>"20 minutes" , "1800"=>"30 minutes" , "3600"=>"1 hour" , "7200"=>"2 hours" , "14400"=>"4 hours" , "28800"=>"8 hours" , "57600"=>"16 hours" , "86400"=>"1 day");
											foreach ( $tarray as $tk=>$tval ) {
													echo "<option value=\"".$tk."\"";
													if((isset($_GET['feed'])) && ($tk == $egyrssfeeder_options[$_GET['feed']]['ufrequency'])){
														echo " selected=\"selected\" ";
													}
													echo ">".$tval."</option>";
											}
                                            echo "</select>
                                        </div>
                                    </div>
                                </td>
								<tr>
								<td><div><label><b>category</b></label>
								<ul>";
								if(isset($_GET['feed'])){
									wp_dropdown_categories("selected=".$egyrssfeeder_options[$_GET['feed']]['cat']);
								}else{
									wp_dropdown_categories();
								}
								echo "</ul></div>
                                    <div>
                                        <p><label><input type=\"checkbox\" value=\"1\" name=\"autoupdate\" ";
										if((isset($_GET['feed'])) && ($egyrssfeeder_options[$_GET['feed']]['autoupdate']) == 1){
											echo "checked ";
										}
										echo "/>Auto update </label>
                                    </div>
                                    <div>
                                        <p><label><input type=\"checkbox\" value=\"1\" name=\"useimages\" ";
										if((isset($_GET['feed'])) && ($egyrssfeeder_options[$_GET['feed']]['useimages']) == 1){
											echo "checked ";
										}
										echo "/>Use images </label>
                                    </div>
									<div>
                                        <p><label><input type=\"checkbox\" value=\"1\" name=\"useframe\" ";
										if((isset($_GET['feed'])) && ($egyrssfeeder_options[$_GET['feed']]['useframe']) == 1){
											echo "checked ";
										}
										echo "/>Use iFrame </label>
                                    </div>
                                    <div>
                                        <p><label><input type=\"checkbox\" value=\"1\" name=\"overwriteposts\" ";
										if((isset($_GET['feed'])) && ($egyrssfeeder_options[$_GET['feed']]['overwriteposts']) == 1){
											echo "checked ";
										}
										echo " />Overwrite posts </label>
                                    </div>

                                    <div>
                                        <label><b>Limit number of posts </b></label>
                                        <div>
                                            <input size=\"3\" name=\"postslimit\" value=\"";
											if(isset($_GET['feed'])){
												echo $egyrssfeeder_options[$_GET['feed']]['postslimit'];
											}else{
												echo "50";
											}
											echo "\" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
						</table>
						<div>
							<input type=\"submit\" name=\"Submit\" class=\"button\" value=\"Save\" />
						</div>
    </form>
</div>";
?>