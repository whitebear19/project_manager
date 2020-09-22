@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
           <p class="dash_title">Settings</p>
        </div>
        <div class="col-md-12">
            <div class="card card-plain">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Change Password</h4>
                </div>

            </div>
            <div class="card-body">
                <form action="" id="password-form" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">New Password</label>
                    <input type="password" name="password" id="password1" class="form-control" >
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="password" id="password2" class="form-control">
                    </div>
                    <button type="button" id="password_save" class="btn btn-primary pull-right">Update</button>
                </form>

            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','#password_save',function(){
            var password1 = $('#password1').val();
            var password2 = $('#password2').val();

            if(password1 == "")
            {
                swal({ title:"Something wrong!", text: "Please enter newpassword.", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                return false;
            }
            else if(password1 != password2)
            {
                swal({ title:"Something wrong!", text: "The password confirmation does not match.", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                return false;
            }
            else if(password1.length < 8)
            {
                swal({ title:"Something wrong!", text: "The password must be at least 8 characters.", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                return false;
            }
            else
            {
                var data = $("#password-form").serialize();
                $.ajax({
                    url:"/ajax/update_password",
                    type: 'post',
                    dataType: 'json',
                    data: data,

                    success: function(result){
                        console.log(result);
                        if(result)
                        {
                            swal({ title:"Successful updated!", text: "", type: "success", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                        }
                        else if(result == 'auth')
                        {
                            location.reload();
                        }
                        else
                        {
                            swal({ title:"Something wrong!", text: "Please try again.", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                        }
                    }
                });
            }

        });
    });
</script>
@endsection
