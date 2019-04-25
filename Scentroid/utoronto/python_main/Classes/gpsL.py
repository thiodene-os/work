import time
from serial import Serial
import datetime
import threading

def timestamp(s):
    '''
    Converts a timestamp given in "hhmmss[.ss]" ASCII text format to a
    datetime.time object
    '''
    ms_s = s[6:]
    ms = ms_s and int(float(ms_s) * 1000000) or 0

    t = datetime.time(
        hour=int(s[0:2]),
        minute=int(s[2:4]),
        second=int(s[4:6])#,
        #microsecond=ms
        )
    return t


def datestamp(s):
    '''
    Converts a datestamp given in "DDMMYY" ASCII text format to a
    datetime.datetime object
    '''
    return datetime.datetime.strptime(s, '%d%m%y').date()

class GPS:
    def __init__(self):
        self.lat = 0.0            # Instance Variable
        self.lon = 0.0
        self.alt = 0.0
        self.time = 0
        self.date = 0
        self.epoch = 0
    
    def connect(self):
        try:
            self.ser = Serial(
                "/dev/ttyUSB0",\
                # port = 'COM34',\
                baudrate = 4800#,\
                #parity = serial.PARITY_NONE,\
                #stopbits = serial.STOPBITS_ONE,\
                #bytesize = serial.EIGHTBITS,\
                #timeout = 0 )
                )
            self.connected = True
        except:
            self.connected = False

    def startReading(self):
        self.connect()
        t1 = threading.Thread(target=self._read)
        t1.daemon = True
        t1.start()

    def _read(self):
        y = False
        lat = 0.0 
        while(True): 
            try:
                x = self.ser.read()
                while (True):
                        while (y == False):
                            if (self.ser.in_waiting):
                                x = self.ser.read()
                                if x == b'\n':
                                    y = True
                        if (self.ser.in_waiting):
                            time.sleep(0.15)
                            data = self.ser.readline().decode('utf-8')
                            splitline = data.split(',')
                            if (splitline[0] == '$GPGGA'):
                                try:
                                    self.lat = float(splitline[2])
                                    if(splitline[3] == 'S'):
                                        self.lat = -self.lat
                                except ValueError:
                                    pass
                                    #print('Error: Received Lat: ' + str(self.lat))
                                try:
                                    self.lon = float(splitline[4])
                                    if(splitline[5] == 'W'):
                                        self.lon = -self.lon
                                except ValueError:
                                    pass
                                    #print('Error: Received Lon: ' + str(self.lon))
                                try:
                                    self.alt = float(splitline[9])
                                except ValueError:
                                    pass
                                    #print('Error: Received Alt: ' + str(self.alt))
                                #print('GPS Quality:' + splitline[6])
                                #print('Number of satellites in use: ' + splitline[7])
                                #print("")
                            if (splitline[0] == '$GPRMC'):
                                try:
                                    self.time = timestamp(splitline[1])
                                except:
                                    pass
                                    #print(Error)
                                try:
                                    self.date = datestamp(splitline[9])
                                except:
                                    pass#print(Error)
                                try:
                                    self.epoch = int((datetime.datetime.combine(self.date, self.time)-datetime.datetime(1970,1,1)).total_seconds())
                                except:
                                    pass#print(Error)

            except KeyboardInterrupt:
                exit
            except:
                try:
                    self.ser.close()
                except:
                    pass
                self.connect()
                time.sleep(1)

    def getData(self):
        if(self.connected):
            return str(self.lat) + "," + str(self.lon) + "," + str(self.alt) + "," + str(self.epoch)
        else:
            return ",,,"

