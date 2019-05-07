from Classes.gpsL import GPS
from Classes.windL import Wind
from Classes.compassL import Compass
from Classes.config_aqi import *
import time
import mysql.connector
import datetime

# This function saves the main data (Timestamp, Lat, Lon, Vehicle Direction) waiting for the 2 polluTrackers to complete the line with UPDATE      
def SQL_query_result(conn,mycursor,lat,lon,wind_speed,wind_direction,vehicle_direction,timestamp):
       # Print a date string to create the new Daily SESSION Table
       today = datetime.datetime.today()
       date_str = today.strftime("%d%m%Y")
       err_date_str = today.strftime("%m/%d/%Y, %H:%M:%S")

       # Executes main MySQL queries  
       try:
           # CREATE a Session Table if needed for the day
           mycursor.execute("CREATE TABLE IF NOT EXISTS `small_data_" + date_str + "` ("
           + "`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
           +"`lat` float(10,6) DEFAULT NULL,"
           +"`lon` float(10,6) DEFAULT NULL,"
           +"`wind_speed` float DEFAULT 0,"
           +"`wind_direction` float NOT NULL DEFAULT 0,"
           +"`vehicle_speed` float NOT NULL DEFAULT 0,"
           +"`vehicle_direction` float NOT NULL DEFAULT 0,"
           +"`value1_lat` float(10,6) DEFAULT NULL,"
           +"`value1_lon` float(10,6) DEFAULT NULL,"
           +"`value1_co_mv` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_co_ppb` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_no2_mv` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_no2_ppb` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_o3_mv` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_o3_ppb` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_pm1_ugm3` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_pm25_ugm3` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_pm4_ugm3` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_pm10_ugm3` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_temp` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_humid` float(8,3) NOT NULL DEFAULT 0,"
           +"`value1_aqi` float(4,1) DEFAULT NULL,"
           +"`value2_lat` float(10,6) DEFAULT NULL,"
           +"`value2_lon` float(10,6) DEFAULT NULL,"
           +"`value2_co_mv` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_co_ppb` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_no2_mv` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_no2_ppb` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_o3_mv` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_o3_ppb` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_pm1_ugm3` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_pm25_ugm3` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_pm4_ugm3` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_pm10_ugm3` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_temp` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_humid` float(8,3) NOT NULL DEFAULT 0,"
           +"`value2_aqi` float(4,1) DEFAULT NULL,"
           +"`timestamp` varchar(10) NOT NULL DEFAULT 0"
           +") ENGINE=InnoDB DEFAULT CHARSET=latin1;")
           conn.commit()


           # UPDATE the AQI values live!
           config_id = 1
           mycursor.execute("UPDATE config_aqi SET "
           +"co_max_good_ppb=" + str(co_max_good_ppb)  + ","
           +"co_max_moderate_ppb=" + str(co_max_moderate_ppb)  + ","
           +"co_max_sensitive_ppb=" + str(co_max_sensitive_ppb)  + ","
           +"co_max_unhealthy_ppb=" + str(co_max_unhealthy_ppb)  + ","
           +"co_max_very_ppb=" + str(co_max_very_ppb)  + ","
           +"co_max_hazardous_ppb=" + str(co_max_hazardous_ppb)  + ","
           +"no2_max_good_ppb=" + str(no2_max_good_ppb)  + ","
           +"no2_max_moderate_ppb=" + str(no2_max_moderate_ppb)  + ","
           +"no2_max_sensitive_ppb=" + str(no2_max_sensitive_ppb)  + ","
           +"no2_max_unhealthy_ppb=" + str(no2_max_unhealthy_ppb)  + ","
           +"no2_max_very_ppb=" + str(no2_max_very_ppb)  + ","
           +"no2_max_hazardous_ppb=" + str(no2_max_hazardous_ppb)  + ","
           +"o3_max_good_ppb=" + str(o3_max_good_ppb)  + ","
           +"o3_max_moderate_ppb=" + str(o3_max_moderate_ppb)  + ","
           +"o3_max_sensitive_ppb=" + str(o3_max_sensitive_ppb)  + ","
           +"o3_max_unhealthy_ppb=" + str(o3_max_unhealthy_ppb)  + ","
           +"o3_max_very_ppb=" + str(o3_max_very_ppb)  + ","
           +"o3_max_hazardous_ppb=" + str(o3_max_hazardous_ppb)  + ","
           +"pm1_max_good_ugm3=" + str(pm1_max_good_ugm3)  + ","
           +"pm1_max_moderate_ugm3=" + str(pm1_max_moderate_ugm3)  + ","
           +"pm1_max_sensitive_ugm3=" + str(pm1_max_sensitive_ugm3)  + ","
           +"pm1_max_unhealthy_ugm3=" + str(pm1_max_unhealthy_ugm3)  + ","
           +"pm1_max_very_ugm3=" + str(pm1_max_very_ugm3)  + ","
           +"pm1_max_hazardous_ugm3=" + str(pm1_max_hazardous_ugm3)  + ","
           +"pm25_max_good_ugm3=" + str(pm25_max_good_ugm3)  + ","
           +"pm25_max_moderate_ugm3=" + str(pm25_max_moderate_ugm3)  + ","
           +"pm25_max_sensitive_ugm3=" + str(pm25_max_sensitive_ugm3)  + ","
           +"pm25_max_unhealthy_ugm3=" + str(pm25_max_unhealthy_ugm3)  + ","
           +"pm25_max_very_ugm3=" + str(pm25_max_very_ugm3)  + ","
           +"pm25_max_hazardous_ugm3=" + str(pm25_max_hazardous_ugm3)  + ","
           +"pm4_max_good_ugm3=" + str(pm4_max_good_ugm3)  + ","
           +"pm4_max_moderate_ugm3=" + str(pm4_max_moderate_ugm3)  + ","
           +"pm4_max_sensitive_ugm3=" + str(pm4_max_sensitive_ugm3)  + ","
           +"pm4_max_unhealthy_ugm3=" + str(pm4_max_unhealthy_ugm3)  + ","
           +"pm4_max_very_ugm3=" + str(pm4_max_very_ugm3)  + ","
           +"pm4_max_hazardous_ugm3=" + str(pm4_max_hazardous_ugm3)  + ","
           +"pm10_max_good_ugm3=" + str(pm10_max_good_ugm3)  + ","
           +"pm10_max_moderate_ugm3=" + str(pm10_max_moderate_ugm3)  + ","
           +"pm10_max_sensitive_ugm3=" + str(pm10_max_sensitive_ugm3)  + ","
           +"pm10_max_unhealthy_ugm3=" + str(pm10_max_unhealthy_ugm3)  + ","
           +"pm10_max_very_ugm3=" + str(pm10_max_very_ugm3)  + ","
           +"pm10_max_hazardous_ugm3=" + str(pm10_max_hazardous_ugm3)
           +"  where id=%s",(config_id,))
           conn.commit()

           # Check if the Data Handler has been set to Receiving / If not don't get any Data
           mycursor.execute("SELECT id FROM `data_handling` WHERE handler IS NOT NULL LIMIT 1")                 
           data_receiving = mycursor.rowcount
           conn.commit()
           if data_receiving == 0:
                   # Data currently not recording / It has to be switched on by the Front-End
                   print("Not recording Data!")
           else:
                   # INSERT data to small_data and small_data_%%%%%% Tables
                   mycursor.execute("SELECT id,timestamp FROM `small_data` WHERE status1 IS NOT NULL OR status2 IS NOT NULL LIMIT 1")
                   row = mycursor.fetchone()
                   num_incomplete = mycursor.rowcount
                   conn.commit()
                   if len(lat) == 0:
                           query_lat = None
                   else:
                           query_lat = lat

                   if len(lon) == 0:
                           query_lon = None
                   else:
                           query_lon= lon

                   if len(wind_speed) == 0:
                           query_wspeed = "0"
                   else:
                           query_wspeed= wind_speed

                   if len(wind_direction) == 0:
                           query_wdir = "0"
                   else:
                           query_wdir= wind_direction

                   if len(vehicle_direction) == 0:
                           query_vdir = "0"
                   else:
                           query_vdir= vehicle_direction
                   # Check the number of incomplete rows in small_data table 
                   if num_incomplete == 0:
                           print("num:" + str(num_incomplete))
                           # If all rows are completed then create a new row of Main Data
                           # Check a timestamp value first!
                           print("len:" + timestamp)
                           if len(timestamp) >= 10:
                                   print("Inserted")
                                   mycursor.execute("INSERT INTO `small_data` (`lat`, `lon`,`wind_speed`,`wind_direction`,`vehicle_direction` ,`timestamp`) VALUES (%s,%s,%s,%s,%s,%s)",(query_lat,query_lon,query_wspeed, query_wdir, query_vdir, timestamp))
                                   conn.commit()

                                   mycursor.execute("INSERT INTO `small_data_" + date_str + "` (`lat`, `lon`,`wind_speed`,`wind_direction`,`vehicle_direction` ,`timestamp`) VALUES (%s,%s,%s,%s,%s,%s)",(query_lat,query_lon,query_wspeed, query_wdir, query_vdir, timestamp))
                                   conn.commit()
                           else:
                                   # Get the machine timestamp instead and INSERT the record    
                                   tmstp_str = str(int(time.time()))
                                   print("Inserted No GPS")
                                   mycursor.execute("INSERT INTO `small_data` (`lat`, `lon`,`wind_speed`,`wind_direction`,`vehicle_direction` ,`timestamp`) VALUES (%s,%s,%s,%s,%s,%s)",(query_lat,query_lon,query_wspeed, query_wdir,query_vdir, tmstp_str))
                                   conn.commit()

                                   mycursor.execute("INSERT INTO `small_data_" + date_str + "` (`lat`, `lon`,`wind_speed`,`wind_direction`,`vehicle_direction` ,`timestamp`) VALUES (%s,%s,%s,%s,%s,%s)",(query_lat,query_lon,query_wspeed, query_wdir,query_vdir, tmstp_str))
                                   conn.commit()
                   else:
                           # If the number of incomplete rows isnt zero but the status is unchanged for too long, go on and INSERT a new row 
                           # Simply UPDATE the receiving status of that row to NULL for the 2 pollu trackers
                           receiving_wait_time = 2
                           receiving_id = row[0]
                           receiving_tmstp = row[1]
                           # get the timestamp for NOW
                           now_tmstp = int(time.time())
                           if (now_tmstp - int(receiving_tmstp)) > receiving_wait_time:     
                                   # UPDATE the present record, set the 2 status to NULL for the data collector moves on     
                                   mycursor.execute("UPDATE small_data SET status1=NULL, status2=NULL WHERE id=%s",(receiving_id,))            
                                   conn.commit()     
                                   # INSERT new small data record after the status UPDATE                                              
                                   mycursor.execute("INSERT INTO `small_data` (`lat`, `lon`,`wind_speed`,`wind_direction`,`vehicle_direction` ,`timestamp`) VALUES (%s,%s,%s,%s,%s,%s)",(query_lat,query_lon,query_wspeed, query_wdir,query_vdir, now_tmstp))
                                   conn.commit()

                                   mycursor.execute("INSERT INTO `small_data_" + date_str + "` (`lat`, `lon`,`wind_speed`,`wind_direction`,`vehicle_direction` ,`timestamp`) VALUES (%s,%s,%s,%s,%s,%s)",(query_lat,query_lon,query_wspeed, query_wdir,query_vdir, now_tmstp))
                                   conn.commit()
         
           return "OK"

       # If Try MySQL not succesful handle the error
       except mysql.connector.Error as err:
            with open("error.log", "a") as f:
                    f.write("[" + err_date_str + "] " + str(err) + "\n")
                    #print(err)
                    #print("Error Code:", err.errno)
                    #print("SQLSTATE", err.sqlstate)
                    #print("Message", err.msg)
                    return "Not OK"



def main():
    try:
        conn=mysql.connector.connect(user='scentroid',password='scentroid',host='localhost',database='utoronto')

        mycursor=conn.cursor()

        # First switch off the data receiving which will be turned on on-click by Front-end waiting for command in the WHILE loop!
        handler_id = 1
        mycursor.execute("UPDATE data_handling SET handler=NULL"
        +"  where id=%s",(handler_id,))
        conn.commit()


        test1 = GPS()
        test2 = Wind()         
        test3 = Compass()
        #data = InsertMainDataSQL()

        test1.startReading()
        test2.startReading()
        test3.startReading()

        time.sleep(2)
        while True:
#        for x in range(500):
            #print('GPS: ' + test1.getData())
            print('Wind: ' + test2.getData())
            #print('Compass: ' + test3.getData())

            # Organize the GPS data 
            gps_array = test1.getData().split(",") 

            # Organize Wind data 
            wind_array = test2.getData().split(",") 

            # Fill in the data before executing the query 
            #msg = InsertMainDataSQL(lat,lon,wind_speed,wind_direction,vehicle_direction,timestamp)
            lat = gps_array[0] 
            lon = gps_array[1] 
            wind_speed = wind_array[0] 
            wind_direction = wind_array[1] 
            vehicle_direction = test3.getData() 
            timestamp = gps_array[3]   
            msg = SQL_query_result(conn,mycursor,lat,lon,wind_speed,wind_direction,vehicle_direction,timestamp)
            # Now query the Database and print the result on screen
            #print(msg)

            #time.sleep(1)
            time.sleep(0.5)
        conn.close()
    except KeyboardInterrupt:
        exit

if __name__ == "__main__":
    main()
