import time
from serial import Serial
import threading

class Wind:
    def __init__(self):
        self.speed = 0.0
        self.direction = 0.0

    def connect(self):
        try:        
            self.ser = Serial(
                "/dev/ttyUSB1",\
                baudrate = 9600,\
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
        EOL = False
        lat = 0.0
        try:
            while(True):
                try:
                    if(self.connected):
                        x = self.ser.read()
                        #while (True):
                        while (EOL == False):
                        #try:
                            if (self.ser.in_waiting):
                                x = self.ser.read()
                                if x == b'\n':
                                    EOL = True
                        #except:
                         #   self.ser.close()
                          #  self.connect()
                    try:                     
                        if (self.ser.in_waiting):
                            time.sleep(0.15)
                            data = self.ser.readline().decode('utf-8')
                            #print(data)
                            splitline = data.split(',')
                            if (splitline[0] == '\x02Q'):
                                try:
                                    self.direction = float(splitline[1])
                                except ValueError:
                                    pass#print('Error: Received Dir: ' + splitline[1])
                                try:
                                    self.speed = float(splitline[2])
                                except ValueError:
                                    pass#print('Error: Received Spd: ' + splitline[2])
                    except:
                        self.ser.close()
                        self.connect()
                        time.sleep(1)
                    else:
                        try:
                            self.ser.close()
                        except:
                            pass
                    self.connect()
                except:
                    try:
                        self.ser.close()
                    except:
                        pass
                    self.connect()
                    time.sleep(1)
        except KeyboardInterrupt:
            exit

    def getData(self) -> str:
        if(self.connected):
            return str(self.speed) + "," + str(self.direction)
        else:
            return ","
# def main():
#     try:
#         test = Wind()
#         test.startReading()
#         time.sleep(5)
#         for x in range(10):
#             print(test.getData())
#             time.sleep(2)
#     except KeyboardInterrupt:
#         exit
#
# if __name__ == "__main__":
#     main()
