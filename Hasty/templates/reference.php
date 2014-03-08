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
            <h1>Reference: <?= $route ?> : <?= $class ?></h1>
        </div>
        <div id="content">
            <table border="1" cellspacing="0" cellpadding="4">
                <thead>
                    <tr>
                        <th>Path</th>
                        <th>API Method</th>
                        <th>Description</th>
                        <th>Info</th>
                    </tr>
                </thead>
                <?php
                if (isset($doc) && count($doc) > 0) {
                    foreach ($doc as $function) {
                        if ($function['function'] != "__construct" && $function['type'] == "PUBLIC") {
                            ?>
                            <tr>
                                <td><?= DS . $this->request->version . DS . $class . DS . implode("_", array_slice(explode("_", $function['function']), 0, count(explode("_", $function['function'])) - 1)) ?></td>
                                <td><?= explode("_", $function['function'])[count(explode("_", $function['function'])) - 1] ?></td>
                                <td><?= @nl2br(htmlentities($function['comment']['description'])) ?></td>
                                <td><?php
                                    if (is_array($function['comment'])) {
                                        ?> <table border="1" cellspacing="0" cellpadding="4"><?php
                                            foreach ($function['comment'] as $name => $value) {
                                                if ($name != "description") {
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlentities($name) ?></td>
                                                        <td><?= htmlentities($value) ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?></table><?php
                                    }
                                    ?></td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
            </table>
        </div>
    </body>
</html>