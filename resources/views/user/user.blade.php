@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
           <p class="dash_title">User</p>
        </div>
        <div class="col-md-6" style="text-align: right;">
            <button class="btn btn-success" data-toggle="modal" data-target="#addUserModal">Add User</button>
         </div>
        <div class="col-md-12">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td><b>Name</b></td>
                                    <td><b>Email</b></td>
                                    <td><b>Action</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    <tr>
                                        <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            {{ $item->email }}
                                        </td>
                                        <td>
                                            <form action="{{ route('deleteuser') }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <button type="submit" rel="tooltip" class="btn btn-danger btn-mini">
                                                    <i class="material-icons">close</i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
{{-- ------------------ --}}
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="addUser-form" method="post">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" id="user_name" class="form-control">
                </div>
                <br>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" name="email" id="user_email" class="form-control">
                </div>
                <br>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" name="password" id="user_password" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btn-addUser" class="btn btn-primary">Add</button>
            </div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','#btn-addUser',function(){

        var data = $("#addUser-form").serialize();
        if($('#user_name').val() == '')
        {
            return false;
        }
        else if($('#user_email').val() == '')
        {
            return false;
        }
        else if($('#user_password').val() == '')
        {
            return false;
        }
        else
        {
            $.ajax({
                url:"/ajax/create_user",
                type: 'post',
                dataType: 'json',
                data: data,

                success: function(result){
                    if(result == '1')
                    {

                    $('#addUserModal').modal('hide');
                    swal({ title:"Successfuly sent!", text: "", type: "success", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                    location.reload();
                    }
                    else if(result == 'email')
                    {
                        $('#addUserModal').modal('hide');
                    swal({ title:"Something wrong!", text: "This email already exsist.", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                    }
                }
            });
        }
        });
    });
</script>
@endsection
