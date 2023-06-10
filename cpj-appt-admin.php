<?php
/*************
 * File for admin functions for CPJ Schedule Plugin
 * Author: Paul Jarvis
 * ver: 1.0.0
 * License: gpl. gnu
 */

 if(! defined ('WPINC')){
    die;
}

function cpj_appt_admin_enqueue($hook){
	
	wp_enqueue_script('cpj-appt-admin-js', plugin_dir_url(__FILE__).'cpj-appt-admin.js',array( 'jquery' ),'1.0.0',true);

	wp_enqueue_style('cpj-appt-admin-css',  plugin_dir_url(__FILE__).'cpj-appt-admin.css');

    $cpj_appt_admin_nonce = wp_create_nonce( 'cpj_appt_admin_nonce' );

    $localizeYesOrNo2 = wp_localize_script(
        'cpj-appt-admin-js',
        'cpj_appt_admin_ajax_obj',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    =>  $cpj_appt_admin_nonce,
            'site_url' => plugins_url()
        )
        );
}//end fx

add_action('admin_enqueue_scripts','cpj_appt_admin_enqueue');

add_action('wp_ajax_cpj-appt-admin-appt-list', 'cpj_appt_admin_appt_list');

function cpj_appt_admin_appt_list(){

      global $wpdb;

       $rightNow = time();

       $apptListSql = 'SELECT *, wcau.id as cust_id FROM wp_cpj_appt_schedule as wcas, wp_cpj_appt_users as wcau where wcas.user_id = wcau.id and mktime > '.$rightNow.' order by mktime';
    
       $apptListRes = $wpdb->get_results($apptListSql);

       ?>

        <table class="appt-list-table">
            <tr class="appt-list-table-header-row">
                <th class="appt-list-table-name-header">Name</th>
                <th class="appt-list-table-date-header">Date</th>
                <th class="appt-list-table-time-header">Time</th>
            </tr>
    <?php

        $i=0;

        foreach($apptListRes as $myRow){
            $i++;
            $bgClass = (($i%2)>0)?"row-bg-odd":"row-bg-even";

                $apptDate = date('D F j, Y',$myRow->mktime);
                $apptTime = date('g:i A',$myRow->mktime);
              
                ?>
                <tr id="<?php echo esc_attr($myRow->cust_id);?>" class="<?php echo esc_attr($bgClass);?>">
                <td><?php echo esc_html($myRow->first_name) . ' ' . esc_html($myRow->last_name);?></td>
                <td><?php echo esc_html($apptDate);?></td>
                <td><?php echo esc_html($apptTime);?></td>
                </tr>
        <?php
        }//end foreach
        if($i === 0){
        
        echo '<tr><td colspan="3">No Upcoming Appointments</td></tr>'."\n";

        }//end if

        ?>
        </table>

<?php
wp_die();
}//end fx

add_action('wp_ajax_cpj-appt-admin-cust-list', 'cpj_appt_admin_cust_list');

function cpj_appt_admin_cust_list(){

            global $wpdb;
?>

        <table class="appt-list-table">
            <tr class="appt-list-table-header-row">
                <th class="appt-list-table-name-header">Name</th>
                <th class="appt-list-table-date-header">Phone</th>
                <th class="appt-list-table-time-header">Email</th>
            </tr>

<?php

    $custListSql = "select * from wp_cpj_appt_users order by first_name";

    $custListRes = $wpdb->get_results($custListSql);

    $i = 0;

    foreach($custListRes as $cust){
            $i++;
            $bgClass = (($i%2)>0)?"row-bg-odd":"row-bg-even";

               ?>
                <tr id="<?php echo esc_attr($cust->id);?>" class="<?php echo esc_attr($bgClass);?>">
                <td><?php echo esc_html($cust->first_name) . ' ' . esc_html($cust->last_name);?></td>
                <td><?php echo esc_html($cust->phone);?></td>
                <td><?php echo esc_html($cust->email);?></td>
                </tr>
        <?php
        }//end foreach

        if($i === 0){
        
        echo '<tr><td colspan="3">No Customers</td></tr>'."\n";

        }//end if

        ?>
        </table>

<?php
wp_die();
}//end fx

add_action('wp_ajax_cpj-appt-cust-details', 'cpj_appt_cust_appt_details');

function cpj_appt_cust_appt_details(){

      global $wpdb;

      $custId = sanitize_text_field($_POST['cust_id']);

      $mySql = "select * from wp_cpj_appt_users where id = '" . $custId . "' limit 1";

      $myRow = $wpdb->get_row($mySql);

        ?>
         <div class="cust-details-outer-box">

            <a href="#" id="cust-details-close-x">X</a>

            <h4>Cust - Appt - Details</h4>

            <div id="cust-mod-msg"></div>

            <div id="cust-form">
            <input type="hidden" id="cpj-cust-mod-cust-id" value="<?php echo esc_attr($custId);?>">
            <div class="form-row">
            <label class="cust-form-labels" for="first_name">First Name</label>
            <input type="text" id="cpj-cust-mod-first_name" class="cust-input" value="<?php echo esc_attr($myRow->first_name);?>">
            </div>

            <div class="form-row">
            <label class="cust-form-labels" for="last_name">Last Name</label>
            <input type="text" id="cpj-cust-mod-last_name" class="cust-input" value="<?php echo esc_attr($myRow->last_name);?>">
            </div>

            <div class="form-row">
            <label class="cust-form-labels" for="phone">Phone</label>
            <input type="text" id="cpj-cust-mod-phone" class="cust-input" value="<?php echo esc_attr($myRow->phone);?>">
            </div>

            <div class="form-row">
            <label class="cust-form-labels" for="email">Email</label>
            <input type="text" id="cpj-cust-mod-email" class="cust-input" value="<?php echo esc_attr($myRow->email);?>">
            </div>

            <div class="form-row">
            <label class="cust-form-labels" for="pref">Contact Preference</label>
            <select id="cpj-cust-mod-pref" class="cust-input">
            <?php
            $prefArr = array('Phone','Email','Text');
            foreach($prefArr as $prefText){
                    $selText = ($myRow->pref == $prefText)?' selected="yes" ':'';
                    echo '<option'.$selText.' value="'.$prefText.'">'.$prefText.'</option>'."\n";
            }//end foreach
            ?>
            </select>
            </div>

            <div class="form-row">
            <button id="cpj-cust-mod-btn" class="btn">Modify</button>
            </div>

            </div>

            <div id="appt-details">
                <h4>Upcoming Appointments</h4>
            
            <div class="appt-details-appt-list-box">

            <?php

            output_cust_details_appt_list($custId);

            ?>
            </div>

            </div>

            <div class="cust-details-btn-row">

                <button id="cust-details-add-new-appt-btn" class="cust-details-btns" style="float:left;margin-left:5px;" value="<?php echo esc_attr($custId);?>" >Add New Appt</button>
                <button id="cust-details-del-cust-btn" class="cust-details-btns" style="float:left;margin-left:5px;" value="<?php echo esc_attr($custId);?>" >Delete Customer</button>
                <button id="cust-details-cancel-btn" class="cust-details-btns" style="float:left;margin-left:5px;" value="" >Close</button>
             </div>   


        </div>


        <?php
wp_die();

}//end fx

add_action('wp_ajax_cpj-appt-cust-mod', 'cpj_appt_cust_mod');

function cpj_appt_cust_mod(){

        global $wpdb;

        $result = $wpdb->update('wp_cpj_appt_users',
                    array(
                    'first_name'=>sanitize_text_field($_POST['first_name']),
                    'last_name'=>sanitize_text_field($_POST['last_name']),
                    'phone'=>sanitize_text_field($_POST['phone']),
                    'email'=>sanitize_text_field($_POST['email']),
                    'pref'=>sanitize_text_field($_POST['pref'])
                    ),
                    array('id'=>sanitize_text_field($_POST['cust_id'])),
                    array('%s','%s','%s','%s','%s'),
                    array('%d')

        );

        $msg = (false === $results)?'Error Modifying Customer':'Customer Modify Successful';

        echo $msg;




wp_die();
}//end fx

add_action('wp_ajax_cpj-appt-del-appt', 'cpj_appt_del_appt');

function cpj_appt_del_appt(){

        global $wpdb;

        $didDel = $wpdb->delete('wp_cpj_appt_schedule',array('id'=>sanitize_text_field($_POST['appt_id'])));

        $msg = (false === $didDel)?'Error deleting appoinment':'Appointment deleted successfully.';

        echo $msg;


wp_die();
}

add_action('wp_ajax_cpj-appt-details-refresh-appt', 'cpj_appt_details_refresh_appt');

function cpj_appt_details_refresh_appt(){

    $custId = sanitize_text_field($_POST['cust_id']);

    output_cust_details_appt_list($custId);

wp_die();
}

function output_cust_details_appt_list($custId){

        global $wpdb;


            $rightNow = time();

            $apptSql = "select * from wp_cpj_appt_schedule where user_id = " . $custId . " and mktime > " . $rightNow;

            $myRes = $wpdb->get_results($apptSql);

            if(count($myRes)){

            foreach($myRes as $apptRow){

                $apptDate = date('D F j, Y',$apptRow->mktime);
                $apptTime = date('g:i A',$apptRow->mktime);
                    
            ?>


                <div class="appt-details-row">
                    <?php echo esc_html($apptDate) . ' - ' . esc_html($apptTime);?>
                    <button class="appt-details-delete-btn" value="<?php echo esc_attr($apptRow->id);?>">X</button>
                </div>

            <?php
            
            }
            }
            else{
            ?>
            <div class="appt-details-row">No Appointments scheduled</div>

            <?php
                
            }//end else



}//end fx

add_action('wp_ajax_cpj-appt-details-load-cal', 'cust_details_load_cal');

function cust_details_load_cal(){

	$curMonNum = intval($_POST['cur-mon-num']);
	$showToday = false;
	$monDir = sanitize_text_field($_POST['mon-direction']);

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

	<div class="calendar-header">
		<div class="prev-mon-btn"><<</div>
		<div class="mon-title"><?php echo esc_html($today['month']) . " " . esc_html($today['year']);?></div>
		<div class="next-mon-btn">>></div>
</div>
<table class="cal-table">
<tr class="dow-header">

<?php

	for($m=0; $m < 8;$m++){
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

add_action('wp_ajax_cust_details_cal_time', 'cust_details_cal_time');

function cust_details_cal_time(){


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
				
						$disabledClass = "cust-details-add-new-time-btn";
				
					}
					

				?>
				
				<button class="<?php echo esc_attr($disabledClass);?>" value="<?php echo esc_attr($timeNum);?>"><?php echo esc_html($timeStr);?></button>
				
				<?php

			}//end for
			}
			else{

				echo "No Times Available.";
			}


wp_die();

}

add_action('wp_ajax_cust-appt-details-add-new-appt', 'cust_appt_details_add_new_appt');

function cust_appt_details_add_new_appt(){


	global $wpdb;

        $cust_id = sanitize_text_field($_POST['cust_id']);
        $mktime = sanitize_text_field($_POST['sel-date_time']);

        $sqlDate = date('Y-m-d', $mktime);
        $sqlTime = date('H-i-s',$mktime);

        $didInsert = $wpdb->insert('wp_cpj_appt_schedule',
                        array('user_id'=>$cust_id,
                            'appt_date'=>$sqlDate,
                            'appt_time'=>$sqlTime,
                            'mktime'=>$mktime),
                            array('%d','%s','%s','%d')
                        );

        $msg = (false === $didInsert)?'Error Adding New appointment':'New appointment added successfully';

        echo $msg;




wp_die();
}

add_action('wp_ajax_cpj-appt-details-del-cust', 'cpj_appt_details_del_cust');

function cpj_appt_details_del_cust(){


	global $wpdb;

        $cust_id = sanitize_text_field($_POST['cust_id']);

        $wpdb->delete('wp_cpj_appt_schedule',array('user_id'=>$cust_id),array('%d'));
        
        $wpdb->delete('wp_cpj_appt_users',array('id'=>$cust_id),array('%d'));
        
        ?>

        <p>Customer has been deleted.</p>
        <div style="width:100px;margin:20px auto;"><button class="cust-appt-del-btns" id="cust-del-response-ok">Ok</button>
        </div>

        <?php



wp_die();

}

add_action('wp_ajax_cust-appt-details-refresh-list', 'cust_appt_details_refresh_list');

function cust_appt_details_refresh_list(){

    cpj_appt_admin_appt_list();

}//end fx
