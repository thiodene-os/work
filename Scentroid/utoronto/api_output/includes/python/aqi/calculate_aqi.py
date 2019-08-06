from config_aqi import *

# Define the function names
def calculateAQI(concentration,max_good,max_moderate,max_sensitive,max_unhealthy,max_very,max_hazardous):
    #Set up the important local variables
    if concentration <= max_good:
          c_low = 0
          c_high = max_good
          i_low = 0
          i_high = 50
          if c_high == 0:
              aqi = 0
          else:
              aqi = round(((i_high - i_low)/(c_high - c_low) * (concentration - c_low)) + i_low)
          #print(aqi)
    elif concentration > max_good and concentration <= max_moderate:
          c_low = max_good
          c_high = max_moderate
          i_low = 51
          i_high = 100
          if (c_high - c_low) <= 0:
              aqi = 0
          else:
              aqi = round(((i_high - i_low)/(c_high - c_low) * (concentration - c_low)) + i_low)
          #print(aqi)
    elif concentration > max_moderate and concentration <= max_sensitive:
          c_low = max_moderate
          c_high = max_sensitive
          i_low = 101
          i_high = 150
          if (c_high - c_low) <= 0:
              aqi = 0
          else:
              aqi = round(((i_high - i_low)/(c_high - c_low) * (concentration - c_low)) + i_low)
          #print(aqi)
    elif concentration > max_sensitive and concentration <= max_unhealthy:
          c_low = max_sensitive
          c_high = max_unhealthy
          i_low = 151
          i_high = 200
          if (c_high - c_low) <= 0:
              aqi = 0
          else:
              aqi = round(((i_high - i_low)/(c_high - c_low) * (concentration - c_low)) + i_low)
          #print(aqi)
    elif concentration > max_unhealthy and concentration <= max_very:
          c_low = max_unhealthy
          c_high = max_very
          i_low = 201
          i_high = 300
          if (c_high - c_low) <= 0:
              aqi = 0
          else:
              aqi = round(((i_high - i_low)/(c_high - c_low) * (concentration - c_low)) + i_low)
          #print(aqi)
    elif concentration > max_very and concentration <= max_hazardous:
          c_low = max_very
          c_high = max_hazardous
          i_low = 301
          i_high = 500
          if (c_high - c_low) <= 0:
              aqi = 0
          else:
             aqi = round(((i_high - i_low)/(c_high - c_low) * (concentration - c_low)) + i_low)
          #print(aqi)
    elif concentration > max_hazardous:
          aqi = 500 #round(((i_high - i_low)/(c_high - c_low) * (concentration - c_low)) + i_low)
          #print(aqi)

    return aqi



# print (o3_max_moderate_ppm)




