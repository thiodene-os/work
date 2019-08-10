import os
import csv
import time
import sys
sys.path.insert(0, 'root/home/pi/scentroid/Google/ids')
from scripts.config import config_current_id

array = []
print config_current_id
a = 0

while a < 1000:
    array.append(['absolute zero value 1: ' + str(a), 'absolute zero value 2: ' + str(a), 'absolute zero value 3: ' + str(a)])
    time.sleep(.001)
    a += 1

name = '/home/pi/scentroid/Google/result_documents/live/output_absolute_zeros_' + str(config_current_id) + '.csv'
try:
    os.remove(name)
except OSError:
    pass
with open(name, 'w') as csv_file:
    writer = csv.writer(csv_file, dialect='excel')
    for number in array:
        writer.writerow(number)
    csv_file.close()
