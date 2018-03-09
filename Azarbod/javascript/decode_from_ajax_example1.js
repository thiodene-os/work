// Finish the JSON String and run the PHP function
if (info.length > 0)
{
  info = '{' + info + "\n" + '}' ;
  info = xmlbEncodeForAJAX(info) ;

  prog =  '$do_record = new doRecord("ITINERARY") ;'
  + "\n" + '$new_rec = array() ;'
  + "\n" + '$new_rec[\'LNK_EVENT\'] = ' + %event_id% + ' ;'
  + "\n" + '$new_rec[\'ITIN_VALUES_JSON\'] = xmlbDecodeFromAJAX("' + info + '") ;'
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


  runBackEndProg(prog) ; 

}
