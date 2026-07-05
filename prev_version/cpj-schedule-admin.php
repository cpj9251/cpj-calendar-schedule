<?php
/*************
 * File for admin functions for CPJ Schedule Plugin
 * Author: Paul Jarvis
 * ver: 2.0.0
 * License: gpl. gnu
 */

 if(! defined ('WPINC')){
    die;
}



function cpj_appt_schedule_admin_enqueue($hook){
	
	wp_enqueue_script('cpj-calendar-schedule-admin-js', plugin_dir_url(__FILE__).'cpj-calendar-schedule-admin.js',array( 'jquery' ),'1.0.0',true);

	wp_enqueue_style('cpj-calendar-schedule-admin-css',  plugin_dir_url(__FILE__).'cpj-calendar-schedule-admin.css');

    $cpj_calendar_admin_nonce = wp_create_nonce( 'cpj_calendar_admin_nonce_cpj' );

    $localizeYesOrNo2 = wp_localize_script(
        'cpj-calendar-schedule-admin-js',
        'cpj_calendar_admin_ajax_obj',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    =>  $cpj_calendar_admin_nonce,
            'site_url' => plugins_url()
        )
        );


	        wp_enqueue_style('cpj-calendar-time-css',  plugin_dir_url(__FILE__).'cpj-calendar-time.css');

}//end fx

add_action('admin_enqueue_scripts','cpj_appt_schedule_admin_enqueue');


add_action('admin_menu','cpj_appt_schedule_admin_menu');

function cpj_appt_schedule_admin_menu(){
    
    add_menu_page(
        'CPJ Appt Schedule Admin',
        'CPJ Appt Schedule Admin',
        'manage_options',
        'cpj-appt-schedule-admin-menu',
        'cpj_appt_schedule_admin_menu_page');

    add_submenu_page(
        'cpj-appt-schedule-admin-menu',
        'CPJ Appt E-mail Settings',
        'CPJ Appt E-mail Settings',
        'manage_options',
        'cpj-appt-schedule-email-settings',
        'cpj_appt_schedule_email_settings_page'
    );

    add_submenu_page(
        'cpj-appt-schedule-admin-menu',
        'CPJ Appt Time Settings',
        'CPJ Appt Time Settings',
        'manage_options',
        'cpj-appt-schedule-time-settings',
        'cpj_appt_schedule_time_settings_page'
    );




}


function cpj_appt_schedule_admin_menu_page(){

?>

<h4>Appt Schedule Admin</h4>

<div class="cpj-appt-admin-outer-box">

    <div class="cpj-appt-admin-outer-table-box">

        <div class="cpj-appt-admin-appt-cust-tab-box">

            <div id="cpj-appt-list-tab" class="cpj-appt-admin-tabs cpj-appt-admin-tabs-sel">Upcoming Appointments</div>
            <div id="cpj-cust-list-tab" class="cpj-appt-admin-tabs">Customers</div>
        </div>
        <div class="cpj-appt-admin-appt-cust-table-box">

        </div>
    </div>
</div>
<div id="cust-appt-details-win"></div>
<div id="white-bg"></div>
<div id="cust-appt-del-appt-confirm-win">
<input type="hidden" id="cust-appt-del-appt-confirm-id" value="">
<p>Are you sure you want to delete this appointment?</p>
<p style="margin-left:50px;">
    <button id="cust-appt-del-appt-confirm-btn-no" class="cust-appt-del-btns">No</button>
    <button id="cust-appt-del-appt-confirm-btn-yes" class="cust-appt-del-btns">Yes</button>
</p>
</div>
<div id="cust-appt-del-cust-confirm-win">
<input type="hidden" id="cust-appt-del-cust-confirm-id" value="">
<p>Are you sure you want to delete this Customer?</p>
<p style="margin-left:50px;">
    <button id="cust-appt-del-cust-confirm-btn-no" class="cust-appt-del-btns">No</button>
    <button id="cust-appt-del-cust-confirm-btn-yes" class="cust-appt-del-btns">Yes</button>
</p>
</div>
<div id="cust-appt-del-cust-deleted-win">
</div>
<div id="cust-appt-details-add-new-appt-win">
<h4>Add New Appointment</h4>
<div class="cust-appt-details-add-new-calendar-box"></div>
<div class="cust-appt-details-add-new-time-box"></div>
<div class="cust-appt-details-add-new-submit-box"></div>
</div>
 
<?php

}//end fx

function cpj_appt_schedule_email_settings_page(){


    global $wpdb;
    $emailContent = $wpdb->get_row("select * from wp_cpj_appt_email_content limit 1");

?>
<input type="hidden" name="cpj-schedule-email-admin-id" id="cpj-schedule-email-admin-id" value="<?php echo esc_attr($emailContent->id);?>">
<div class="schedule-email-optoins-outer-box">
    <h3>Schedule E-mail Options</h3>

    <div class="email-box-left">
        <p>Enter the e-mail you would like users to receive after scheduling an appointment. The keywords *cpj-first-name*, *cpj-date*, *cpj-time* are automatically populated with client's name and appointment time.</p>
        <div class="subject-line-box">
            <div>Subject:</div>
            <div>
        <input type="text" style="width:350px;" name="cpj-confirm-appt-subject" id="cpj-confirm-appt-subject" value="<?php echo esc_attr($emailContent->subject);?>">
        </div>
    </div> 

        <div class="editor-box">
<?php
 

                wp_editor($emailContent->email_content,'cpj-confirm-appt-email');

        ?>
        </div>
        <div class="email-content-submit-box">
            <input type="button" name="email-content-submit" id="email-content-submit" value="Submit">
        </div>
    </div>
    <div class="email-box-right" id="from-field-box">
    <p>E-mail From Address Display Name:</p>
        <div>
            <input type="text" name="appt-email-from-field-display-name" id="appt-email-from-field-display-name" value="<?php echo esc_attr($emailContent->from_field_display_name);?>">
        </div>
        <p>E-mail From Address:</p>
        <div>
            <input type="text" name="appt-email-from-field" id="appt-email-from-field" value="<?php echo esc_attr($emailContent->from_field);?>">
        </div>
        <div>
            <input type="button" name="appt-from-email-submit" id="appt-from-email-submit" value="submit">
        </div>

    </div> 
    <div class="email-box-right" id="notify-box">
        <p>E-mail where scheduling appointments notifications should be sent:</p>
        <div>
            <input type="text" name="notify-email" id="notify-email" value="<?php echo esc_attr($emailContent->notify_email);?>">
        </div>
        <div>
            <input type="button" name="notify-email-submit" id="notify-email-submit" value="submit">
        </div>
    </div>
</div>

<?php

}//end fx

add_action('wp_ajax_cpj_calendar_e-mail_admin', 'cpj_calendar_email_admin_handler');

function cpj_calendar_email_admin_handler(){

    global $wpdb;

      $doUpdate = $wpdb->update('wp_cpj_appt_email_content',
                                array(
                                    'email_content'=>sanitize_textarea_field($_POST['email-content']),
                                    'subject'=>sanitize_text_field($_POST['subject'])
                                ),
                                array(
                                    'id' => intval($_POST['id'])
                                ),
                                array('%s','%s'),
                                array('%d')
                            );
        $msg = (false !== $doUpdate)?"Success, E-mail Content has been updated":"Error E-mail content was not updated";



        echo esc_html($msg);

 wp_die();

}

add_action('wp_ajax_cpj_calendar_e-mail_notify_admin', 'cpj_calendar_email_notify_admin_handler');

function cpj_calendar_email_notify_admin_handler(){

    global $wpdb;
   
    $doUpdate2 = $wpdb->update('wp_cpj_appt_email_content',
    array(
        'notify_email'=>sanitize_email($_POST['notify-email'])
    ),
    array(
        'id' => intval($_POST['id'])
    ),
    array('%s'),
    array('%d')
);
$msg = (false !== $doUpdate2)?"Success, E-mail has been updated":"Error E-mail was not updated";

echo esc_html($msg);

wp_die();

}

add_action('wp_ajax_cpj_calendar_e-mail_from_admin', 'cpj_calendar_email_from_admin_handler');

function cpj_calendar_email_from_admin_handler(){

    global $wpdb;
  
    $doUpdate2 = $wpdb->update('wp_cpj_appt_email_content',
    array(
        'from_field'=>sanitize_email($_POST['from-email']),
        'from_field_display_name'=>sanitize_text_field($_POST['display-name'])
    ),
    array(
        'id' => intval($_POST['id'])
    ),
    array('%s','%s'),
    array('%d')
);

$msg = (false !== $doUpdate2)?"Success, E-mail has been updated":"Error E-mail was not updated";

echo esc_html($msg);

wp_die();

}

function cpj_appt_schedule_time_settings_page(){

            global $wpdb;

            ?>
            <h2>Appointment Time Settings</h2>

            <div class="appt-time-settings-box">
            <div class="appt-time-inner-box">
            <h4 id="return-msg"></h4>
            <h4>Set Available Times Global</h4>
            <table>
                <tr>
                <th>Sunday</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
                </tr>

            <?php
            for($t=6;$t<21;$t++){

            $time = date('g:i A',mktime($t,0,0));
            
            echo "<tr>";
            
                for($d=0;$d<7;$d++){

                $isActive = $wpdb->get_var('select id from wp_cpj_schedule_day_time where day_num = '.$d.' and time = '.$t.' limit 1');
                $selTime = ($isActive)?' appt-time-settings-btn-sel':'appt-time-settings-btn';

                $timeId = ($isActive)?$isActive:0;

                $timeVal = $timeId . "_" . $d . "_" . $t;

                echo '<td><button class="'.esc_attr($selTime).'" value="'.esc_attr($timeVal).'">'.esc_html($time).'</button></td>';
            
                }//ednm for

                echo '</tr>';
            }//emnd for
        ?>
        </table>
        </div>
        </div>
        <div class="appt-time-daily-settings-box">
        <h2>Block out unavailable times per day</h2>
        <div class="appt-time-daily-settings-inner-box">

        <div id="cpj-admin-cal"></div>
        <div id="cpj-admin-time"></div>
        <?php

}//end fx

add_action('wp_ajax_cpj_schedule_time_settings', 'cpj_schedule_time_settings_handler');

function cpj_schedule_time_settings_handler(){

        global $wpdb;

        $valArr = array(sanitize_text_field($_POST['time-id']),sanitize_text_field($_POST['day-num']),sanitize_text_field($_POST['time-num']));


        if($valArr[0] > 0 ){

            $wpdb->delete('wp_cpj_schedule_day_time',array('id'=>$valArr[0]), array('%d'));    

            $insertId = 0;
            
        }
        else{
            $wpdb->insert('wp_cpj_schedule_day_time',
                            array('day_num'=>$valArr[1],
                                    'time'=>$valArr[2]
                                    ),
                            array('%d','%d')
                            );

            $insertId = $wpdb->insert_id;

        }

        echo $insertId;

wp_die();

}//endfx

add_action('wp_ajax_cpj_admin_calendar', 'cpj_admin_calendar_handler');


function cpj_admin_calendar_handler(){


    if(isset($_POST['mon-direction'])){
            $monDir = sanitize_text_field($_POST['mon-direction']);
            $curMonNum = intval($_POST['cur-mon-num']);
            $showToday = false;
    }
    else{
        $monDir = '';
    }

	if($monDir === 'next'){

		 if($curMonNum === 12){
			$newMon = 1;
			$newYear = date("Y") + 1;
		}
		else{
			$newMon = $curMonNum + 1;
			$newYear = date('Y');
		}
		
		$today = getdate(mktime(0,0,0,$newMon,1,$newYear));
		$numOfDaysInMonth = date("t",mktime(0,0,0,$newMon,1,$newYear));
	}
	else if($monDir === 'prev'){
	
		if($curMonNum === 1){
			$newMon = 12;
			$newYear = date("Y") - 1;
		}
		else{
			$newMon = $curMonNum - 1;
			$newYear = date('Y');
		}
		
		$today = getdate(mktime(0,0,0,$newMon,1,$newYear));
		$numOfDaysInMonth = date("t",mktime(0,0,0,$newMon,1,$newYear));

	}
	else{
		$showToday = true;

		$today = getdate();
		$numOfDaysInMonth = date("t");
	}

	$firstDayOfMonth = date("w", mktime(0,0,0,$today['mon'],1,$today['year']));	

	$dayArr = ['Sun','Mon','Tues','Wed','Thu','Fri','Sat'];
?>

<input type="hidden" id="cur-mon-num" value="<?php echo esc_attr($today['mon']);?>">
<input type="hidden" id="cur-mon-name" value="<?php echo esc_attr($today['month']);?>">
<input type="hidden" id="cur-year" value="<?php echo esc_attr($today['year']);?>">

	<div class="calendar-header" style="width:325px;">
		<div class="prev-mon-btn"><<</div>
		<div class="mon-title"><?php echo esc_html($today['month']) . " " . esc_html($today['year']);?></div>
		<div class="next-mon-btn">>></div>
</div>
<table class="cal-table">
<tr class="dow-header">

<?php

	for($m=0; $m < 7;$m++){
echo '
		<td class="dow-title">'.esc_html($dayArr[$m]).'</td>
';

	}//end for loop
echo '
</tr>
<tr class="mon-days-box">
';

	
	$startNum = 1 - $firstDayOfMonth;
	$weekCounter = 0;

	for($i=$startNum; $i<=$numOfDaysInMonth; $i++){
	$weekCounter ++;

	echo '<td>';

	if($i<=0){
		echo "<div>&nbsp;</div>";
	}
	else if(($i == $today['mday'])&&($showToday)){

		echo '<div class="mon-days mon-days-today">'.esc_html($i).'</div>';

	}
	else{

		echo '<div class="mon-days">'.esc_html($i).'</div>';

	}

	echo '</td>';

	if($weekCounter === 7){
		echo '</tr><tr>';
		$weekCounter = 0;
	}	
}//end for loop

echo "</tr></table>";
/**/
	wp_die();

}
add_action('wp_ajax_cpj_admin_time', 'cpj_admin_time_handler');


function cpj_admin_time_handler(){


	global $wpdb;


			$selMon = intval($_POST['cur-mon-num']);
			$selDay = intval($_POST['cur-day-num']);
			$selYear = intval($_POST['cur-year']);

			$nineAm = mktime(9,0,0,$selMon,$selDay,$selYear);
			$dow = date('w', mktime(0,0,0,$selMon,$selDay,$selYear));

			$nineAmStr = date('g:i A D F j, Y',$nineAm);

			
			$sqlDate = $selYear . "-" . str_pad($selMon,2,"0",STR_PAD_LEFT) . "-" . str_pad($selDay,2,"0",STR_PAD_LEFT);
			$sqlStr = "select mktime from wp_cpj_appt_schedule where appt_date = '".$sqlDate."'";
			

			$apptTimeArr = $wpdb->get_results($sqlStr, ARRAY_N);


			$bookedTimeArr = array();

			for($p=0;$p<count($apptTimeArr);$p++){
				$bookedTimeArr[] = $apptTimeArr[$p][0];
			}//emnd for
			
			$avalTimesArr = $wpdb->get_results('select time from wp_cpj_schedule_day_time where day_num = '.$dow.' order by time');

			if(count($avalTimesArr)>0){

			foreach($avalTimesArr as $avalTime){
			//for($t = 9; $t < 17; $t++){
				 $t = $avalTime->time;
				$timeNum = mktime($t,0,0,$selMon,$selDay,$selYear);
				$timeStr = date('g:i A',$timeNum);
				
			
				if(in_array($timeNum,$bookedTimeArr)){

					$disabledClass = "time-btn-disabled";
				
				}
					else{
				
						$disabledClass = "time-btn-sel";
				
					}
					

				?>
				
				<button class="time-btn <?php echo esc_attr($disabledClass);?>" value="<?php echo esc_attr($timeNum);?>"><?php echo esc_html($timeStr);?></button>
				
				<?php

			}//end for
			}
			else{

				echo "No Times Available.";
			}


wp_die();

}

add_action('wp_ajax_cpj_admin_time_remover', 'cpj_admin_time_remover_handler');


function cpj_admin_time_remover_handler(){

            global $wpdb;

            $myTime = sanitize_text_field($_POST['sel-time']);


            $sqlDate = date('Y-m-d',$myTime);
            $sqlTime = date('H:i:s',$myTime);

            $wpdb->insert('wp_cpj_appt_schedule',
                                        array(
                                        'user_id' => 0,
                                        'appt_date' => $sqlDate,
                                        'appt_time' => $sqlTime,
                                        'mktime' => $myTime,
                                        'type' => 0),
                                        array(
                                        '%d',
                                        '%s',
                                        '%s',
                                        '%d',
                                        '%d')
                            );

                $myId = $wpdb->insert_id;

                echo $myId;

wp_die();


}//end fx

add_action('wp_ajax_cpj_admin_time_adder', 'cpj_admin_time_adder_handler');


function cpj_admin_time_adder_handler(){

            global $wpdb;

            $myTime = sanitize_text_field($_POST['del-id']);

             $didDelete = $wpdb->delete('wp_cpj_appt_schedule',array('mktime'=>$myTime,'type'=>0));
                                    
            echo $didDelete;

wp_die();

}//end fx


