                        # UPDATE the SENSITITVITY values live!
                        config_pt_id = 1
                        mycursor.execute("UPDATE config_mv_ppb SET "
                        +"co_zero_offset=" + str(co_zero_offset)  + ","
                        +"co_sensitivity=" + str(co_sensitivity)  + ","
                        +"co_min_detection=" + str(co_min_detection)  + ","
                        +"co_max_detection=" + str(co_max_detection)  + ","
                        +"no2_zero_offset=" + str(no2_zero_offset)  + ","
                        +"no2_sensitivity=" + str(no2_sensitivity)  + ","
                        +"no2_min_detection=" + str(no2_min_detection)  + ","
                        +"no2_max_detection=" + str(no2_max_detection)  + ","
                        +"o3_zero_offset=" + str(o3_zero_offset)  + ","
                        +"o3_sensitivity=" + str(o3_sensitivity)  + ","
                        +"o3_min_detection=" + str(o3_min_detection)  + ","
                        +"o3_max_detection=" + str(o3_max_detection)  + ","
                        +"pm1_zero_offset=" + str(pm1_zero_offset)  + ","
                        +"pm1_sensitivity=" + str(pm1_sensitivity)  + ","
                        +"pm1_min_detection=" + str(pm1_min_detection)  + ","
                        +"pm1_max_detection=" + str(pm1_max_detection)  + ","
                        +"pm25_zero_offset=" + str(pm25_zero_offset)  + ","
                        +"pm25_sensitivity=" + str(pm25_sensitivity)  + ","
                        +"pm25_min_detection=" + str(pm25_min_detection)  + ","
                        +"pm25_max_detection=" + str(pm25_max_detection)  + ","
                        +"pm4_zero_offset=" + str(pm4_zero_offset)  + ","
                        +"pm4_sensitivity=" + str(pm4_sensitivity)  + ","
                        +"pm4_min_detection=" + str(pm4_min_detection)  + ","
                        +"pm4_max_detection=" + str(pm4_max_detection)  + ","
                        +"pm10_zero_offset=" + str(pm10_zero_offset)  + ","
                        +"pm10_sensitivity=" + str(pm10_sensitivity)  + ","
                        +"pm10_min_detection=" + str(pm10_min_detection)  + ","
                        +"pm10_max_detection=" + str(pm10_max_detection)
                        +"  where id=%s",(config_pt_id,))
                        conn.commit()

                        # UPDATE the SENSITITVITY values live!
                        config_pt_id = 2
                        mycursor.execute("UPDATE config_mv_ppb SET "
                        +"co_zero_offset=" + str(co_zero_offset)  + ","
                        +"co_sensitivity=" + str(co_sensitivity)  + ","
                        +"co_min_detection=" + str(co_min_detection)  + ","
                        +"co_max_detection=" + str(co_max_detection)  + ","
                        +"no2_zero_offset=" + str(no2_zero_offset)  + ","
                        +"no2_sensitivity=" + str(no2_sensitivity)  + ","
                        +"no2_min_detection=" + str(no2_min_detection)  + ","
                        +"no2_max_detection=" + str(no2_max_detection)  + ","
                        +"o3_zero_offset=" + str(o3_zero_offset)  + ","
                        +"o3_sensitivity=" + str(o3_sensitivity)  + ","
                        +"o3_min_detection=" + str(o3_min_detection)  + ","
                        +"o3_max_detection=" + str(o3_max_detection)  + ","
                        +"pm1_zero_offset=" + str(pm1_zero_offset)  + ","
                        +"pm1_sensitivity=" + str(pm1_sensitivity)  + ","
                        +"pm1_min_detection=" + str(pm1_min_detection)  + ","
                        +"pm1_max_detection=" + str(pm1_max_detection)  + ","
                        +"pm25_zero_offset=" + str(pm25_zero_offset)  + ","
                        +"pm25_sensitivity=" + str(pm25_sensitivity)  + ","
                        +"pm25_min_detection=" + str(pm25_min_detection)  + ","
                        +"pm25_max_detection=" + str(pm25_max_detection)  + ","
                        +"pm4_zero_offset=" + str(pm4_zero_offset)  + ","
                        +"pm4_sensitivity=" + str(pm4_sensitivity)  + ","
                        +"pm4_min_detection=" + str(pm4_min_detection)  + ","
                        +"pm4_max_detection=" + str(pm4_max_detection)  + ","
                        +"pm10_zero_offset=" + str(pm10_zero_offset)  + ","
                        +"pm10_sensitivity=" + str(pm10_sensitivity)  + ","
                        +"pm10_min_detection=" + str(pm10_min_detection)  + ","
                        +"pm10_max_detection=" + str(pm10_max_detection)
                        +"  where id=%s",(config_pt_id,))
                        conn.commit()
