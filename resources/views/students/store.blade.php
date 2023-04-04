@include('header')

<style>

    span {
        color: red;
    }

</style>

<button class="back-students">Back</button>

<h1>Add Student</h1>


<form id="students-store_form">

    <input type="text" name="firstname" placeholder="Enter Name">
    <br>
    <span class="error firstname_err"></span>
    <br><br>
    <input type="text" name="surname" placeholder="Enter Surname">
    <br>
    <span class="error surname_err"></span>
    <br><br>
    <input type="text" name="lastname" placeholder="Enter Lastname">
    <br>
    <span class="error lastname_err"></span>
    <br><br>
    <input type="date" name="birthdate" placeholder="Enter Birthdate">
    <br>
    <span class="error birhtdate_err"></span>
    <br><br>
    <input type="text" name="group" placeholder="Enter Group">
    <br>
    <span class="error group_err"></span>
    <br><br>
    <input type="submit" value="Add">

</form>
<br>
<p class="result">
<p>

    <script>

        $(".logout").hide();
        $(".refresh").hide();
        $(".students-store").hide();
        $(".students").hide();
        $(".back-students").click(function () {
            window.open("/students", "_self");
        });

        $(document).ready(function () {
            $("#students-store_form").submit(function (event) {
                event.preventDefault();

                var fromData = $(this).serialize();

                $.ajax({
                    url: "/api/students",
                    type: "POST",
                    headers: {"Authorization": localStorage.getItem("user_token")},
                    data: fromData,
                    success: function (data) {
                        if (data.msg) {
                            $("#students-store_form")[0].reset();
                            $(".error").text("");
                            $(".result").text(data.msg);
                            window.open("/students", "_self");
                        } else {
                            printErrorMsg(data);
                        }
                    }
                });

            });

            function printErrorMsg(msg) {
                $(".error").text("");
                $.each(msg, function (key, value) {
                    $("." + key + "_err").text(value);
                });
            }
        });

    </script>
