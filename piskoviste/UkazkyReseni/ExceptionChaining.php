<?php
function theDatabaseObj(){
     if( database_object ){
         return database_object;
     }
     else{
         throw new DatabaseException("Could not connect to the database");
     }
}

function updateProfile( $userInfo ){
     try{
         $db = theDatabaseObj();
         $db->updateProfile();
     }
     catch( DatabaseException $e ){
         $message = "The user :" . $userInfo->username . " could not update his profile information";
         /* notice the '$e'. I'm adding the previous exception  to this exception. I can later get a detailed view of
          where the problem began. Lastly, the number '12' is  an exception code. I can use this for categorizing my
         exceptions or don't use it at all. */
         throw new MemberSettingsException($message,12,$e);
     }
}

try{
     updateProfile( $userInfo );
}
catch( MemberSettingsException $e ){
     // this will give all information we have collected above.
     echo $e->getTraceAsString();
}
?>
