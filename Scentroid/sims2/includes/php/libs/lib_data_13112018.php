<?php

// Main libraries for Data processing features

// AQI for each compound that are relevant in the last 1Hour/8Hours/24Hours
function calculatePollutantAQI($pollutant, $data_unit, $concentration, $time_avg)
{
  $aqi = false ;
  
  $pollutant = trim(strtolower($pollutant)) ;
  $data_unit = trim(strtolower($data_unit)) ;
  
  // Based on the relevant pollutant calculate AQI
  if ($pollutant == 'o3') // Ozone | O3 (1Hour Average, PPB) || Ozone | O3 (8Hour Average, PPB)
  {
    // If data Unit different from PPB recalculate
    if ($data_unit == 'ppm')
      $concentration = $concentration * 1000; // PPB
    else if ($data_unit == 'ug/m3')
    {
      $molecular_weight = 3 * 16 ; // g/mol
      $concentration = 24.45 * $concentration / $molecular_weight ; // PPB
    }
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 1) // Time Average 1Hour
    {
      // From concentration in PPB get the concentration low and high for that concentration
      if ($concentration < 125)
      {
        $aqi = false ;
      }
      elseif ($concentration >= 125 && $concentration <= 164) // 125-164 (1-hr)
      {
        $c_low = 125 ; $c_high = 164 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 165 && $concentration <= 204) // 165-204 (1-hr)
      {
        $c_low = 165 ; $c_high = 204 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 205 && $concentration <= 404) // 205-404 (1-hr)
      {
        $c_low = 205 ; $c_high = 404 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 405 && $concentration <= 504) // 405-504 (1-hr)
      {
        $c_low = 405 ; $c_high = 504 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 505 && $concentration <= 604) // 505-604 (1-hr)
      {
        $c_low = 505 ; $c_high = 604 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ;
      }
      
    }
    else if ($time_avg == 8) // Time Average 8Hour
    {
      if ($concentration <= 54) // 0-54 (8-hr)
      {
        $c_low = 0 ; $c_high = 54 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 55 && $concentration <= 70) // 55-70 (8-hr)
      {
        $c_low = 55 ; $c_high = 70 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 71 && $concentration <= 85) // 71-85 (8-hr)
      {
        $c_low = 71 ; $c_high = 85 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 86 && $concentration <= 105) // 86-105 (8-hr)
      {
        $c_low = 86 ; $c_high = 105 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 106 && $concentration <= 200) // 106-200 (8-hr)
      {
        $c_low = 106 ; $c_high = 200 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
      
    }
  }
  else if ($pollutant == 'pm2.5')   // PM2.5      (24Hour Average, ug/m3)
  {
    // If data Unit different from ug/m3 recalculate (There is not so far...)
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 24) // Time Average 1Hour
    {
    
      // From concentration in ug/m3 get the concentration low and high for that concentration
      if ($concentration <= 12.0) // 0.0-12.0 (24-hr)
      {
        $c_low = 0 ; $c_high = 12.0 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 12.1 && $concentration <= 35.4) // 12.1-35.4 (24-hr)
      {
        $c_low = 12.1 ; $c_high = 35.4 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 35.5 && $concentration <= 55.4) // 35.5-55.4 (24-hr)
      {
        $c_low = 35.5 ; $c_high = 55.4 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 55.5 && $concentration <= 150.4) // 55.5-150.4 (24-hr)
      {
        $c_low = 55.5 ; $c_high = 150.4 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 150.5 && $concentration <= 250.4) // 150.5-250.4 (24-hr)
      {
        $c_low = 150.5 ; $c_high = 250.4 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 250.5 && $concentration <= 350.4) // 250.5-350.4 (24-hr)
      {
        $c_low = 250.5 ; $c_high = 350.4 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 350.5 && $concentration <= 500.4) // 350.5-500.4 (24-hr)
      {
        $c_low = 350.5 ; $c_high = 500.4 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
    
    }
    
  }
  else if ($pollutant == 'pm10')   // PM10      (24Hour Average, ug/m3)
  {
    // If data Unit different from ug/m3 recalculate (There is not so far...)
    
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 24) // Time Average 1Hour
    {
      // From concentration in ug/m3 get the concentration low and high for that concentration
      if ($concentration <= 54) // 0-54 (24-hr)
      {
        $c_low = 0 ; $c_high = 54 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 55 && $concentration <= 154) // 55-154 (24-hr)
      {
        $c_low = 55 ; $c_high = 154 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 155 && $concentration <= 254) // 155-254 (24-hr)
      {
        $c_low = 155 ; $c_high = 254 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 255 && $concentration <= 354) // 255-354 (24-hr)
      {
        $c_low = 255 ; $c_high = 354 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 355 && $concentration <= 424) // 355-424 (24-hr)
      {
        $c_low = 355 ; $c_high = 424 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 425 && $concentration <= 504) // 425-504 (24-hr)
      {
        $c_low = 425 ; $c_high = 504 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 505 && $concentration <= 604) // 505-604 (24-hr)
      {
        $c_low = 505 ; $c_high = 604 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
    
    }
    
  }
  else if ($pollutant == 'co')   // Carbon Monoxide | CO (8Hour Average, PPM)
  {
    // If data Unit different from PPM recalculate
    if ($data_unit == 'ppb')
      $concentration = $concentration / 1000; // PPM
    else if ($data_unit == 'ug/m3')
    {
      $molecular_weight = 12 + 16 ; // g/mol
      $concentration = 24.45 * $concentration / $molecular_weight * 1000 ; // PPM
    }
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 8) // Time Average 1Hour
    {
    
      // From concentration in PPM get the concentration low and high for that concentration
      if ($concentration <= 4.4) // 0.0-4.4 (8-hr)
      {
        $c_low = 0 ; $c_high = 4.4 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 4.5 && $concentration <= 9.4) // 4.5-9.4 (8-hr)
      {
        $c_low = 4.5 ; $c_high = 9.4 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 9.5 && $concentration <= 12.4) // 9.5-12.4 (8-hr)
      {
        $c_low = 9.5 ; $c_high = 12.4 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 12.5 && $concentration <= 15.4) // 12.5-15.4 (8-hr)
      {
        $c_low = 12.5 ; $c_high = 15.4 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 15.5 && $concentration <= 30.4) // 15.5-30.4 (8-hr)
      {
        $c_low = 15.5 ; $c_high = 30.4 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 30.5 && $concentration <= 40.4) // 30.5-40.4 (8-hr)
      {
        $c_low = 30.5 ; $c_high = 40.4 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 40.5 && $concentration <= 50.4) // 40.5-50.4 (8-hr)
      {
        $c_low = 40.5 ; $c_high = 50.4 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
    
    }
    
  }
  else if ($pollutant == 'so2')  // Sulfur Dioxide | SO2 (1Hour Average, PPB)
  {
    // If data Unit different from PPM recalculate
    if ($data_unit == 'ppm')
      $concentration = $concentration * 1000; // PPB
    else if ($data_unit == 'ug/m3')
    {
      $molecular_weight = 32 + (16 * 2) ; // g/mol
      $concentration = 24.45 * $concentration / $molecular_weight ; // PPB
    }
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 1) // Time Average 1Hour
    {
      // From concentration in PPB get the concentration low and high for that concentration
      if ($concentration <= 35) // 0-35 (1-hr)
      {
        $c_low = 0 ; $c_high = 35 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 36 && $concentration <= 75) // 36-75 (1-hr)
      {
        $c_low = 36 ; $c_high = 75 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 76 && $concentration <= 185) // 76-185 (1-hr)
      {
        $c_low = 76 ; $c_high = 185 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 186 && $concentration <= 304) // 186-304 (1-hr)
      {
        $c_low = 186 ; $c_high = 304 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ;
      }
      
    }
    else if ($time_avg == 24) // Time Average 24Hour
    {
      if ($concentration >= 305 && $concentration <= 604) // 305-604 (24-hr)
      {
        $c_low = 305 ; $c_high = 604 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 605 && $concentration <= 804) // 605-804 (24-hr)
      {
        $c_low = 605 ; $c_high = 804 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 805 && $concentration <= 1004) // 805-1004 (24-hr)
      {
        $c_low = 805 ; $c_high = 1004 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
      
    }
    
  }
  else if ($pollutant == 'no2')  // Nitrogen Dioxide | NO2 (1Hour Average, PPB)
  {
    // If data Unit different from PPM recalculate
    if ($data_unit == 'ppm')
      $concentration = $concentration * 1000; // PPB
    else if ($data_unit == 'ug/m3')
    {
      $molecular_weight = 14 + (16 * 2) ; // g/mol
      $concentration = 24.45 * $concentration / $molecular_weight ; // PPB
    }
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 1) // Time Average 1Hour
    {
    
      // From concentration in PPM get the concentration low and high for that concentration
      if ($concentration <= 53) // 00-53 (1-hr)
      {
        $c_low = 0 ; $c_high = 53 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 54 && $concentration <= 100) // 54-100 (1-hr)
      {
        $c_low = 54 ; $c_high = 100 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 101 && $concentration <= 360) // 101-360 (1-hr)
      {
        $c_low = 101 ; $c_high = 360 ; $i_low = 101 ; $i_high = 150 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 361 && $concentration <= 649) // 361-649 (1-hr)
      {
        $c_low = 361 ; $c_high = 649 ; $i_low = 151 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 650 && $concentration <= 1249) // 650-1249 (1-hr)
      {
        $c_low = 650 ; $c_high = 1249 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 1250 && $concentration <= 1649) // 1250-1649 (1-hr)
      {
        $c_low = 1250 ; $c_high = 1649 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 1650 && $concentration <= 2049) // 1650-2049 (1-hr)
      {
        $c_low = 1650 ; $c_high = 2049 ; $i_low = 401 ; $i_high = 500 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
    
    }
    
  }
  else if ($pollutant == 'nh3')  // Ammonia | NH3 (24Hour Average, ug/m3)
  {
    // If data Unit different from PPM recalculate
    $molecular_weight = 14 + (1 * 3) ; // g/mol
    if ($data_unit == 'ppm')
      $concentration = 0.0409 * $concentration * $molecular_weight * 1000 ; // ug/m3
    else if ($data_unit == 'ppb')
      $concentration = 0.0409 * $concentration * $molecular_weight ; // ug/m3
    
    // Now use the Time Average to calculate the AQI
    if ($time_avg == 24) // Time Average 1Hour
    {
      
      // From concentration in PPM get the concentration low and high for that concentration
      if ($concentration <= 200) // 0-200 (24-hr)
      {
        $c_low = 0 ; $c_high = 200 ; $i_low = 0 ; $i_high = 50 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 201 && $concentration <= 400) // 201–400 (24-hr)
      {
        $c_low = 201 ; $c_high = 400 ; $i_low = 51 ; $i_high = 100 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 401 && $concentration <= 800) // 401–800 (24-hr)
      {
        $c_low = 401 ; $c_high = 800 ; $i_low = 101 ; $i_high = 200 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 801 && $concentration <= 1200) // 801–1200 (24-hr)
      {
        $c_low = 801 ; $c_high = 1200 ; $i_low = 201 ; $i_high = 300 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      elseif ($concentration >= 1201 && $concentration <= 1800) // 1201–1800 (24-hr)
      {
        $c_low = 1201 ; $c_high = 1800 ; $i_low = 301 ; $i_high = 400 ;
        $aqi = round((($i_high - $i_low)/($c_high - $c_low) * ($concentration - $c_low)) + $i_low) ; // calculate AQI
      }
      else
      {
        $aqi = false ; // calculate AQI
      }
    
    }
    
  }
  
  return $aqi ;
  
} // calculatePollutantAQI

// This function provides the right image for the weather data table
function addMetDataIconToParameter($met_parameter)
{
  // Get the useful part of the parameter name
  $met_parameter = strtolower($met_parameter) ;
  
  switch ($met_parameter)
  {
    case 'internal temperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'internaltemperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'external temperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'externaltemperature': $met_icon = 'icon_temp_inv.png' ; break ;
    case 'internal humidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'internalhumidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'external humidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'externalhumidity': $met_icon = 'icon_hum_inv.png' ; break ;
    case 'daily rain': $met_icon = 'icon_rain_inv.png' ; break ;
    case 'dailyrain': $met_icon = 'icon_rain_inv.png' ; break ;
    case 'uv radiation': $met_icon = 'icon_uv_inv.png' ; break ;
    case 'uvradiation': $met_icon = 'icon_uv_inv.png' ; break ;
    case 'uv sensor': $met_icon = 'icon_uv_inv.png' ; break ;
    case 'uvsensor': $met_icon = 'icon_uv_inv.png' ; break ;
    case 'noise sensor': $met_icon = 'icon_noise_inv.png' ; break ;
    case 'noisesensor': $met_icon = 'icon_noise_inv.png' ; break ;
    case 'noise': $met_icon = 'icon_noise_inv.png' ; break ;
    case 'solar radiation': $met_icon = 'icon_sun_inv.png' ; break ;
    case 'solarradiation': $met_icon = 'icon_sun_inv.png' ; break ;
    case 'barometric pressure': $met_icon = 'icon_press_inv.png' ; break ;
    case 'barometricpressure': $met_icon = 'icon_press_inv.png' ; break ;
    case 'pressure': $met_icon = 'icon_press_inv.png' ; break ;
    case 'wind speed': $met_icon = 'icon_wind_spd_inv.png' ; break ;
    case 'windspeed': $met_icon = 'icon_wind_spd_inv.png' ; break ;
    case 'wind direction': $met_icon = 'icon_wind_dir_inv.png' ; break ;
    case 'winddirection': $met_icon = 'icon_wind_dir_inv.png' ; break ;
    
    default: $met_icon = 'icon_meteo.png' ; // Met data icon for default
  }
  
  return $met_icon ;
} // addMetDataIconToParameter

function buildMetDiv($equipment)
{
  
  // Connect to db
  $dbc_local = db_connect_local() ;
  
  $met_box = '' ;
  
  // Calculate the averages for last [updated, 1 hour, 8 hour, 24 hour] + AQI
  // Get the values of each sensors of that equipment
  $query = "SELECT lastvalue_equipment.id AS lastvalue_id, value_per_sensor
        FROM lastvalue_equipment
        WHERE equipment_id = " . $equipment ;
  $result = mysqli_query($dbc_local, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc_local));
  $row = mysqli_fetch_array($result, MYSQLI_NUM) ;
  $sensor_json = $row[1] ;
  // Beware of the special characters
  $sensor_json = utf8_encode($sensor_json);
  $sensor_json = preg_replace('/\s+/', ' ',$sensor_json);
  // Decode JSON
  
  $sensor_obj = json_decode($sensor_json) ;
  
  // Get the last updated value and construct the other values based on it (for now!)
  //foreach ($obj as $key => $value)
  foreach ($sensor_obj as $value)
  {
    //echo "Key:" . $key . ", Value:" . strtolower($value->name) . " <br />" ;
    //echo "Value:" . strtolower($value->name) . " <br />" ;
    // Avoid Met data or normal Gas data
    if (strtolower($value->unit) != 'ppm' && strtolower($value->unit) != 'ppb' && strtolower($value->unit) != 'ou'
      && strtolower($value->unit) != 'ug/m3' && strtolower($value->unit) != 'ou/m3')
    {
      // Start the Met data box
      if (strlen($met_box) == 0)
        $met_box .= '<div id="weather_box">' ;
      
      $met_box .= '  <div class="inner_box">
    <div class="line_image"><img class="image_trans" src="/images/' . addMetDataIconToParameter($value->name) . '"/></div>
    <div class="line_text">' . $value->name . '</div>
    <div class="line_text">' . $value->value . ' ' . $value->unit . '</div>
  </div>' ;
    }
  
  }
  // Finish the Met data Box
  if (strlen($met_box) > 0)
    $met_box .= '</div>' ;
  
  // Close db
  db_close($dbc_local) ;
  

  return $met_box ;
} // buildMetDiv

// Change the sensor names with respect to chemical 
function shortenSensorName($sensor_name, $empirical=false)
{ 
  // replace extra spaces around the '-'
  $sensor_name = str_replace(' - ', '-', $sensor_name) ;
  $sensor_name_orig = $sensor_name ;
  $sensor_name = strtolower($sensor_name) ;
  
  switch ($sensor_name)
  {
    case 'ammonia-lc': $shortened_name = 'NH3-LC' ; $formula = 'NH3' ; break ;
    case 'ammonia-a': $shortened_name = 'NH3-A' ; $formula = 'NH3' ; break ;
    case 'ammonia-b': $shortened_name = 'NH3-B' ; $formula = 'NH3' ; break ;
    case 'ammonia': $shortened_name = 'NH3' ; $formula = 'NH3' ; break ;
    case 'nitrogen oxide-lc': $shortened_name = 'NOx-LC' ; $formula = 'NO2' ; break ; // By default give Empirical formula NO2
    case 'nitrogen oxide': $shortened_name = 'NOx' ; $formula = 'NO2' ; break ; // By default give Empirical formula NO2
    case 'nitrogen dioxide-lc': $shortened_name = 'NO2-LC' ; $formula = 'NO2' ; break ;
    case 'nitrogendioxide-lc': $shortened_name = 'NO2-LC' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide': $shortened_name = 'NO2' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide': $shortened_name = 'NO2' ; $formula = 'NO2' ; break ;
    case 'nitrogendioxide': $shortened_name = 'NO2' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-lc-d': $shortened_name = 'NO2-LC-D' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-lc-d': $shortened_name = 'NO2-LC-D' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-lc-c': $shortened_name = 'NO2-LC-C' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-lc-c': $shortened_name = 'NO2-LC-C' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-lc-b': $shortened_name = 'NO2-LC-B' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-lc-b': $shortened_name = 'NO2-LC-B' ; $formula = 'NO2' ; break ;
    case 'nitrogendioxide-lc-b': $shortened_name = 'NO2-LC-B' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-lc-a': $shortened_name = 'NO2-LC-A' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-lc-a': $shortened_name = 'NO2-LC-A' ; $formula = 'NO2' ; break ;
    case 'nitrogendioxide-lc-a': $shortened_name = 'NO2-LC-A' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-hc': $shortened_name = 'NO2-HC' ; $formula = 'NO2' ; break ;
    case 'nitrogendioxide-hc': $shortened_name = 'NO2-HC' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-hc': $shortened_name = 'NO2-HC' ; $formula = 'NO2' ; break ;
    case 'nitrogen dioxide-hc-a': $shortened_name = 'NO2-HC-A' ; $formula = 'NO2' ; break ;
    case 'nitrogen-dioxide-hc-a': $shortened_name = 'NO2-HC-A' ; $formula = 'NO2' ; break ;
    case 'nitric oxide': $shortened_name = 'NO' ; $formula = 'NO' ; break ;
    case 'nitricoxide': $shortened_name = 'NO' ; $formula = 'NO' ; break ;
    case 'nitric oxide-lc': $shortened_name = 'NO-LC' ; $formula = 'NO' ; break ;
    case 'nitricoxide-lc': $shortened_name = 'NO-LC' ; $formula = 'NO' ; break ;
    
    case 'hydrogen cyanide-lc-d': $shortened_name = 'HCN-LC-D' ; $formula = 'HCN' ; break ;
    case 'hydrogen-cyanide-lc-d': $shortened_name = 'HCN-LC-D' ; $formula = 'HCN' ; break ;
    case 'hydrogen cyanide-lc-c': $shortened_name = 'HCN-LC-C' ; $formula = 'HCN' ; break ;
    case 'hydrogen-cyanide-lc-c': $shortened_name = 'HCN-LC-C' ; $formula = 'HCN' ; break ;
    case 'hydrogen cyanide-lc-b': $shortened_name = 'HCN-LC-B' ; $formula = 'HCN' ; break ;
    case 'hydrogen-cyanide-lc-b': $shortened_name = 'HCN-LC-B' ; $formula = 'HCN' ; break ;
    case 'hydrogen cyanide-lc-a': $shortened_name = 'HCN-LC-A' ; $formula = 'HCN' ; break ;
    case 'hydrogen-cyanide-lc-a': $shortened_name = 'HCN-LC-A' ; $formula = 'HCN' ; break ;
    
    case 'hydrogen sulfide-hc': $shortened_name = 'H2S-HC' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-hc': $shortened_name = 'H2S-HC' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-hc': $shortened_name = 'H2S-HC' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-hc-a': $shortened_name = 'H2S-HC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-hc-a': $shortened_name = 'H2S-HC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-hc-a': $shortened_name = 'H2S-HC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-hc-b': $shortened_name = 'H2S-HC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-hc-b': $shortened_name = 'H2S-HC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-hc-b': $shortened_name = 'H2S-HC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc': $shortened_name = 'H2S-LC' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-lc': $shortened_name = 'H2S-LC' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-lc': $shortened_name = 'H2S-LC'  ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-mc': $shortened_name = 'H2S-MC' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-mc': $shortened_name = 'H2S-MC' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc-a': $shortened_name = 'H2S-LC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc -a': $shortened_name = 'H2S-LC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-lc-a': $shortened_name = 'H2S-LC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-lc-a': $shortened_name = 'H2S-LC-A' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc-b': $shortened_name = 'H2S-LC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc -b': $shortened_name = 'H2S-LC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-lc-b': $shortened_name = 'H2S-LC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide-lc-b': $shortened_name = 'H2S-LC-B' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc-d': $shortened_name = 'H2S-LC-D' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-lc-d': $shortened_name = 'H2S-LC-D' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide-lc-c': $shortened_name = 'H2S-LC-C' ; $formula = 'H2S' ; break ;
    case 'hydrogen-sulfide-lc-c': $shortened_name = 'H2S-LC-C' ; $formula = 'H2S' ; break ;
    case 'hydrogen sulfide': $shortened_name = 'H2S' ; $formula = 'H2S' ; break ;
    case 'hydrogensulfide': $shortened_name = 'H2S' ; $formula = 'H2S' ; break ;
    case 'hyrdrogen sulfide-b': $shortened_name = 'H2S-B' ; $formula = 'H2S' ; break ; // Typo
    case 'hyrdrogen sulfide-a': $shortened_name = 'H2S-A' ; $formula = 'H2S' ; break ; // Typo
    case 'hyrdrogensulfide-b': $shortened_name = 'H2S-B' ; $formula = 'H2S' ; break ; // Typo
    case 'hyrdrogensulfide-a': $shortened_name = 'H2S-A' ; $formula = 'H2S' ; break ; // Typo
    
    case 'sulfur dioxide': $shortened_name = 'SO2' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide': $shortened_name = 'SO2' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide': $shortened_name = 'SO2' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-lc': $shortened_name = 'SO2-LC' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide-lc': $shortened_name = 'SO2-LC' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-mc': $shortened_name = 'SO2-MC' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide-mc': $shortened_name = 'SO2-MC' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-hc': $shortened_name = 'SO2-HC' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide-hc': $shortened_name = 'SO2-HC' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide-hc': $shortened_name = 'SO2-HC' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-hc-a': $shortened_name = 'SO2-HC-A' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide-hc-a': $shortened_name = 'SO2-HC-A' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-lc-d': $shortened_name = 'SO2-LC-D' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide-lc-a': $shortened_name = 'SO2-LC-A' ; $formula = 'SO2' ; break ;
    case 'sulfur dioxide-lc-a': $shortened_name = 'SO2-LC-A' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide-lc-a': $shortened_name = 'SO2-LC-A' ; $formula = 'SO2' ; break ;
    case 'suflurdioxide-lc-a': $shortened_name = 'SO2-LC-A' ; $formula = 'SO2' ; break ; // Typo
    case 'suflurdioxide-lc-b': $shortened_name = 'SO2-LC-B' ; $formula = 'SO2' ; break ; // Typo
    case 'sulfur-dioxide-lc-b': $shortened_name = 'SO2-LC-B' ; $formula = 'SO2' ; break ;
    case 'sulfurdioxide-lc-b': $shortened_name = 'SO2-LC-B' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide-lc-c': $shortened_name = 'SO2-LC-C' ; $formula = 'SO2' ; break ;
    case 'sulfur-dioxide-lc-d': $shortened_name = 'SO2-LC-D' ; $formula = 'SO2' ; break ;
    
    case 'carbon monoxide': $shortened_name = 'CO' ; $formula = 'CO' ; break ;
    case 'carbon-monoxide': $shortened_name = 'CO' ; $formula = 'CO' ; break ;
    case 'carbonmonoxide': $shortened_name = 'CO' ; $formula = 'CO' ; break ;
    case 'carbon monoxide-lc': $shortened_name = 'CO-LC' ; $formula = 'CO' ; break ;
    case 'carbonmonoxide-lc': $shortened_name = 'CO-LC' ; $formula = 'CO' ; break ;
    case 'carbon monoxide-lc-ppm': $shortened_name = 'CO-LC' ; $formula = 'CO' ; break ;
    case 'carbonmonoxide-lc-ppm': $shortened_name = 'CO-LC' ; $formula = 'CO' ; break ;
    case 'carbon monoxide-lc-a': $shortened_name = 'CO-LC-A' ; $formula = 'CO' ; break ;
    case 'carbonmonoxide-lc-a': $shortened_name = 'CO-LC-A' ; $formula = 'CO' ; break ;
    case 'carbon monoxide-lc-b': $shortened_name = 'CO-LC-B' ; $formula = 'CO' ; break ;
    case 'carbonmonoxide-lc-b': $shortened_name = 'CO-LC-B' ; $formula = 'CO' ; break ;
    case 'carbon dioxide-lc': $shortened_name = 'CO2-LC' ; $formula = 'CO2' ; break ;
    case 'carbondioxide-lc': $shortened_name = 'CO2-LC' ; $formula = 'CO2' ; break ;
    case 'carbon dioxide': $shortened_name = 'CO2'  ; $formula = 'CO2' ; break ;
    case 'carbondioxide': $shortened_name = 'CO2' ; $formula = 'CO2' ; break ;
    case 'carbon dioxide-lc-ppm': $shortened_name = 'CO2-LC' ; $formula = 'CO2' ; break ;
    case 'carbondioxide-lc-ppm': $shortened_name = 'CO2-LC' ; $formula = 'CO2' ; break ;
    
    case 'ethyl-sh': $shortened_name = 'Et-SH' ; $formula = 'C2H5-SH' ; break ;
    case 'methane-lc': $shortened_name = 'Me-LC' ; $formula = 'CH4' ; break ;
    case 'methane': $shortened_name = 'Me' ; $formula = 'CH4' ; break ;
    case 'methyl-sh': $shortened_name = 'Me-SH' ; $formula = 'CH3-SH' ; break ;
    case 'trs-methylmercaptan': $shortened_name = 'TRS-Me-SH' ; $formula = 'TRS-CH3-SH' ; break ;
    case 'trs-methyl mercaptan': $shortened_name = 'TRS-Me-SH' ; $formula = 'TRS-CH3-SH' ; break ;
    case 'iso-propyl-sh': $shortened_name = 'iPr-SH' ; $formula = 'C3H7-SH' ; break ;
    case 'n-propyl-sh': $shortened_name = 'nPr-SH' ; $formula = 'C3H7-SH' ; break ;
    case '2-butyl-sh': $shortened_name = '2Bu-SH' ; $formula = 'C4H9-SH' ; break ;
    case 'n-butyl-sh': $shortened_name = 'nBu-SH' ; $formula = 'C4H9-SH' ; break ;
    case 'methane, butane, lpg': $shortened_name = 'Me-Bu-LPG' ; $formula = 'CH4-C4H10-LPG' ; break ;
    case 'methane,butane,lpg': $shortened_name = 'Me-Bu-LPG' ; $formula = 'CH4-C4H10-LPG' ; break ;
    case 'alcohol, ethanol, smoke': $shortened_name = 'Alc-Et-SMK' ; $formula = '' ; break ;
    case 'alcohol,ethanol,smoke': $shortened_name = 'Alc-Et-SMK' ; $formula = '' ; break ;
    case 'benzene, alcohol, smoke': $shortened_name = 'Bz-Alc-SMK' ; $formula = '' ; break ;
    case 'benzene,alcohol,smoke': $shortened_name = 'Bz-Alc-SMK' ; $formula = '' ; break ;
    
    case 'odour unit': $shortened_name = 'OU' ; $formula = '' ; break ;
    case 'odourunit': $shortened_name = 'OU' ; $formula = '' ; break ;
    case 'odour': $shortened_name = 'OU' ; $formula = '' ; break ;
    case 'odour-a': $shortened_name = 'OU-A' ; $formula = '' ; break ;
    case 'odour-b': $shortened_name = 'OU-B' ; $formula = '' ; break ;
    
    case 'hydrogen': $shortened_name = 'H2' ; $formula = 'H2' ; break ;
    case 'hydrogen chloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'hydrogen-chloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'hydrogenchloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'hydrogen chloride-lc-d': $shortened_name = 'HCl-LC-D' ; $formula = 'HCl' ; break ;
    case 'hydrogen-chloride-lc-d': $shortened_name = 'HCl-LC-D' ; $formula = 'HCl' ; break ;
    case 'hydrogen chloride-lc-c': $shortened_name = 'HCl-LC-C' ; $formula = 'HCl' ; break ;
    case 'hydrogen-chloride-lc-c': $shortened_name = 'HCl-LC-C' ; $formula = 'HCl' ; break ;
    case 'hydrogen chloride-lc-b': $shortened_name = 'HCl-LC-B' ; $formula = 'HCl' ; break ;
    case 'hydrogen-chloride-lc-b': $shortened_name = 'HCl-LC-B' ; $formula = 'HCl' ; break ;
    case 'hydrogen chloride-lc-a': $shortened_name = 'HCl-LC-A' ; $formula = 'HCl' ; break ;
    case 'hydrogen-chloride-lc-a': $shortened_name = 'HCl-LC-A' ; $formula = 'HCl' ; break ;
    case 'hydrogen cloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'hydrogen-cloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'hydrogencloride': $shortened_name = 'HCl' ; $formula = 'HCl' ; break ;
    case 'chlorine': $shortened_name = 'Cl2' ; $formula = 'Cl2' ; break ;
    case 'chlorine-lc-d': $shortened_name = 'Cl2-LC-D' ; $formula = 'Cl2' ; break ;
    case 'chlorine-lc-c': $shortened_name = 'Cl2-LC-C' ; $formula = 'Cl2' ; break ;
    case 'chlorine-lc-b': $shortened_name = 'Cl2-LC-B' ; $formula = 'Cl2' ; break ;
    case 'chlorine-lc-a': $shortened_name = 'Cl2-LC-A' ; $formula = 'Cl2' ; break ;
    
    case 'ozone & nitrogen dioxide': $shortened_name = 'O3-NO2' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide': $shortened_name = 'O3-NO2' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogendioxide': $shortened_name = 'O3-NO2' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide-lc-c': $shortened_name = 'O3-NO2-LC-C' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogendioxide-lc-c': $shortened_name = 'O3-NO2-LC-C' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide-lc-d': $shortened_name = 'O3-NO2-LC-D' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogendioxide-lc-d': $shortened_name = 'O3-NO2-LC-D' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide-lc-a': $shortened_name = 'O3-NO2-LC-A' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogendioxide-lc-a': $shortened_name = 'O3-NO2-LC-A' ; $formula = 'O3-NO2' ; break ;
    case 'ozone & nitrogen dioxide-lc-a': $shortened_name = 'O3-NO2-LC-A' ; $formula = 'O3-NO2' ; break ;
    case 'ozone & nitrogen dioxide-lc-b': $shortened_name = 'O3-NO2-LC-B' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide-lc-b': $shortened_name = 'O3-NO2-LC-B' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogendioxide-lc-b': $shortened_name = 'O3-NO2-LC-B' ; $formula = 'O3-NO2' ; break ;
    case 'ozone&nitrogen dioxide-lc-b': $shortened_name = 'O3-NO2-LC-B' ; $formula = 'O3-NO2' ; break ;
    case 'ozone + nitrogen dioxide': $shortened_name = 'O3-NO2' ; $formula = 'O3-NO2' ; break ;
    case 'ozone+nitrogendioxide': $shortened_name = 'O3-NO2' ; $formula = 'O3-NO2' ; break ;
    case 'nitrogen dioxide + ozone': $shortened_name = 'NO2-O3' ; $formula = 'NO2-O3' ; break ;
    case 'nitrogendioxide+ozone': $shortened_name = 'NO2-O3' ; $formula = 'NO2-O3' ; break ;
    case 'nitrogen dioxide + ozone-lc': $shortened_name = 'NO2-O3-LC' ; $formula = 'NO2-O3' ; break ;
    case 'nitrogendioxide+ozone-lc': $shortened_name = 'NO2-O3-LC' ; $formula = 'NO2-O3' ; break ;
    case 'ozone + nitrogen dioxide-lc': $shortened_name = 'O3-NO2-LC' ; $formula = 'O3-NO2' ; break ;
    case 'ozone+nitrogendioxide-lc': $shortened_name = 'O3-NO2-LC' ; $formula = 'O3-NO2' ; break ;
    case 'ozone-lc': $shortened_name = 'O3-LC' ; $formula = 'O3' ; break ;
    case 'ozone': $shortened_name = 'O3' ; $formula = 'O3' ; break ;
    
    case 'air contaminants (ammonia, ethanol, toulene)': $shortened_name = 'Air Cont.' ; $formula = '' ; break ;
    case 'aircontaminants(ammonia,ethanol,toulene)': $shortened_name = 'Air Cont.' ; $formula = '' ; break ;
    
    case 'trs & amines': $shortened_name = 'TRS+A' ; $formula = '' ; break ;
    case 'trs&amines': $shortened_name = 'TRS+A' ; $formula = '' ; break ;
    case 'trs and amines': $shortened_name = 'TRS+A' ; $formula = '' ; break ;
    case 'trsandamines': $shortened_name = 'TRS+A' ; $formula = '' ; break ;
    case 'trs+amines': $shortened_name = 'TRS+A' ; $formula = '' ; break ;
    case 'trs + amines': $shortened_name = 'TRS+A' ; $formula = '' ; break ;
    case 'trs+amines-a': $shortened_name = 'TRS+A-A' ; $formula = '' ; break ;
    case 'trs+amines-b': $shortened_name = 'TRS+A-B' ; $formula = '' ; break ;
    case 'trs & amines-lc-a': $shortened_name = 'TRS+A-LC-A' ; $formula = '' ; break ;
    case 'trs&amines-lc-a': $shortened_name = 'TRS+A-LC-A' ; $formula = '' ; break ;
    case 'trs & amines-lc-b': $shortened_name = 'TRS+A-LC-B' ; $formula = '' ; break ;
    case 'trs&amines-lc-b': $shortened_name = 'TRS+A-LC-B' ; $formula = '' ; break ;
    
    case 'formaldehyde': $shortened_name = 'CH2O' ; $formula = 'CH2O' ; break ;
    case 'formaldehyde-lc-d': $shortened_name = 'CH2O-LC-D' ; $formula = 'CH2O' ; break ;
    case 'formaldehyde-lc-c': $shortened_name = 'CH2O-LC-C' ; $formula = 'CH2O' ; break ;
    case 'formaldehyde-lc-b': $shortened_name = 'CH2O-LC-B' ; $formula = 'CH2O' ; break ;
    case 'formaldehyde-lc-a': $shortened_name = 'CH2O-LC-A' ; $formula = 'CH2O' ; break ;
    case 'formaldahyde-lc-b': $shortened_name = 'CH2O-LC-B' ; $formula = 'CH2O' ; break ; // Typo
    case 'formaldahyde-lc-a': $shortened_name = 'CH2O-LC-A' ; $formula = 'CH2O' ; break ; // Typo
    
    case 'pid sensor': $shortened_name = 'PID' ; $formula = '' ; break ;
    case 'pidsensor': $shortened_name = 'PID' ; $formula = '' ; break ;
    default: $shortened_name = '' ;
  }
  
  if (strlen($shortened_name) == 0)
  {
    $shortened_name = $sensor_name_orig ;
    //$shortened_name = $sensor_name ;
  }
  
  if ($empirical)
    return $formula ;
  else
    return $shortened_name ;
} // shortenSensorName

?>