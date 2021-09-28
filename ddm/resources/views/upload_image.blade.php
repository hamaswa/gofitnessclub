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
                    <form method="post" action="/api/dailydite/upload_image"  enctype="multipart/form-data">
                        <input type="file" name="image">
                        <input type="number" name="id">
                        <input type="submit" name="submit" value="upload">
                    </form>
                </div>               
            </div>
        </div>
    </body>
</html>
