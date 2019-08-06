from config_aqi import *
from calculate_aqi import calculateAQI

# Calculate for Specified GAS
gas = "O3"
cc = 5
aqi = calculateAQI(cc,o3_max_good_ppm,o3_max_moderate_ppm,o3_max_sensitive_ppm,o3_max_unhealthy_ppm,o3_max_very_ppm,o3_max_hazardous_ppm)

print(gas + ": AQI= " + str(aqi))

