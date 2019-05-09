                   if len(wind_direction) == 0:
                           query_wdir = "0"
                   else:
                           query_wdir= float(wind_direction) + 166
                           if query_wdir >= 360:
                                    query_wdir = query_wdir - 360

                   if len(vehicle_direction) == 0:
                           query_vdir = "0"
                   else:
                           query_vdir= float(vehicle_direction) + 59
                           if query_vdir >= 360:
                                    query_vdir = query_vdir - 360
