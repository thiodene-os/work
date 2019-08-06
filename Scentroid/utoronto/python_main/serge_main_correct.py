                   if len(wind_direction) == 0:
                           query_wdir = "0"
                   else:
                           adj_query_wdir= float(wind_direction) + 166
                           if adj_query_wdir >= 360:
                                    adj_query_wdir = adj_query_wdir - 360
                           query_wdir = str(adj_query_wdir)

                   if len(vehicle_direction) == 0:
                           query_vdir = "0"
                   else:
                           adj_query_vdir= float(vehicle_direction) + 59
                           if adj_query_vdir >= 360:
                                    adj_query_vdir = adj_query_vdir - 360
                           query_vdir = str(adj_query_vdir)
