# Javascript

// Finish the JSON String and run the PHP function
if (info.length > 0)
{
  info = '{' + info + "\n" + '}' ;
  info = xmlbEncodeForAJAX(info) ;

  prog =  '$do_record = new doRecord("ITINERARY") ;'
  + "\n" + '$new_rec = array() ;'
  + "\n" + '$new_rec[\'LNK_EVENT\'] = ' + %event_id% + ' ;'
  + "\n" + '$new_rec[\'ITIN_VALUES_JSON\'] = xmlbDecodeFromAJAX(' + info + ') ;'
  + "\n" + '$do_record -> new_record = $new_rec ;'
  + "\n" + '$do_record -> id_column_val = ' + %itin_id% + ' ;'
  + "\n" + 'if (! $do_record -> update()) '
  + "\n" + '{ '
  + "\n" + '  debug(getGlobalMsg(),"getGlobalMsg","File: " . __FILE__ . " Line: " . __LINE__) ;'
  + "\n" + '  return Null ; '
  + "\n" + '} '
  + "\n" + 'unset($new_rec) ;'
  + "\n" + 'unset($do_record) ;'
  + "\n" + '$prog_result = "doAfterItineraryOptionsSaved(\" . %event_id% . \")" ; ' ;


  //runBackEndProg(prog) ; 

}

alert(prog) ;

#----------------------------------------------------------------------------------------------------------

# String Result:

$do_record = new doRecord("ITINERARY") ;
$new_rec = array() ;
$new_rec['LNK_EVENT'] = 11764 ;
$new_rec['ITIN_VALUES_JSON'] = xmlbDecodeFromAJAX({--br----sp----sp----dq--items_leave_bombo--dq--:--sp----dq--Yes--dq----cm----br----sp----sp----dq--items_leave_bombo_how_many--dq--:--sp----dq--1--dq----cm----br----sp----sp----dq--items_leave_other--dq--:--sp----bo----dq--serge--dq----bc----br--}) ;
$do_record -> new_record = $new_rec ;
$do_record -> id_column_val = 3 ;
if (! $do_record -> update()) 
{ 
  debug(getGlobalMsg(),"getGlobalMsg","File: " . __FILE__ . " Line: " . __LINE__) ;
  return Null ; 
} 
unset($new_rec) ;
unset($do_record) ;
$prog_result = "doAfterCheckoutSessionSaved(" . 11764 . ")" ; 
