<?php
/*
Plugin Name: Game Server Tracker
Plugin URI: http://hannawerner.com/wordpress/game-server-tracker-version-1-7/
Description: Gets current stats of a Game Server from GameTracker.com and displays them in the sidebar. The Game Server Tracker supports all games that are supported by GameTracker.com.
Version: 1.7
License: GPLv2
Author: Hanna Camille Werner
Author URI: http://www.hannawerner.com
*/

// Inspired by the built-in WP_Widget_Text class

$alttitle = $instance['alttitle'];

class HCW_Gameserver_Tracker extends WP_Widget {

	function HCW_Gameserver_Tracker() {
		$widget_ops = array('classname' => 'widget_hcw_gst', 'description' => __('Displays Game  Server Information'));
		$control_ops = array();
		$this->WP_Widget('hcwgst', __('Game Server Tracker'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$serverip = $instance['serverip'];
		
  	echo $before_widget . $before_title;
		echo $instance['alttitle'];
		echo $after_title; 

    ?>

<script type="text/javascript" language="JavaScript">
<!--
	function HideMoreContent(d) {
		document.getElementById(d).style.display = "none";
	}
	function ShowMoreContent(d) {
		document.getElementById(d).style.display = "block";
	}
	function ReverseDisplay(d) {
		if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
		else { document.getElementById(d).style.display = "none"; }
	}
//-->
</script>

<?php
$content = file_get_contents("http://www.gametracker.com/server_info/$serverip/");
preg_match("/<span class=\"item_color_success\"(.*?)>(.+?)<\/span>/s", $content, $matchesalive);
preg_match("/<span id=\"HTML_num_players\"(.*?)>(.+?)<\/span>/s", $content, $matchesnumplayers);
preg_match("/<span id=\"HTML_max_players\"(.*?)>(.+?)<\/span>/s", $content, $matchestotalplayers);
preg_match("/<span id=\"HTML_num_bots\"(.*?)>(.+?)<\/span>/s", $content, $matchesbots);
preg_match("/<div class=\"si_map_header\" id=\"HTML_curr_map\"(.*?)>(.+?)<\/div>/s", $content, $matchesmap);
preg_match("/<span class=\"item_color_title\"(.*?)>Game:<\/span>(.+?)&nbsp;/s", $content, $matchesgame);
preg_match("/<span class=\"item_color_title\"(.*?)>Name:<\/span>(.+?)<br\/\>/s", $content, $matchesclan);
preg_match("/<div class=\"si_map_image\" id=\"HTML_map_ss_img\"(.*?)>(.+?)<\/div>/s", $content, $matchesimgmap);

	echo '<ul>';
	echo '<li><strong>Game: </strong>';
	echo $matchesgame[2];
	echo '</li><li><strong>IP: </strong><a href="http://www.gametracker.com/server_info/';
	echo $serverip;
	echo '/" target="_blank">';
	echo $serverip;
	echo '</a></li><li><strong>Server Status: </strong>';
	echo $matchesalive[2];
	echo '</li><li><strong>Players: </strong>';
	echo $matchesnumplayers[2]; 
	echo '/';
	echo $matchestotalplayers[2];
	echo '</li><li><strong>Bots: </strong>';
	echo $matchesbots[2];
	echo '</li><li><strong>Map: </strong>';
	
?>
<a class="moreserverinfo" href="javascript:ReverseDisplay('<?php echo $serverip; ?>')"><?php echo $matchesmap[2]; ?> &raquo;</a>
<div class="content-sidebar" id="<?php echo $serverip; ?>" style="display:none; padding-top:5px;">
	<?php echo $matchesimgmap[2]; ?>
</div>
	<?php
		echo '</li>';
		echo '</ul>';
	?>
  	<?php echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['serverip'] = strip_tags($new_instance['serverip']);
		$instance['alttitle'] = strip_tags($new_instance['alttitle']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'serverip' => '', 'alttitle' => false ) );

		if ($instance['serverip'])
  		$title = preg_replace('/\?.*/', "", basename($instance['serverip']));

?>
    <?php ?>
    <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="hidden" value="<?php echo $title; ?>" />

		<p>
			<label for="<?php echo $this->get_field_id('alttitle'); ?>">
				&nbsp;<?php _e('Server Name:'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('alttitle'); ?>" name="<?php echo $this->get_field_name('alttitle'); ?>" type="text" value="<?php echo $instance['alttitle']; ?>" />
				
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('serverip'); ?>">
				 &nbsp;<?php _e('Server IP and Port:'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('serverip'); ?>" name="<?php echo $this->get_field_name('serverip'); ?>" type="text" value="<?php echo $instance['serverip']; ?>" /><br />&nbsp;<small>e.g. 213.239.207.85:27960</small><br />
			</label>
		</p>

<?php
	}
}

function widget_hcw_gst_init() {
  register_widget('HCW_Gameserver_Tracker');
}
add_action('init', 'widget_hcw_gst_init', 1);

?>
