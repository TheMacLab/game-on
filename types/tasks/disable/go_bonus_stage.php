<?php
/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 4/22/18
 * Time: 10:30 PM
 */

if (($number_of_stages == 5) && ($stage == 4) ){
    echo "<button id='go_button' status='{$stage}' onclick='task_stage_change( this );' button_type='continue'";
    if ( $stage_is_locked && empty( $stage_pass_lock ) ) {
        echo "admin_lock='true'";
    }
    echo ">See Bonus</button> ";
}

$number = $stage - 4;
$ends = array('th','st','nd','rd','th','th','th','th','th','th');
if (($number %100) >= 11 && ($number%100) <= 13)
    $abbreviation = $number. 'th';
else
    $abbreviation = $number. $ends[$number % 10];
if (($number_of_stages == 5) && ($stage >= 5) ){
    if ($stage >= 5 && $repeat_amount >= $number){
        if ($repeat_amount > 1) {
            echo "This bonus task can be repeated " . $repeat_amount . " times.<br>This is your " . $abbreviation . ".<br>";
        }
        echo "<button id='go_button' status='{$stage}' onclick='task_stage_change( this );' button_type='continue'";
        if ( $stage_is_locked && empty( $stage_pass_lock ) ) {
            echo "admin_lock='true'";
        }
        echo ">Submit Bonus</button> ";
    }

}
