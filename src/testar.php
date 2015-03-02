<?php

$logfile['character'] = 'Jena';
$logfile['character_type'] = 'pc';
$logfile['timestamp'] = '2014-12-01 18:24:32';
$logfile['text'] = '----| 2014-12-01 18:24:32 | pc:Lenore |----

** As /mychar carefully makes her way closer to the guards she tweets like a bird to let the others know that she\'s on her way.

ooc: Ok, I hope I don\'t need to point it out, but I don\'t make any strange bird noises too close to the guards.

----| 2014-12-01 18:24:32 | gm |----

@Lenore: You make it over without anyone noticing you, but on your way there you find a small silver key on the ground. It could have been thrown out one of the windows above.

** [pc:Lenore] makes it over to the guards without incidence and [pc:Thomas] has readied his bow, but neither [pc:Jena] nor [npc:Lars] are anywhere to be seen.

@Jena: You\'ve just finished tying [npc:Lars] up and, if you like to, looted him bare. You can enter the scene whenever.

----| 2014-12-01 18:24:32 | pc:Jena |----

@gm: I make double sure the knots hold and that the rope is tight. I don\'t want to loose any Karma points over killing him, but I don\'t want him to get free before we\'re well clear of the city either.

** /mychar pops up beside [pc:Thomas].

ooc: A wild [pc:Jena] appears!

"I\'m sorry I\'m late. There were... Complications."

** Suddendly, /mychar looks strangely sad.

"Lars... Lars didn\'t make it. The guards overwhelmed us. He made sure I got away clean, but..."

** /mychar goes quiet and it looks like she\'s about to cry. Before she does, /mychar wipes her face and smiles.

"So, where\'s [pc:Lenore]?"

----| 2014-12-01 18:24:32 | pc:Thomas |----

ooc: What? Lars died? 4 realz? But didn\'t we need that guy for his genetic imprint or something?

"What... You... We\'ll talk about [npc:Lars] later. [pc:Lenore] is waiting in the shrubbery over there. We\'ll distract the guards and she\'ll sneak in."

@Jena: You better not have done anything to [npc:Lars]...';

// print_r($logfile);

function parse_tags_mychar($matches)
{
    $out = '[pc:Jena]';
    return $out;
}

function parse_tags_timestamps($matches)
{
    return '<h4 class="timestamp">' . $matches[1] . '</h4>';
}

function parse_tags_private($matches)
{
    $text = preg_replace('/@([^:]+):\s*/', '', $matches[0]);
    preg_match('/@([^:]+):\s*/', $matches[0], $character);
    return '<p class="private" style="color:#aa3;"><em><strong>To ' . $character[1] . ':</strong> ' . $text . '</em></p>';
}

function parse_tags_ooc($matches)
{
    return '<p class="ooc" style="color:#6b6;"><small><strong>OOC: </strong>' . $matches[1] . '</small></p>';
}

function parse_tags_description($matches)
{
    return '<p class="description"><em>' . $matches[1] . '</em></p>';
}

function parse_tags_character($matches)
{
    $string = explode(':', $matches[1]);
    $out = '<a href="characters.html?type=' . $string[0] . '&name=' . $string[1] . '" title="">' . $string[1] . '</a>';
    return $out;
}

function parse_tags_spoken($matches)
{
    return '<p class="spoken"><q>' . $matches[1] . '</q></p>';
}

$logfile['text'] = trim(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $logfile['text']));

$logfile['text'] = preg_replace_callback('/\/([^\s]+)/m', 'parse_tags_mychar', $logfile['text']);
$logfile['text'] = preg_replace_callback('/^----\|\s*(.+)\|----/m', 'parse_tags_timestamps', $logfile['text']);
$logfile['text'] = preg_replace_callback('/^@(.+).*/m', 'parse_tags_private', $logfile['text']);
$logfile['text'] = preg_replace_callback('/^ooc:\s*(.*).*/m', 'parse_tags_ooc', $logfile['text']);
$logfile['text'] = preg_replace_callback('/^\*\*\s*(.*).*/m', 'parse_tags_description', $logfile['text']);
$logfile['text'] = preg_replace_callback('/^"(.+)"/m', 'parse_tags_spoken', $logfile['text']);
$logfile['text'] = preg_replace_callback('/\[([^\]]+)\]/m', 'parse_tags_character', $logfile['text']);

$html = '<html>
    <head>
    </head>
    <body>

' . $logfile['text'] . '

    </body>
</html>';

echo $html;
