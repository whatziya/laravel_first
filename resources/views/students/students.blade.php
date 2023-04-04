@include('header')

<button class="back">Back to profile</button>
<button class="store-student">Add Student</button>
<span class="output"></span>
<table id="table-data" border="1" style="margin:10px 0;">
    <tr>
        <th>No</th>
        <th>Id</th>
        <th>Firstname</th>
        <th>Surname</th>
        <th>Lastname</th>
        <th>Birthdate</th>
        <th>Group</th>
        <th>Edit/Delete</th>
    </tr>
</table>

<script>
    $(".logout").hide();
    $(".refresh").hide();
    $(".students").hide();
    $(".back").click(function(){
        window.open("/profile","_self");
    });
    $(".store-student").click(function(){
        window.open("/store-student","_self");
    });

    $(document).ready(function(){

        $.ajax({
            type:"GET",
            url:"/api/students",
            headers:{"authorization": localStorage.getItem("user_token")},
            success:function(data){
                console.log(data);
                if (data.students.length > 0) {
                    for (let i = 0; i < data.students.length; i++) {
                        $("#table-data").append(`<tr>
                        <td>`+(i+1)+`</td>
                        <td>`+(data.students[i]['id'])+`</td>
                        <td>`+(data.students[i]['firstname'])+`</td>
                        <td>`+(data.students[i]['surname'])+`</td>
                        <td>`+(data.students[i]['lastname'])+`</td>
                        <td>`+(data.students[i]['birthdate'])+`</td>
                        <td>`+(data.students[i]['group'])+`</td>
                        <td> <input type="button" onclick="location.href='/edit-student/`+(data.students[i]['id'])+`';" value="Edit" />
                             <input type="button" class="delete" onclick="location.href='#'" data-id="`+(data.students[i]['id'])+`" value="Delete" />
                        </td>
                        </tr>`);

                    }
                } else {
                    $("#table-data").append("<tr><td colspan='4'>Data Not Found</td></tr>")
                }
            },
            error:function(err){
                console.log(err.responseText);
            }
        });

        $("#table-data").on("click",".delete",function(){
            var id = $(this).attr("data-id");
            var obj = $(this);

            $.ajax({
                type:"delete",
                url:"api/students/"+id,
                success:function(){
                    $(obj).parent().parent().remove();
                    $("#output").text(data.result);
                },
                error:function(err){
                    console.log(err.responseText);
                }
            });
        });

    });
</script>
