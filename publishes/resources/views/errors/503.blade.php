<!DOCTYPE html>
<html>
    <head>
        <title>{{__('Be right back.')}}</title>

        <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Muli';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                {!!config('site.maintenance_notice')!!}
            </div>
        </div>
    </body>
</html>
