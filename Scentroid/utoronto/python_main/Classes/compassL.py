# ********************************************************************
#
#  $Id: helloworld.py 32630 2018-10-10 14:11:07Z seb $
#
#  An example that show how to use a  Yocto-3D
#
#  You can find more information on our web site:
#   Yocto-3D documentation:
#      https://www.yoctopuce.com/EN/products/yocto-3d/doc.html
#   Python API Reference
#      https://www.yoctopuce.com/EN/doc/reference/yoctolib-python-EN.html
#
# *********************************************************************

#!/usr/bin/python
# -*- coding: utf-8 -*-
import os, sys
import threading
import time
# add ../../Sources to the PYTHONPATH
sys.path.append(os.path.join("..", "..", "Sources"))

from yoctopuce.yocto_api import *
from yoctopuce.yocto_tilt import *
from yoctopuce.yocto_compass import *


class Compass:
    def connect(self):
        if(not self.connected):
            try:
                errmsg = YRefParam()
                if YAPI.RegisterHub("usb", errmsg) != YAPI.SUCCESS:
                    self.connected = False
                #print('Check USB for Compass')#sys.exit("init error" + errmsg.value)
                else:
                    self.connected = True
                anytilt = YTilt.FirstTilt()
                serial = anytilt.get_module().get_serialNumber()
                self.compass = YCompass.FindCompass(serial + ".compass")
            except:
                self.connected = False
        else:
            time.sleep(1)

    def startReading(self):
        self.connected = False
        t1 = threading.Thread(target=self.connect)
        t1.daemon = True
        t1.start()

    def getData(self) -> str:
        if(self.connected):
            try:
                return str(self.compass.get_currentValue())
            except:
                self.connected = False
                return ""
        else:
            self.connect()
            return ""
