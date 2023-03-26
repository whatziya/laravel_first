@include('header')
<h1>Hi <span class="name"></span></h1>

<div class="email_verify">

    <p><b>Email: <span class="email"></span> &nbsp; <span class="verify"></span></b></p>

</div>

<form action="" id="profileForm">
    <input type="hidden" value="" name="id" id="user_id">
    <input type="text" name="name" placeholder="Enter Name" id="name">
    <br>
    <span class="error name_err" style="color:red;"></span>
    <br><br>
    <input type="email" name="email" placeholder="Enter Email" id="email">
    <br>
    <span class="error email_err" style="color:red;"></span>
    <br><br>
    <input type="submit" value="Update Profile">
</form>

<div class="result" style="color:green;"></div>

<script>

    $(document).ready(function(){
        $.ajax({

            url:"http://127.0.0.1:8000/api/profile",
            type:"GET",
            headers:{"Authorization":localStorage.getItem("user_token")},
            success:function(data){
                if (data.success == true) {
                    $("#user_id").val(data.data.id);
                    $(".name").text(data.data.name);
                    $(".email").text(data.data.email);
                    $("#name").val(data.data.name);
                    $("#email").val(data.data.email);

                    if (data.data.email_verified_at == null || data.data.email_verified_at == "") {
                        $(".verify").html("<button class='verify_mail' data-id='"+data.data.email+"'>Verify</button>");
                    }
                    else {
                        $(".verify").text("Verified");
                    }
                }
                else {
                    alert(data.msg);
                }
            }

        });

        $("#profileForm").submit(function(event){
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url:"http://127.0.0.1:8000/api/profile-update",
                type:"POST",
                data:formData,
                headers:{"Authorization":localStorage.getItem("user_token")},
                success:function(data){
                    if (data.success == true) {
                        console.log(data);
                        $(".error").text("");
                        setTimeout(function(){
                            $(".result").text("");
                        },1000);
                        $(".result").text("User Updated Successfully!");
                        $(".email").text(data.data.email);
                        if (data.data.email_verified_at == null || data.data.email_verified_at == "") {
                            $(".verify").html("<button class='verify_mail' data-id='"+data.data.email+"'>Verify</button>");
                        }
                        else {
                            $(".verify").text("Verified");
                        }
                    }
                    else {
                        printErrMsg(data);
                    }
                }
            });
        });


        function printErrMsg(msg) {
            $(".error").text("");
            $.each(msg,function(key, value){
                $("."+key+"_err").text(value);
            });
        }


        //email vrification api call

        $(document).on("click",".verify_mail",function(){
            var email =$(this).attr('data-id');
            $.ajax({
                url:'http://127.0.0.1:8000/api/send-verify-mail/'+email,
                type:'GET',
                headers:{'Authorization':localStorage.getItem("user_token")},
                success:function(data){
                    $('.result').text(data.msg);
                }
            });
        });


    });

</script>
