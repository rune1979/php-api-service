<?php
class RestControl extends Dbc{

  protected function insertLog($facility_id = null, $zone_id = null, $lock_id = null, $user_id = null, $action){
    $sql="INSERT INTO log SET user_id=?, lock_id=?, zone_id=?, facility_id=?, action=?";
    $stmt = $this->prpConnect()->prepare($sql);
    $stmt->execute([$user_id, $lock_id, $zone_id, $facility_id, $action]);
    }

  protected function getAlert($alert_type, $who, $status = 1){
    $sql = "SELECT * FROM alerts WHERE alert_type_id=? AND who=? AND status=?";
    $stmt = $this->prpConnect()->prepare($sql);
    $stmt->execute([$alert_type, $who, $status]);
    $results = $stmt->fetchAll();
    return $results;
  }

  protected function insertAlert($alert_type, $who, $description, $facility_id = null){
    if (empty($this->getAlert($alert_type, $who, "1"))){
    $sql="INSERT INTO alerts SET alert_type_id=?, description=?, who=?, facility_id=?";
    $stmt = $this->prpConnect()->prepare($sql);
    $stmt->execute([$alert_type, $description, $who, $facility_id]);
    }
  }

  protected function updateIoT($id, $cur_val, $set_val_once = null){
		//global $standard;
		$sql = "UPDATE iot SET cur_val=?, set_val_once=? WHERE id=?";
		$stmt = $this->prpConnect()->prepare($sql);
		if ($stmt->execute([$cur_val, $set_val_once, $id])){
			//$action = "Updated IoT id: ".$id." - current value: ".$cur_val."";
			//echo $action;
			// ADDING RECORD TO LOG
			//$this->insertLog(null,null,$id,null,$action);
		} else {
			$action = "Error - Updating IoT id: ".$id." - with current value: ".$cur_val."";
			echo $action;
			// ADDING RECORD TO LOG
			//$standard->insertLog(null,null,$id,null,$action);
		}
		}

  protected function searchFacilityHash($fac_hash){
    $sql = "SELECT * FROM facility WHERE hash = ?";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$fac_hash]);
		$results = $stmt->fetchAll();
		return $results;
  }

  protected function getIoT_by_local($local_name, $facility_id){
    $sql = "SELECT * FROM iot WHERE local_name=? AND facility_id=?";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$local_name, $facility_id]);
		$results = $stmt->fetchAll();
		return $results;
  }

//Inserting post from API
  function get_IoT($local_name, $cur_val, $fac_hash){
    $sql = "INSERT INTO iot (name, description, iot_type_id, img_url, zone_id, local_name) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->prpConnect()->prepare($sql);
    $stmt->execute([$name, $description, $iot_type_id, $img_url, $zone_id, $local_name]);
    #echo "New IoT device added!";
    }

    // GET TIMERS FOR IOT ID
  protected function getSchedules($this_time, $this_date, $id){
    $sql = "SELECT * FROM scheduler WHERE iot_id = ? AND from_date <= ? AND to_date > ?";
    $stmt = $this->prpConnect()->prepare($sql);
    $stmt->execute([$id, $this_date, $this_date]);
    $results = $stmt->fetchAll();
    return $results;
  }

}

class RestView extends RestControl{

  protected function get_Schedules($time_zone, $iot_id){
    $datetime = date("Y-m-d H:i:s");
    $utc = new DateTime($datetime, new DateTimeZone('UTC'));
    $utc->setTimezone(new DateTimeZone($time_zone));
    $local_time = $utc->format('H:i:s');
    $local_date = $utc->format('Y-m-d');
    $local_day = $utc->format('D');
    if ($local_day == "Mon"){
      $day_val = 64;
    } elseif ($local_day == "Tue"){
      $day_val = 32;
    } elseif ($local_day == "Wed"){
      $day_val = 16;
    } elseif ($local_day == "Thu"){
      $day_val = 8;
    } elseif ($local_day == "Fri"){
      $day_val = 4;
    } elseif ($local_day == "Sat"){
      $day_val = 2;
    } elseif ($local_day == "Sun"){
      $day_val = 1;
    }
    $current_schedules = $this->getSchedules($łocal_time, $local_date, $iot_id);
    if (empty($current_schedules)){
      return;
    } else {
    foreach ($current_schedules as $cs) {
      // code...
      if ($local_time >= $cs['start_time'] && $local_time < $cs['to_time']){
        $sched_days = $cs['daysofweek'] - 128;
        $res = ($cs['daysofweek'] & $day_val);
       if ($res !== 0) {
         return $cs['set_val'];
       }
      }
    }
      return;
    }

  }

  // IOT CONNECT METHODE
  function IoTConnect($local_name, $get_val, $fac_hash, $ip){
    // CHECK IF FACILITY HASH STRING IS EXISTING

    $facility_id = $this->searchFacilityHash($fac_hash);
    // IF FACILITY DOES NOT EXIST
    if (empty($facility_id)){
      // LOG AND RETURN
      $message = "Illegal Brute Force attempt, attempted injection with hash string: ".$fac_hash.", set_val: ".$get_val.", local_name: ".$local_name."";
      $this->insertLog(null,null,null, $ip, $message);
      $this->insertAlert("3", $ip, $message, null);
      // RESPONSE TO USER
      $response = array(
        'status' => 0,
        'status_message' => "Access not allowed!"
      );
      //header('Content-Type: application/json');
      header("HTTP/1.0 405 Method Not Allowed");
      echo json_encode($response);
      return;
    }

    // GET ALL IOT's WITH LOCAL_NAME AND FACILITY ID
    $iots = $this->getIoT_by_local($local_name, $facility_id[0]['id']);

    if (empty($iots)) {
      // BREAK, LOG AND ALERT IF IOTS IS NOT FOUND IN SERVER
      $message = "IOT not setup on server yet, Local Name: ".$local_name.", get_val: ".$get_val." associated to Facility: ".$facility_id[0]['id']."";
      $this->insertLog($facility_id[0]['id'],"0","0", $ip, $message);
      $this->insertAlert("4", $ip, $message, $facility_id[0]['id']);

      $response = array(
        'status' => 2,
        'status_message' => "Local name not setup on server yet!"
      );
      header('Content-Type: application/json');
      echo json_encode($response);
      return;

      // NO IOT SETUP FOR LOCAL_NAME: $local_name
    } else {

      $message = "Local Name: ".$local_name." IOT id: ".$iots[0]['id'].", get_val: ".$get_val."";
      //$this->insertLog($facility_id[0]['id'],$iots[0]['zone_id'],$iots[0]['id'], $ip, $message);
      $status = "1";
      $status_message = "Everything is fine!";
      $this->updateIoT($iots[0]['id'], $get_val, null);

      // FIND OUT ABOUT INTERNAL ALERT LEVELS IOT SETTINGS
      if (!empty($iots[0]['alert_type'])){
        if (!empty($iots[0]['max_alert']) && $iots[0]['max_alert'] < $get_val){
          $text = "The Current Level: ".$get_val." exceeded the Max. Level: ".$iots[0]['max_alert']." at IoT: ".$iots[0]['name']."";
          $this->insertAlert($iots[0]['alert_type'], $iots[0]['id'], $text, $facility_id[0]['id']);
          $status = "3";
          $status_message = "Value exceeded max!";
        }
        if (!empty($iots[0]['min_alert']) && $iots[0]['min_alert'] > $get_val){
          $text = "The Current Level: ".$get_val." went below Min. Level: ".$iots[0]['min_alert']." at IoT: ".$iots[0]['name']."";
          $this->insertAlert($iots[0]['alert_type'], $iots[0]['id'], $text, $facility_id[0]['id']);
          $status = "3";
          $status_message = "Value exceeded min!";
        }
        if (!empty($iots[0]['equal_alert']) && $iots[0]['equal_alert'] == $get_val){
          $text = "The Current Level: ".$get_val." is equal to Alert: ".$iots[0]['equal_alert']." at IoT: ".$iots[0]['name']."";
          $this->insertAlert($iots[0]['alert_type'], $iots[0]['id'], $text, $facility_id[0]['id']);
          $status = "3";
          $status_message = "Value equal to alert!";
        }
        if (!empty($iots[0]['not_equal_alert']) && $iots[0]['not_equal_alert'] !== $get_val){
          $text = "The Current Level: ".$get_val." is NOT equal to: ".$iots[0]['not_equal_alert']." at IoT: ".$iots[0]['name']."";
          $this->insertAlert($iots[0]['alert_type'], $iots[0]['id'], $text, $facility_id[0]['id']);
          $status = "3";
          $status_message = "Value NOT equal to alert!";
        }
      }

      $time_schedule = $this->get_Schedules($facility_id[0]['time_zone'], $iots[0]['id']);

      $response = array(
        'status' => $status,
        //$local_name => $get_val,
        'status_message' => $status_message
      );
      if (!empty($iots[0]['set_val_forced']) || $iots[0]['set_val_forced'] == "0"){
        $response['set_val'] = $iots[0]['set_val_forced'];
      } elseif (!empty($iots[0]['set_val_once']) || $iots[0]['set_val_once'] == "0"){
        $response['set_val'] = $iots[0]['set_val_once'];
      } elseif (!empty($time_schedule) || $time_schedule == "0"){
        $response['set_val'] = $time_schedule;
      } elseif (!empty($iots[0]['set_val']) || $iots[0]['set_val'] == "0"){
        $response['set_val'] = $iots[0]['set_val'];
      }

      header('Content-Type: application/json');
      echo json_encode($response);
      return;
    }

    // GET ZONE WHERE IOT->ZONE->FACILITY_ID == FACILITY_ID

  }





}
?>
