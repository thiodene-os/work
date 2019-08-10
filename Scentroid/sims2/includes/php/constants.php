<?php

// User Roles
define("USER_ROLE_ADMIN",1) ;
define("USER_ROLE_STAFF",2) ;
define("USER_ROLE_CUSTOMER",3) ;
$desc_user_role = array(USER_ROLE_ADMIN => "Admin"
                       ,USER_ROLE_STAFF => "Staff"
                       ,USER_ROLE_CUSTOMER => "Customer"
                      ) ;

// Equipment Category for Notifications
define("EQUIPMENT_CONNECTION_TO_CLOUD",1) ;
$desc_notification_category = array(EQUIPMENT_CONNECTION_TO_CLOUD => "Cloud"
                       //,EQUIPMENT_SENDING => "Sending"
                       //,EQUIPMENT_UNKNOWN => "Unknown"
                      ) ;

// Equipment Status from Cloud perspective for Notifications
define("EQUIPMENT_NO_DATA",0) ;
define("EQUIPMENT_SENDING",1) ;
define("EQUIPMENT_NOT_SENDING",2) ;
define("EQUIPMENT_INACTIVE",3) ;
$desc_equipment_notification_status = array(EQUIPMENT_NO_DATA => "No Data"
                       ,EQUIPMENT_SENDING => "Sending"
                       ,EQUIPMENT_NOT_SENDING => "Not Sending"
                       ,EQUIPMENT_INACTIVE => "Inactive"
                      ) ;

$desc_equipment_notification_color = array(EQUIPMENT_NO_DATA => "#f00" // Red
                       ,EQUIPMENT_SENDING => "#00e400" // Green
                       ,EQUIPMENT_NOT_SENDING => "#ff7e00" // Orange
                       ,EQUIPMENT_INACTIVE => "#99004d" // Purple
                      ) ;
                      
// Here are the error codes so far in the SL50 (03/01/2019)
define("FS001",1) ;
define("FS141",2) ;
define("FS151",3) ;
define("FS140",4) ;
define("FS150",5) ;
define("FM001",6) ;
define("FM002",7) ;
$desc_sensor_error_code = array(FS001 => "ZERO_OFFSET_DRIFT_ERROR_CODE"
                       ,FS141 => "HIGH_INTERNAL_TEMPERATURE_ERROR_CODE"
                       ,FS151 => "HIGH_INTERNAL_HUMIDITY_ERROR_CODE"
                       ,FS140 => "LOW_INTERNAL_TEMPERATURE_ERROR_CODE"
                       ,FS150 => "LOW_INTERNAL_HUMIDITY_ERROR_CODE"
                       ,FM001 => "SD_CARD_FAILURE_ERROR_CODE"
                       ,FM002 => "SD_CARD_LOW_MEM_ERROR_CODE"
                      ) ;

?>