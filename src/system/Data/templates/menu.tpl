        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="fa fa-navicon fa-lg fa-light"></span>
                        <span class="sr-only">Visa/göm menyn</span>
                    </button>
                    <h1><a class="navbar-brand" href="index.php">Anmälningssystemet</a>{{ username ? '<small class="navbar-brand"><span class="sr-only"> - </span>Inloggad som ' ~ username ~ "</small>" }}</h1>
                </div>
                <div class="collapse navbar-collapse">
{% if user_logged_in %}
                    <ul class="nav navbar-nav navbar-right">

{% if show_adminpage_confirm_payments or
      show_adminpage_confirm_updated_user_information or
      show_adminpage_view_statistics or
      show_adminpage_view_users_and_groups %}
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-tasks fa-stack-1x fa-dark"></i>
                                </span>
                                <span class="hidden-sm"> Admin </span>
                                {% if show_adminpage_nbr_of_tasks > 0 %}<span class="badge">{{ show_adminpage_nbr_of_tasks }}</span>{% endif %}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                            {% if show_adminpage_confirm_updated_user_information %}
                                <li><a href="{{ base_url }}index.php?page=confirmstageduserdetails">Godkänn medlemsuppgifter {% if show_adminpage_nbr_of_unconfirmed_user_details > 0 %}<span class="badge">{{ show_adminpage_nbr_of_unconfirmed_user_details }}</span>{% endif %}</a></li>
                            {% endif %}
                            {% if show_adminpage_confirm_payments %}
                                <li><a href="{{ base_url }}index.php?page=confirmpayments">Godkänn betalningar {% if show_adminpage_nbr_of_unconfirmed_payments > 0 %}<span class="badge">{{ show_adminpage_nbr_of_unconfirmed_payments }}</span>{% endif %}</a></li>
                            {% endif %}
                            {% if (show_adminpage_confirm_payments or
                                  show_adminpage_confirm_updated_user_information) and
                                  show_adminpage_view_statistics %}
                                <li class="divider"></li>
                            {% endif %}
                            {% if show_adminpage_view_statistics %}
                                <li><a href="{{ base_url }}index.php?page=sitestatistics">Statistik</a></li>
                            {% endif %}
                            {% if show_adminpage_view_users_and_groups %}
                                <li><a href="{{ base_url }}index.php?page=usersandgroups">Användare och grupper</a></li>
                            {% endif %}
                            
                            </ul>
                        </li>
{% endif %}
{% if 1 == 1 %}
                        <li>
                            <a href="{{ base_url }}index.php?page=minprofil">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-user fa-stack-1x fa-dark"></i>
                                </span>
                                <span class="hidden-sm"> Min profil</span>
                            </a>
                        </li>
{% endif %}
                        <li>
                            <a href="{{ base_url }}index.php?page=anmalningar">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-gamepad fa-stack-1x fa-dark"></i>
                                </span>
                                <span class="hidden-sm"> Anmälan WSK 2015</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ base_url }}index.php?page=arrangemang">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-puzzle-piece fa-stack-1x fa-dark"></i>
                                </span>
                                <span class="hidden-sm"> Arrangemang</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ base_url }}index.php?logout=logout">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-sign-out fa-stack-1x fa-dark"></i>
                                </span>
                                <span class="hidden-sm"> Logga ut</span>
                            </a>
                        </li>
                    </ul>
{% else %}
                    <form class="navbar-form navbar-right" role="form" id="user_login" name="user_login" action="dostuff.php" method="post">
                    	<input type="hidden" id="user_login_url" name="user_login_url" value="{{ this_url }}" />
                        <div class="form-group">
                            <input type="text" class="form-control" id="user_login_username" name="user_login_username" placeholder="Användarnamn" />
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="user_login_password" name="user_login_password" placeholder="Lösenord" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" id="user_login_submit" name="submit_dostuff" value="user_login_submit">Logga in <i class="fa fa-sign-in"></i></button>
                        <button type="submit" class="btn btn-social-icon btn-google-plus" title="Google+" name="submit_dostuff" value="social_login_google">
							<span class="fa fa-google-plus"></span>
                        </button>
                        <button type="submit" class="btn btn-social-icon btn-facebook" title="Facebook" name="submit_dostuff" value="social_login_facebook">
                        	<span class="fa fa-facebook"></span>
                        </button>
                    </form>
{% endif %}
                </div>
            </div>
        </div>