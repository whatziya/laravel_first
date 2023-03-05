<!DOCTYPE html>
<html lang="en">
<head>
    <title>Example API</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

    <button class="logout">Logout</button>
    <button class="refresh">Refresh User</button>
    
</body>
<script>

    var token = localStorage.getItem("user_token");

    if (window.location.pathname == "/login" || window.location.pathname == "/register") {
        if (token != null) {
            window.open("/profile","_self");
        }
        $(".logout").hide();
    }
    else {
        console.log(token);
        if (token == null) {
            window.open("/login","_self");
        }
    }

    if (window.location.pathname == "/login" || window.location.pathname == "/register") {
        $(".refresh").hide();
    }

    //logout user

    $(document).ready(function(){
        $(".logout").click(function(){
            $.ajax({
                url:"http://127.0.0.1:8000/api/logout",
                type:"GET",
                headers:{"authorization": localStorage.getItem("user_token")},
                success:function(data){
                    if (data.success == true) {
                        localStorage.removeItem("user_token");
                        window.open("/login", "_self");
                    }
                    else {
                        alert(data.msg);
                    }
                }
            });
        });

        //refresh token api

        $(".refresh").click(function(){

            $.ajax({
                url:"http://127.0.0.1:8000/api/refresh-token",
                type:"GET",
                headers:{"Authorization":localStorage.getItem("user_token")},
                success:function(data){
                    if (data.success == true){
                        localStorage.setItem("user_token",data.token_type+" "+data.access_token);
                        alert("User is Refreshed.");
                    } 
                    else {
                        alert(data.msg);
                    }
                }
            });

        });
    });

</script>
</html>