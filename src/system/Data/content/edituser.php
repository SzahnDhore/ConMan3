<?php

namespace Szandor\ConMan;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * We can do stuff here.
 */

/**
 * The following is simple contents.
 */
$contents['page_id'] = 'edituser';
$contents['date_created'] = '2014-12-28 22:09:18';
$contents['date_changed'] = gmdate("Y-m-d H:i:s", filemtime(__FILE__));
$contents['required_clearance'] = '2';
$contents['name'] = '';
$contents['title'] = 'Ändra användare';
$contents['head_local'] = '';

$contents['content_top'] = '';

$contents['content_main'] = '
<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-12">
            <h1>Ändra användarinfo</h1>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9">

            <form role="form" id="new_user" name="new_user" action="dostuff.php" method="post">

                <div class="well bs-component">
                    <fieldset>

                        <div class="row">
                            <div class="col-xs-12 col-sm-5 col-lg-3">
                                <div class="form-group">
                                    <label for="new_user_given_name">Förnamn</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Ditt förnamn"></span>
                                    <input type="text" class="form-control" id="new_user_given_name" name="new_user_given_name" placeholder="Förnamn" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-7 col-lg-4">
                                <div class="form-group">
                                    <label for="new_user_family_name">Efternamn</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Ditt efternamn"></span>
                                    <input type="text" class="form-control" id="new_user_family_name" name="new_user_family_name" placeholder="Efternamn" />
                                </div>
                            </div>
                            <div class="col-xs-8 col-lg-3">
                                <div class="form-group">
                                    <label for="new_user_national_id_number">Personnummer</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Ditt fullständiga personnummer, utan streck eller mellanslag"></span>
                                    <input type="text" class="form-control" id="new_user_national_id_number" name="new_user_national_id_number" placeholder="Personnummer" />
                                </div>
                            </div>
                            <div class="col-xs-4 col-lg-2">
                                <div class="form-group">
                                    <label for="new_user_male">Kön</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Ditt binära kön. Eftersom vi rapporterar till system som inte kan hantera fler kön måste vi tyvärr begränsa oss till dessa två."></span>
                                    <select class="form-control" id="new_user_male" name="new_user_male">
                                        <option value="X" selected="selected" disabled="disabled">Välj kön</option>
                                        <option value="0">Tjej</option>
                                        <option value="1">Kille</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-lg-6">
                                <div class="form-group">
                                    <label for="new_user_address">Adress</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Din adress"></span>
                                    <input type="text" class="form-control" id="new_user_address" name="new_user_address" placeholder="Adress" />
                                </div>
                            </div>
                            <div class="col-xs-4 col-lg-2">
                                <div class="form-group">
                                    <label for="new_user_postal_code">Postnr.</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Postnummer"></span>
                                    <input type="text" class="form-control" id="new_user_postal_code" name="new_user_postal_code" placeholder="Postnr." />
                                </div>
                            </div>
                            <div class="col-xs-8 col-lg-4">
                                <div class="form-group">
                                    <label for="new_user_city">Stad</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Stad"></span>
                                    <input type="text" class="form-control" id="new_user_city" name="new_user_city" placeholder="Stad" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-5 col-md-3">
                                <div class="form-group">
                                    <label for="new_user_phone_number">Telenr.</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Ditt telefonnummer"></span>
                                    <input type="text" class="form-control" id="new_user_phone_number" name="new_user_phone_number" placeholder="Telenr." />
                                </div>
                            </div>
                            <div class="col-xs-7 col-md-5">
                                <div class="form-group">
                                    <label for="new_user_email">Epost</label> <span class="fa fa-question-circle fa-fw has-tooltip" data-placement="top" title="Epostadress"></span>
                                    <input type="text" class="form-control" id="new_user_email" name="new_user_email" placeholder="Epost" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <label for="new_user_submit">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block" id="new_user_submit" name="submit_dostuff" value="new_user_submit">Skapa ny användare</button>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>

            </form>

        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <h2><small>Kort om säkerhet:</small></h2>
            <p>Om du loggar in via ett socialt media så sker all lösen&shy;ords&shy;hantering på mediets egna sida. Det betyder att ditt lösen&shy;ord aldrig sparas hos oss vilket bidrar till ökad säkerhet.</p>
            <p>Om du istäl&shy;let skapar en helt ny använ&shy;dare så krypt&shy;erar vi ditt lösen&shy;ord på ett sätt som gör det prakt&shy;iskt sett omöjligt att de&shy;krypt&shy;era. Det betyder att om du glömmer bort ditt lösen&shy;ord så kan vi inte skicka över det till dig igen, men också att ditt lösen&shy;ord är relativt säkert även om data&shy;basen blir hackad.</p>
        </div>
    </div>';

$contents['content_bottom'] = '';
