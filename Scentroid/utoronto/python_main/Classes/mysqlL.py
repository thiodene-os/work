import mysql.connector
import datetime
from .config_aqi import *

# For today just build a string that will be added to the creation of Daily SESSION Table if not exists
# today = datetime.date.today()


# This Class defines the MySQL transactions for the main parameters to INSERT, UPDATE
class InsertMainDataSQL:
        lat = ""
        lon = ""
        wind_speed = ""
        wind_direction = ""
        vehicle_direction = ""
        timestamp = ""
        # This class function CREATEs the session Table, UPDATEs the config_aqi Table and INSERTs to the main tabele the GEO records
        def SQL_query_result(self):
                # Print a date string to create the new Daily SESSION Table
                today = datetime.datetime.today()
                date_str = today.strftime("%d%m%Y")
                err_date_str = today.strftime("%m/%d/%Y, %H:%M:%S")

                # Connect to Local MySQL
                try:
                        conn=mysql.connector.connect(user='scentroid',password='scentroid',host='localhost',database='utoronto')

                        mycursor=conn.cursor()

                        # Create a Session Table if needed for the day
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
                          +"`value1_co_ppm` float(8,3) NOT NULL DEFAULT 0,"
                          +"`value1_no2_mv` float(8,3) NOT NULL DEFAULT 0,"
                          +"`value1_no2_ppm` float(8,3) NOT NULL DEFAULT 0,"
                          +"`value1_o3_mv` float(8,3) NOT NULL DEFAULT 0,"
                          +"`value1_o3_ppm` float(8,3) NOT NULL DEFAULT 0,"
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
                          +"`value2_co_ppm` float(8,3) NOT NULL DEFAULT 0,"
                          +"`value2_no2_mv` float(8,3) NOT NULL DEFAULT 0,"
                          +"`value2_no2_ppm` float(8,3) NOT NULL DEFAULT 0,"
                          +"`value2_o3_mv` float(8,3) NOT NULL DEFAULT 0,"
                          +"`value2_o3_ppm` float(8,3) NOT NULL DEFAULT 0,"
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
                          +"co_max_good_ppm=" + str(co_max_good_ppm)  + ","
                          +"co_max_moderate_ppm=" + str(co_max_moderate_ppm)  + ","
                          +"co_max_sensitive_ppm=" + str(co_max_sensitive_ppm)  + ","
                          +"co_max_unhealthy_ppm=" + str(co_max_unhealthy_ppm)  + ","
                          +"co_max_very_ppm=" + str(co_max_very_ppm)  + ","
                          +"co_max_hazardous_ppm=" + str(co_max_hazardous_ppm)  + ","
                          +"no2_max_good_ppm=" + str(no2_max_good_ppm)  + ","
                          +"no2_max_moderate_ppm=" + str(no2_max_moderate_ppm)  + ","
                          +"no2_max_sensitive_ppm=" + str(no2_max_sensitive_ppm)  + ","
                          +"no2_max_unhealthy_ppm=" + str(no2_max_unhealthy_ppm)  + ","
                          +"no2_max_very_ppm=" + str(no2_max_very_ppm)  + ","
                          +"no2_max_hazardous_ppm=" + str(no2_max_hazardous_ppm)  + ","
                          +"o3_max_good_ppm=" + str(o3_max_good_ppm)  + ","
                          +"o3_max_moderate_ppm=" + str(o3_max_moderate_ppm)  + ","
                          +"o3_max_sensitive_ppm=" + str(o3_max_sensitive_ppm)  + ","
                          +"o3_max_unhealthy_ppm=" + str(o3_max_unhealthy_ppm)  + ","
                          +"o3_max_very_ppm=" + str(o3_max_very_ppm)  + ","
                          +"o3_max_hazardous_ppm=" + str(o3_max_hazardous_ppm)  + ","
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

                        # INSERT data to small_data and small_data_%%%%%% Tables
                        mycursor.execute("SELECT * FROM `small_data` WHERE status1 IS NOT NULL OR status2 IS NOT NULL")
                        mycursor.fetchall()
                        num_incomplete = mycursor.rowcount
                        conn.commit()
                        if num_incomplete == 0:
                                print("num:" + str(num_incomplete))
                                # If all rows are completed then create a new row of Main Data
                                if len(self.lat) == 0:
                                        query_lat = 'NULL'
                                else:
                                        query_lat = self.lat

                                if len(self.lon) == 0:
                                        query_lon = 'NULL'
                                else:
                                        query_lon= self.lon

                                if len(self.wind_speed) == 0:
                                        query_wspeed = '0'
                                else:
                                        query_wspeed= self.wind_speed

                                if len(self.wind_direction) == 0:
                                        query_wdir = '0'
                                else:
                                        query_wdir= self.wind_direction

                                if len(self.vehicle_direction) == 0:
                                        query_vdir = '0'
                                else:
                                        query_vdir= self.vehicle_direction


                                # Check a timestamp value first!
                                print("len:" + self.timestamp)
                                if len(self.timestamp) >= 10:
                                        mycursor.execute("INSERT INTO `small_data` (`lat`, `lon`,`wind_speed`,`wind_direction`,`vehicle_direction` ,`timestamp`) VALUES (%s,%s,%s,%s,%s,%s)",(query_lat,query_lon,query_wspeed, query_wdir, query_vdir, self.timestamp))
                                        conn.commit()

                                        mycursor.execute("INSERT INTO `small_data_" + date_str + "` (`lat`, `lon`,`wind_speed`,`wind_direction`,`vehicle_direction` ,`timestamp`) VALUES (%s,%s,%s,%s,%s,%s)",(query_lat,query_lon,query_wspeed, query_wdir, query_vdir, self.timestamp))
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


