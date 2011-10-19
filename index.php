<?php
/*
Plugin Name: Game Server Tracker
Plugin URI: http://hannawerner.com/wordpress/game-server-tracker/
Description: Gets current stats of a Game Server from GameTracker.com and displays them in the sidebar. Some of the games this tracker works on are: Battlefield 3 [BETA], Battlefield Bad Company 2, Call of Duty 2, Call of Duty 4, Call of Duty : Black Ops, Counter Strike 1.6, Counter Strike Source, Day of Defeat Source, Left 4 Dead 2, Medal of Honor, Minecraft, Team Fortress 2 and Wolfenstein Enemy Territory.
Version: 1.0
License: GPLv2
Author: Hanna Camille Werner
Author URI: http://www.hannawerner.com
*/

class hcw_gameserver_tracker extends WP_Widget {
 
 
    /** constructor -- name this the same as the class above */
    function hcw_gameserver_tracker() {
        parent::WP_Widget(false, $name = 'Game Server Tracker');
    }
 
    /** @see WP_Widget::widget -- do not rename this */

    function widget($args, $instance) {
        extract( $args );
        $title 		= apply_filters('widget_title', $instance['title']);
        $serverip 	= $instance['message'];
   /**  $clanname 	= $instance['messagetwo']; **/
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>


<!------------------------------------->

<?php

function textbetweenarray($s1,$s2,$s){
  $myarray=array();
  $s1=strtolower($s1);
  $s2=strtolower($s2);
  $L1=strlen($s1);
  $L2=strlen($s2);
  $scheck=strtolower($s);

  do{
  $pos1 = strpos($scheck,$s1);
  if($pos1!==false){
    $pos2 = strpos(substr($scheck,$pos1+$L1),$s2);
    if($pos2!==false){
      $myarray[]=substr($s,$pos1+$L1,$pos2);
      $s=substr($s,$pos1+$L1+$pos2+$L2);
      $scheck=strtolower($s);
      }
        }
  } while (($pos1!==false)and($pos2!==false));
return $myarray;
}

$content = file_get_contents("http://www.gametracker.com/server_info/$serverip");

$servergame     = "<strong>Game:</strong> ";
$playerstats    = "<strong>Players Online:</strong> ";
$serverstatus   = "<strong>Status:</strong> ";
$currentmap     = "<strong>Current Map:</strong> ";
$serverclan     = "<strong>Clan:</strong> ";
/** $members        = "<strong>Members:</strong> "; **/

$nextline       = "<br />";

$trServerName = textbetweenarray("<span class=\"item_color_title\">Name:</span>", "<br/>", $content);
$trServerGame = textbetweenarray("<span class=\"item_color_title\">Game:</span>", "&nbsp;", $content);
$trCurrent    = textbetweenarray("<span id=\"HTML_num_players\">", "</span>", $content);
$trTotal      = textbetweenarray("<span id=\"HTML_max_players\">", "</span>", $content);
$trStatus     = textbetweenarray("<span class=\"item_color_success\">", "</span>", $content);
$trCurrMap    = textbetweenarray("<div class=\"si_map_header\" id=\"HTML_curr_map\">", "</div>", $content);
/** $trMembers    = textbetweenarray("<span class=\"item_color_title\">Members:</span>", "&nbsp;", $content);  **/

/** SERVER NAME **/

foreach($trServerName as $tr) {
 echo $tr;
}

echo $nextline. "\n"; 

/** CLAN 

echo $serverclan;
echo $clanname;
echo $nextline. "\n"; 

**/
/** MEMBERS

echo $members ;
foreach($trMembers as $tr) {
 echo $tr;
}
echo $nextline. "\n"; 
 **/
/** GAME **/

echo $servergame;
foreach($trServerGame as $tr) {
 echo $tr;
}
echo $nextline. "\n"; 

/** STATUS **/

echo $serverstatus;
foreach($trStatus  as $tr) {
 echo $tr;
}
echo $nextline. "\n"; 

/** CURRENT and TOTAL **/

echo $playerstats;
foreach($trCurrent as $tr) {
 echo $tr;
}
?>/<?php
foreach($trTotal as $tr) {
 echo $tr;
}
echo $nextline. "\n"; 
?>
<?php
/** CURRENTMAP **/

echo $currentmap;
foreach($trCurrMap   as $tr) {
 echo $tr;
}
echo $nextline. "\n"; 


/*****************************/
?>


              <?php echo $after_widget; ?>
        <?php
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['message'] = strip_tags($new_instance['message']);
	/**	$instance['messagetwo'] = strip_tags($new_instance['messagetwo']); **/
        return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {
 
        $title 		= esc_attr($instance['title']);
        $serverip	= esc_attr($instance['message']);
  /**   $clanname	= esc_attr($instance['messagetwo']);   **/
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Server and port. eg: 127.0.0.1:27960'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" type="text" value="<?php echo $serverip; ?>" />
        </p>


        <?php
    }
 
 
} // end class hcw_gameserver_tracker
add_action('widgets_init', create_function('', 'return register_widget("hcw_gameserver_tracker");'));
?>
