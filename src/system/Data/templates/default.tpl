<!DOCTYPE html>
<html lang="sv">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- <link rel="icon" href="favicon.ico"> -->

        <title>{{ title }}</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="{{base_url}}js/bootstrap.min.js"></script>
        <script src="{{base_url}}js/ie10-viewport-bug-workaround.js"></script>

        <link href='http://fonts.googleapis.com/css?family=Lobster|Voltaire|Shadows+Into+Light+Two|Patua+One|Merriweather+Sans:700,300italic,700italic,300' rel='stylesheet' type='text/css'>
        <link href="{{base_url}}css/bootstrap.min.css" rel="stylesheet">
        <link href="{{base_url}}css/bootstrap-custom-theme.css" rel="stylesheet">
        <link href="{{base_url}}css/font-awesome.css" rel="stylesheet">
        <link href="{{base_url}}css/bootstrap-social.css" rel="stylesheet">
        <link href="{{base_url}}css/butiken.css" rel="stylesheet">

{{ head_local }}

        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body{{show_alerts_body}}>

{% if user_logged_in %}{% else %}
		<div class="modal" id="loading_screen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display:none;">
			<div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-body">
			        <h1 class="text-center">Laddar. Var god vänta&hellip; <span class="fa fa-refresh fa-spin"></span></h1>
			      </div>
			    </div>
			  </div>
		</div>
{% endif %}

{{ show_alerts }}
{% include 'menu.tpl' %}
{{ content_top }}
        <div class="container">
{{ content_main }}
        </div>

        <div class="container">
            <div class="col-xs-12">
{% include 'footer.tpl' %}
            </div>
        </div>

{{ show_alerts_javascript }}

        <script>
	        $(document).ready(function() {
	            $('abbr, .has-tooltip').tooltip({
	                container: 'body'
	            });
	            $('.has-popover').popover();
	        });
        </script>
{% if user_logged_in %}{% else %}
        <script>
	        $('#user_login_submit').click(function() {
	        	$("#loading_screen").modal();
	        });
        </script>
{% endif %}

{{ content_bottom }}

    </body>
</html>