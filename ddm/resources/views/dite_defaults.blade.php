<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

       
    </head>
    <body>
        <div class="flex-center position-ref full-height">
          
            <div class="content">
                <div class="title m-b-md">
                    <form method="post" action="/api/dailydite/dite_defaults"  enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{$dite->id}}" >
                        <input type="text" name="defaults" 
                            value="<?php echo $dite->name . "=" ;
                            echo isset($dite->weight) and $dite->weight!=""? $dite->weight :"" ;
                            echo isset($dite->weight) and $dite->weight!="" and isset($dite->cal) and $dite->cal!=""?",":"";
                            echo isset($dite->cal) and $dite->cal!=""? $dite->cal :"";
                            ?>"
                        >
                        <input type="submit" name="submit" value="upload">
                    </form>
                </div>               
            </div>
        </div>
    </body>
</html>
