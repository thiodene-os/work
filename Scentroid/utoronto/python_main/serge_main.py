from Classes.gpsL import GPS
from Classes.windL import Wind
from Classes.compassL import Compass
from Classes.mysqlL import InsertMainDataSQL
import time


def main():
    try:
        test1 = GPS()
        test2 = Wind()
        test3 = Compass()

        test1.startReading()
        test2.startReading()
        test3.startReading()

        time.sleep(2)
        for x in range(30):
            #print('GPS: ' + test1.getData())
            print('Wind: ' + test2.getData())
            #print('Compass: ' + test3.getData())

            # Organize the GPS data
            gps_array = test1.getData().split(",")

            # Organize Wind data
            wind_array = test2.getData().split(",")

            # Fill in the data before executing the query
            data = InsertMainDataSQL()
            data.lat = gps_array[0]
            data.lon = gps_array[1]
            data.wind_speed = wind_array[0]
            data.wind_direction = wind_array[1]
            data.vehicle_direction = test3.getData()
            data.timestamp = gps_array[3]
            # Now query the Database and print the result on screen
            print(data.SQL_query_result())

            time.sleep(1)
            #time.sleep(0.5)
    except KeyboardInterrupt:
        exit

if __name__ == "__main__":
    main()
