from config_aqi import *

# define the Vehicle class
class Gas:
    name = ""
    concentration = 0.00
    dataunit = ""
    max_good = 0.00
    max_moderate = 0.00
    max_sensitive = 0.00
    max_unhealthy = 0.00
    max_very = 0.00
    max_hazardous = 0.00
    def AQI_value(self):
        if self.concentration <= self.max_good:
            c_low = 0
            c_high = self.max_good
            i_low = 0
            i_high = 50
            if c_high == 0:
                aqi = 0
            else:
                aqi = round(((i_high - i_low)/(c_high - c_low) * (self.concentration - c_low)) + i_low)
            #print(aqi)
        elif self.concentration > self.max_good and self.concentration <= self.max_moderate:
            c_low = self.max_good
            c_high = self.max_moderate
            i_low = 51
            i_high = 100
            if (c_high - c_low) <= 0:
                aqi = 0
            else:
                aqi = round(((i_high - i_low)/(c_high - c_low) * (self.concentration - c_low)) + i_low)
            #print(aqi)
        elif self.concentration > self.max_moderate and self.concentration <= self.max_sensitive:
            c_low = self.max_moderate
            c_high = self.max_sensitive
            i_low = 101
            i_high = 150
            if (c_high - c_low) <= 0:
                aqi = 0
            else:
                aqi = round(((i_high - i_low)/(c_high - c_low) * (self.concentration - c_low)) + i_low)
            #print(aqi)
        elif self.concentration > self.max_sensitive and self.concentration <= self.max_unhealthy:
            c_low = self.max_sensitive
            c_high = self.max_unhealthy
            i_low = 151
            i_high = 200
            if (c_high - c_low) <= 0:
                aqi = 0
            else:
                aqi = round(((i_high - i_low)/(c_high - c_low) * (self.concentration - c_low)) + i_low)
            #print(aqi)
        elif self.concentration > self.max_unhealthy and self.concentration <= self.max_very:
            c_low = self.max_unhealthy
            c_high = self.max_very
            i_low = 201
            i_high = 300
            if (c_high - c_low) <= 0:
                aqi = 0
            else:
                aqi = round(((i_high - i_low)/(c_high - c_low) * (self.concentration - c_low)) + i_low)
            #print(aqi)
        elif self.concentration > self.max_very and self.concentration <= self.max_hazardous:
            c_low = self.max_very
            c_high = self.max_hazardous
            i_low = 301
            i_high = 500
            if (c_high - c_low) <= 0:
                aqi = 0
            else:
                aqi = round(((i_high - i_low)/(c_high - c_low) * (self.concentration - c_low)) + i_low)
            #print(aqi)
        elif self.concentration > self.max_hazardous:
            aqi = 500 #round(((i_high - i_low)/(c_high - c_low) * (concentration - c_low)) + i_low)

        #aqi = "%s is a %s %s worth $%.2f." % (self.name, self.color, self.kind, self.value)
        return aqi
# your code goes here
o3 = Gas()
o3.name = "O3"
o3.concentration = 5
o3.dataunit = "PPM"
o3.max_good = o3_max_good_ppm
o3.max_moderate = o3_max_moderate_ppm
o3.max_sensitive = o3_max_sensitive_ppm
o3.max_unhealthy = o3_max_unhealthy_ppm
o3.max_very = o3_max_very_ppm
o3.max_hazardous = o3_max_hazardous_ppm

no2 = Gas()
no2.name = "NO2"
no2.concentration = 11.7
no2.dataunit = "PPM"
no2.max_good = no2_max_good_ppm
no2.max_moderate = no2_max_moderate_ppm
no2.max_sensitive = no2_max_sensitive_ppm
no2.max_unhealthy = no2_max_unhealthy_ppm
no2.max_very = no2_max_very_ppm
no2.max_hazardous = no2_max_hazardous_ppm


pm1 = Gas()
pm1.name = "PM1"
pm1.concentration = 17.5
pm1.dataunit = "ug/m3"
pm1.max_good = pm1_max_good_ugm3
pm1.max_moderate = pm1_max_moderate_ugm3
pm1.max_sensitive = pm1_max_sensitive_ugm3
pm1.max_unhealthy = pm1_max_unhealthy_ugm3
pm1.max_very = pm1_max_very_ugm3
pm1.max_hazardous = pm1_max_hazardous_ugm3


# test code
print(o3.AQI_value())
print(no2.AQI_value())
print(pm1.AQI_value())
