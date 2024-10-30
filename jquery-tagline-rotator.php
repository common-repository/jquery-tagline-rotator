<?php

/*
Plugin Name: jQuery Tagline Rotator
Plugin URI: http://arvtard.com/jquery-tagline-rotator
Description: A plugin that will rotate your tagline in a random sequence using jQuery, there's no reloads required for the tagline to change. The plugin uses the MySQL database that your wordpress installation is already depending on. This plugin has only been tested with TwentyTen so there might be a few bugs, it comes with the bare-minimum needed to add taglines and change the delay for the rotation and the fade-in time.
Version: 0.1.5
Author: Arvin Johansson Arbab
Author URI: http://arvtard.com
License: GPL2
*/

wp_enqueue_script("jquery");
add_action('admin_menu', 'jtr_AdminMenu');

if (get_option('jtr_taglines')) {
	$jtr_taglines = jtr_Escape(get_option('jtr_taglines'));
	$GLOBALS['jtr_tagline_number'] = array_rand($jtr_taglines);
	$GLOBALS['jtr_tagline_printready'] = $jtr_taglines[$GLOBALS['jtr_tagline_number']];
	
	unset($jtr_taglines[$GLOBALS['jtr_tagline_number']]);
	$jtr_taglines = array_values($jtr_taglines);
	shuffle($jtr_taglines);
	foreach ($jtr_taglines as $rt) {
		$jtr_rand_taglines[] = $rt;
	}
	$jtr_rand_taglines[] = $GLOBALS['jtr_tagline_printready'];
	$GLOBALS['jtr_shuffled_array'] = $jtr_rand_taglines;
	
	$GLOBALS['jtr_delay'] = get_option('jtr_delay');
	$GLOBALS['jtr_fadein'] = get_option('jtr_fadein');
	
	add_filter('bloginfo','jtr_RandomTagline', 10, 2);
	add_action('wp_head', 'jtr_jQuery', 999);
}

function jtr_Escape($value) {
    $value = is_array($value) ? array_map('jtr_Escape', $value) : stripslashes($value);
    return $value;
}

function jtr_jQuery() {
	$GLOBALS['jtr_head'] = true;
	echo '
	<script type="text/javascript">
	<!--
		jQuery(document).ready(function() {
			var stopped = false;
			var taglines = '.json_encode($GLOBALS['jtr_shuffled_array']).';
			var tracker = 0;
			var timeouts = new Array();
			
			jQuery(window).focus(function() {
				if (stopped == true) {
					stopped = false;
					loopTaglines(tracker);
				}
			});
			
			jQuery(window).blur(function() {
				stopped = true;
				clearAllTimeouts();
			});
			
			function clearAllTimeouts() {
				for(key in timeouts) {
					clearTimeout(timeouts[key]);
				}
			}
			
			function loopTaglines(i) {
				if (stopped == false) {
					timeouts.push(setTimeout(function(){
						if (stopped == false) {
							i = (i+1) % taglines.length;
							tracker = i;
							jQuery(".jtr_tagline").fadeOut(0);
							jQuery(".jtr_tagline").html(taglines[i]);
							jQuery(".jtr_tagline").fadeIn('. $GLOBALS['jtr_fadein'] .');
						}
					}, '. $GLOBALS['jtr_delay']*1000 .'));
					timeouts.push(setTimeout(function() {
						if (stopped == false) {
							loopTaglines(i)
						}
					}, '. $GLOBALS['jtr_delay']*1000 .'));
				}
			}
			 loopTaglines(-1);
		}); 
	-->
	</script>
	';
}

function jtr_RandomTagline($result = '', $show = '') {
	if ($show == 'description' && $GLOBALS['jtr_head'] == true) {
		return '<span class="jtr_tagline">'.$GLOBALS['jtr_tagline_printready'].'</span>';
	} else {
		return $result;
	}

}

function jtr_AdminMenu() {
	add_options_page('jQuery Tagline Rotator', 'jQuery Tagline Rotator', 'manage_options', 'jquery-tagline-rotator', 'jtr_Options');
}

// The Admin Options in one big function, this is not how it is supposed to be done right?
function jtr_Options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	echo '<div class="wrap">'.PHP_EOL;
	echo '<h2>Options for jQuery Tagline Rotator</h2>
	<form method="post" action="'.$_SERVER['PHP_SELF'].'?page=jquery-tagline-rotator">'.PHP_EOL;
	
	if (!get_option('jtr_delay')) {
		add_option('jtr_delay', 10);
		$jtr_delay = 10;
	} else {
		if (empty($_POST['jtr_delay'])) {
			$jtr_delay = get_option('jtr_delay');
		} else {
			if (is_numeric($_POST['jtr_delay'])) {
				update_option('jtr_delay', $_POST['jtr_delay']);
				$jtr_delay = $_POST['jtr_delay'];
			} else {
				echo '<span style="color:red;">The rotational delay needs to be a valid number!</span><br />';
				$jtr_delay = get_option('jtr_delay');
			}
		}
	}
	
	if (!get_option('jtr_fadein') && get_option('jtr_fadein') != 0) {
		add_option('jtr_fadein', 300);
		$jtr_fadein = 300;
	} else {
		if (empty($_POST['jtr_fadein'])) {
			$jtr_fadein = get_option('jtr_fadein');
		} else {
			if (is_numeric($_POST['jtr_fadein'])) {
				update_option('jtr_fadein', $_POST['jtr_fadein']);
				$jtr_fadein = $_POST['jtr_fadein'];
			} else {
				echo '<span style="color:red;">The fade-in time needs to be a valid number!</span><br />';
				$jtr_fadein = get_option('jtr_fadein');
			}
		}
	}
	
	echo PHP_EOL.'<label for="jtr_delay">Rotational delay (in seconds)</label> '.PHP_EOL;
	echo '<input id="jtr_delay" type="text" name="jtr_delay" value="'.$jtr_delay.'" /> <i>Default: 10</i><br />'.PHP_EOL;
	echo PHP_EOL.'<label for="jtr_fadein">Fade-in time (in milliseconds)</label> '.PHP_EOL;
	echo '<input id="jtr_fadein" type="text" name="jtr_fadein" value="'.$jtr_fadein.'" /> <i>Default: 300</i><br /><br />'.PHP_EOL;
	echo '<input class="button-primary" type="submit" name="Submit" value="Save" /></form><br />'.PHP_EOL;
	
	if (!get_option('jtr_taglines')) {
		add_option('jtr_taglines', array());
		$jtr_taglines = array();
	} else {
		$jtr_taglines = jtr_Escape(get_option('jtr_taglines'));
	}
	
	echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?page=jquery-tagline-rotator">'.PHP_EOL;
	echo PHP_EOL.'<br /><label for="jtr_tagline">New Tagline</label> '.PHP_EOL;
	echo '<input style="width:340px;" id="jtr_tagline" type="text" name="jtr_tagline" />'.PHP_EOL;
	echo '<input class="button-secondary" type="submit" name="Submit" value="Add" /></form>'.PHP_EOL;
	
	if (!empty($_POST['jtr_tagline'])) {
		$jtr_taglines[] = $_POST['jtr_tagline'];
		update_option('jtr_taglines', $jtr_taglines);
	}
	
	if (isset($_GET['delete'])) {
		unset($jtr_taglines[$_GET['delete']]);
		$jtr_taglines = array_values($jtr_taglines);
		update_option('jtr_taglines', $jtr_taglines);
	}
	
	if (!empty($jtr_taglines)) {
		$jtr_print_taglines = '<h3>List of taglines</h3> <ul>'.PHP_EOL;
		$i = 0;
		foreach ($jtr_taglines as $jtr_tagline) {
			$jtr_print_taglines .= '<li class="jtr_tagline"><a class="button-secondary" href="'.$_SERVER['PHP_SELF'].'?page=jquery-tagline-rotator&amp;delete='.$i.'">Remove</a> '.$jtr_tagline.'</li>'.PHP_EOL;
			$i++;
		}
		$jtr_print_taglines .= '</ul>'.PHP_EOL;
	} else {
		$jtr_print_taglines = '<i>There are no taglines, please add some!</i>'.PHP_EOL;
	}
	
	echo '<br /><br />'.$jtr_print_taglines.PHP_EOL;
	echo '</div>';
}

?>