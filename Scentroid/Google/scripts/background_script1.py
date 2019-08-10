import os
import csv
import time

array = []

a = 0

while a < 1000:
    array.append(['absolute zero value 1: ' + str(a), 'absolute zero value 2: ' + str(a), 'absolute zero value 3: ' + str(a)])
    time.sleep(.001)
    a += 1

name = './result_documents/output_absolute_zeros.csv'
try:
    os.remove(name)
except OSError:
    pass
with open(name, 'w') as csv_file:
    writer = csv.writer(csv_file, dialect='excel')
    for number in array:
        writer.writerow(number)
    csv_file.close()
