<?php
require_once( "inc/logging.inc.php" );
require_once( "inc/is_logged_in.inc.php" );
require_once( "inc/mysql.inc.php" );
require_once( "inc/search.inc.php" );

$personalities = [];

if( is_logged_in() ) {
    $session_start;
    $member_id = $_SESSION["member_id"];

    $search_results = [];
    if( isset($_GET["recommended"]) or isset($_POST["recommended"]) ) {
        $search_results = search_recommended_users( $member_id );
        
    }
    elseif ( isset($_POST["search"]) ) {
        if ( isset($_POST["searchtext"]) ) {
            $search_results = search_users( $member_id, $_POST["searchtext"] );
        }
    }
}
else
    header( "location: /luv" );
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <title>Search Recommended</title>
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="shortcut icon" href="img/rating/rating.png">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script> 
</head>
<body class="landing-page search-page">
    
    <div class="navbar">
        <div id="nav-placeholder"></div>

        <script>
            $(function(){
              $("#nav-placeholder").load("modules/nav.html");
            });
        </script>
    </div>
    
    
    <div class="search-recommended-main-div">
        <form class="search-sub-div" action="search-recommended-page.php" method="post">
            <input type="text" id="search-text" name="searchtext" placeholder="Search Users"/>
            <button type="submit" id="search-button" name="search">Search</button>
            <button type="submit" id="recommended-button" name="recommended">Recommended</button>
        </form>
    </div>
    
    <div id="search-result-wrapper" class = "wrapper-div scrollable"></div>

    <script>
        var search_results = <?php echo json_encode($search_results); ?>;
        search_results.forEach( member => {

            // PLACEHOLDER CONSOLE OUTPUT

            var name = member.name;
            var aboutMe = member.about_me;
            var picture = member.picture;
            var rating = member.rating;
            var member_id = member.member_id;

            var profile_html = 
                    "<div class='search-profile-image-div'> " +
                        "<img src='" + picture  +"' alt='Profile Picture' class ='reviewProfilePic'> " +
                        "<p id='search-profile-user-name' class='profile-username'>" + name + "</p> " +
                    "</div> " +

                    "<div class='search-profile-feedback-div'> " +

                        "<textarea readonly class='search-profile-aboutme-text-area' rows=8 cols=80 style='resize: none'>" + aboutMe + "</textarea> ";
                
                profile_html += " <div class='review-stars-flex-div'> "
            
                var i;
                for(i = 0; i < rating; i++)
                {
                    profile_html += 
                        "<div class='review-stars-div'> " +
                        "    <img src='img/rating/rating.png' />  " +
                        "</div> ";
                }

                for( ; i < 5; i++ ) {
                    profile_html += 
                        "<div class='review-stars-div'> " +
                        "    <img src='img/rating/rating_greyscale.png' />  " +
                        "</div> ";
                }
            
                profile_html += " </div> "
                
                profile_html +=
                    "</div> " +

                     " <!-- buttons -->  " +
                    "<div class='seach-profile-button-div'>  " +
                    "    <form class='search-profile-form' action='ViewOthersProfile.php' method='post'> " +
                    "        <input type='submit' class='search-profile-button search-profile-view-button' value='View'/>" + 
                            "<input type='hidden' name='target_id' value='" + member_id + "'/> " +
                        "</form> " + 
                    "   <form class='search-profile-form' action='messages.php' method='post'> " +
                    "        <input type='submit' class='search-profile-button search-profile-view-button' value='Message'/> " +
                    "        <input type='hidden' name='target_id' value='" + member_id + "'/> " +
                        "</form> " +
                    "</div>  ";

            var parent_div = document.createElement("DIV");
            parent_div.classList.add("search-result-recommended-parent-div");
            parent_div.innerHTML = profile_html;
            document.getElementById("search-result-wrapper").appendChild(parent_div);
        });
    </script>
    
</body>
</html>