@include('header')
<button class="back">Back</button><br><br>
<form id="update-form">

    @csrf
    <input type="text" name="firstname" value="{{ $student[0]->firstname }}" placeholder="Enter Firstname" required>
    <br><br>
    <input type="text" name="surname" value="{{ $student[0]->surname }}" placeholder="Enter Surname" required>
    <br><br>
    <input type="text" name="lastname" value="{{ $student[0]->lastname }}" placeholder="Enter Lastname" required>
    <br><br>
    <input type="date" name="birthdate" value="{{ $student[0]->birthdate }}" placeholder="Enter Birthdate" required>
    <br><br>
    <input type="text" name="group" value="{{ $student[0]->group }}" placeholder="Enter Group" required>
    <input type="hidden" name="id" value="{{ $student[0]->id }}">
    <br><br>
    <input type="submit" value="Update Data">

</form>
<span id="output"></span>
<script>
    $(".logout").hide();
    $(".refresh").hide();
    $(".students").hide();
    $(".back").click(function(){
        window.open("/get-students","_self");
    });
    $(document).ready(function(){
        $("#update-form").submit(function(event){

            event.preventDefault();

            var form = $("#update-form")[0];
            var data = new FormData(form);

            $.ajax({
                type:"POST",
                url:"{{ route('updateStudent') }}",
                data:data,
                processData:false,
                contentType:false,
                success:function(data){
                    $("#output").text(data.result);
                    window.open("/get-students","_self");
                },
                error:function(err){
                    $("#output").text(err.responseText);
                }
            });
        });
    });
</script>