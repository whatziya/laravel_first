@include('header')

<style>

    span {
        color: red;
    }

</style>

<h1>User Registration</h1>

<form id="register_form">

    <input type="text" name="name" placeholder="Enter Name">
    <br>
    <span class="error name_err"></span>
    <br><br>
    <input type="email" name="email" placeholder="Enter Email">
    <br>
    <span class="error email_err"></span>
    <br><br>
    <input type="password" name="password" placeholder="Enter Password">
    <br>
    <span class="error password_err"></span>
    <br><br>
    <input type="password" name="password_confirmation" placeholder="Confirm Your Password">
    <br>
    <span class="error password_confirmation_err"></span>
    <br><br>
    <input type="submit" value="Register">
    <button class="login">Back</button>
</form>
<br>
<p class="result">
<p>

    <script>
        $(".login").click(function () {
            window.open("/login", "_self");
        });
        $(document).ready(function () {
            $("#register_form").submit(function (event) {
                event.preventDefault();

                var fromData = $(this).serialize();

                $.ajax({
                    url: "http://127.0.0.1:8000/api/register",
                    type: "POST",
                    data: fromData,
                    success: function (data) {
                        if (data.msg) {
                            $("#register_form")[0].reset();
                            $(".error").text("");
                            $(".result").text(data.msg);
                            window.open("/login", "_self");
                        } else {
                            printErrorMsg(data);
                        }
                    }
                });

            });

            function printErrorMsg(msg) {
                $(".error").text("");
                $.each(msg, function (key, value) {
                    if (key == "password") {
                        if (value.length > 1) {
                            $(".password_err").text(value[0]);
                            $(".password_confirmation_err").text(value[1]);

                        } else {
                            if (value[0].includes("password confirmation")) {
                                $(".password_confirmation_err").text(value);
                            } else {
                                $(".password_err").text(value);
                            }
                        }
                    } else {
                        $("." + key + "_err").text(value);
                    }
                });
            }
        });

    </script>
