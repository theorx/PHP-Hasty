<html>
    <head>
        <title>Hasty</title>
    </head>
    <style>
        head, body{
            font-size:12px;
            margin:0px;
            padding:0px;
            background-color:#f5f5f5;
            color:#000000;
            font-family:fantasy;
        }
        #top{
            background-color:#66ccff;
            border-bottom:1px solid black;
        }
        #content {
            margin:10px;
        }
        #top h1{
            margin-top:0px;
            padding-top:10px;
            padding-left:10px;

        }
    </style>
    <body>

        <div id="top">
            <h1>Html: <?= $route ?> : <?= gettype($input) ?></h1>
        </div>
        <div id="content">
            <pre><br />
                <?php
                print_r($input);
                ?>
            </pre>
        </div>
    </body>
</html>