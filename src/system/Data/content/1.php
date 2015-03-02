<?php

namespace Szandor\ConMan;

$contents['page_id'] = 1;
$contents['date_created'] = '2014-11-15 20:53:18';
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = '1';
$contents['name'] = 'Framsida';
$contents['title'] = 'Välkommen till systemet!';
$contents['head_local'] = '';

$contents['content_top'] = '<div class="jumbotron">
    <div class="container">
        <div class="col-md-2 col-sm-0">
        </div>
        <div class="col-md-8 col-sm-12">
            <h1 class="text-center">Välkommen till anmälnings&shy;systemet för WSK2015</h1>
            <hr />
            <p class="text-center">För att anmäla dig eller ett arr&shy;ange&shy;mang till WSK2015 måste du logga in i systemet. För att kunna göra det måste du först</p>
            <p class="text-center"><a href="' . Data\Settings::main('base_url') . 'index.php?page=createnewuser" class="btn btn-primary btn-lg">registrera en ny användare  <i class="fa fa-user"></i></a></p>
        </div>
        <div class="col-md-2 col-sm-0">
        </div>
    </div>
</div>';

$contents['content_main'] = '<div class="col-xs-12">
    <p class="text-justify lead">Du har kommit till det - baserat på tidigare mätningar av liknande system till snarlika konvent - mer stabila, funktio&shy;nella och opti&shy;merade anmälnings&shy;systemet för WSK2015. Här kan du enkelt anmäla dig till konventet och all dess prakt. Med vilket menas de arrangemang som hålls på konventet.</p>
</div>
<div class="col-xs-12 col-sm-4">
    <p class="text-justify">För att vara med på konventet måste du anmäla dig. Det kan du antingen göra direkt på den här sidan eller i dörren när du kommer. Den stora skillnaden är att det är billigare att föranmäla sig. Exakta priser dyker upp så fort vi har satt budgeten.</p>
</div>
<div class="col-xs-12 col-sm-4">
    <p class="text-justify">Dina person&shy;uppgifter hanteras varsamt och vi krypterar lösenorden så de inte går att läsa. Vi skickar heller inte iväg person&shy;uppgifter till tredje part utan att du gått med på det.</p>
</div>
<div class="col-xs-12 col-sm-4">
    <p class="text-justify">Du kan skapa ett nytt konto direkt här, men ett enklare alternativ är att logga in med ett konto du redan har. Då slipper vi spara känsliga lösenord och du slipper ha extra konton att hantera.</p>
</div>
';

$contents['content_bottom'] = '';
