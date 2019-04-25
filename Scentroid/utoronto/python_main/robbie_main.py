from Classes.gpsL import GPS
from Classes.windL import Wind
from Classes.compassL import Compass
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
            print('GPS: ' + test1.getData())
            print('Wind: ' + test2.getData())
            print('Compass: ' + test3.getData())
            time.sleep(2)
    except KeyboardInterrupt:
        exit

if __name__ == "__main__":
    main()
