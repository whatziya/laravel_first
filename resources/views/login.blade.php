
@include('header')

<style>

    span{
        color: red;
    }

</style>

<h1>Login</h1>

<form id="login_form">

    <input type="email" name="email" placeholder="Enter Email">
    <br>
    <span class="error email_err"></span>
    <br><br>
    <input type="password" name="password" placeholder="Enter Password">
    <br>
    <span class="error password_err"></span>
    <br><br>
    <input type="submit" value="Login">
    <button class="register">Register</button>
</form>
<br>
<p class="result"><p>

<script>
    $(".register").click(function(){
        window.open("/register","_self");
    });
    $(document).ready(function(){
        $("#login_form").submit(function(event){
            event.preventDefault();

            var fromData = $(this).serialize();

            $.ajax({
                url:"/api/login",
                type:"POST",
                data:fromData,
                success:function(data){
                    $(".error").text("");
                    if (data.success == false) {
                        $(".result").text(data.msg);
                    }
                    else if (data.success == true) {
                        // console.log(data);
                        localStorage.setItem("user_token",data.token_type+" "+data.access_token);
                        window.open("/profile","_self");
                    }
                    else {
                        printErrorMsg(data);
                    }
                }
            });
        });

        function printErrorMsg(msg) {
            $(".error").text("");
            $.each(msg,function(key, value){
                $("."+key+"_err").text(value);
            });
        }
    });

</script>
