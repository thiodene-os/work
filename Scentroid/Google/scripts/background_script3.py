import os
import csv
import time

array = []

a = 0

while a < 10000:
    array.append(['exhale value 1: ' + str(a), 'exhale value 2: ' + str(a), 'exhale value 3: ' + str(a)])
    time.sleep(.001)
    a += 1

name = './result_documents/output_data.csv'
try:
    os.remove(name)
except OSError:
    pass
with open(name, 'w') as csv_file:
    writer = csv.writer(csv_file, dialect='excel')
    for number in array:
        writer.writerow(number)
    csv_file.close()
