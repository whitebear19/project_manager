@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <p class="dash_title">View Project</p>
        </div>
        @if (Auth::user()->role > 0)
            <div class="col-md-6">
                <button class="btn_invite" data-toggle="modal" data-target="#inviteModal">Invite Collaborator</button>

            </div>
            <div class="col-md-6" style="text-align: right;">
                @if ($project->status == '0')
                    <form action="{{ route('movetoarchieve') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $project->id }}">
                        <button type="submit" class="btn btn-info">Complete Project</button>
                    </form>
                @endif


                <form action="{{ route('deleteproject') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $project->id }}">
                    <button type="submit" class="btn btn-denger">Delete Project</button>
                </form>
            </div>
        </div>

        @endif


        <div class="col-md-12">
            <form action="" id="project-form" method="POST">
                @csrf
                <input type="hidden" name="id" id="project_id" value="{{ $project->id }}">
                <div class="form-group">
                    <label for="">Title<span style="color: red;">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $project->title }}" @if (Auth::user()->role < 1)
                        readonly
                    @endif>
                </div>
                <br>
                <div class="form-group">
                    <label for="">Description<span style="color: red;">*</span></label>
                    <textarea type="text" name="description" id="description" class="form-control" @if (Auth::user()->role < 1)
                        readonly
                    @endif>{{ $project->description }}</textarea>
                </div>
                <br>
                <div class="form-group">
                    <label for="">Set a Due date(Optional)</label>
                    <input type="date" name="date" id="" class="form-control" value="{{ $project->date }}" @if (Auth::user()->role < 1)
                    readonly
                @endif>
                </div>
                @if (Auth::user()->role > 0)
                    <button type="button" id="project_update" class="btn btn-primary pull-right">Update</button>
                @endif

            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            @if (Auth::user()->role > 0)
                <div class="form-group" style="text-align: right;">
                    <button type="button" class="btn btn-success btn-round" data-toggle="modal" data-target="#newTaskModal">New Task</button>
                </div>
            @endif

            <div class="form-group">
                <p class="item_title">Task List</p>
                <br>
                <table class="table">
                    <tbody>
                        @foreach ($tasks as $item)
                            <tr>
                                <td class="">
                                    {{ $item->description }}
                                </td>
                                <td class="td-actions text-right">
                                    <form action="{{ route('deletetask') }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button type="submit" rel="tooltip" class="btn btn-danger">
                                            <i class="material-icons">close</i>
                                        </button>
                                    </form>

                                    <button type="button" rel="tooltip" data-id="{{ $item->id }}" data-status="{{ $item->status }}" data-description="{{ $item->description }}" id="btn-editTask" data-toggle="modal" data-target="#editTaskModal" class="btn btn-success">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" rel="tooltip" class="btn btn-info" data-id="{{ $item->id }}" data-toggle="modal" id="btn-commentMM" data-target="#newCommentModal">
                                        <i class="far fa-comment"></i>
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-5">
            <div>
                <div class="form-group" style="text-align: right;">
                    @if (Auth::user()->role > 0)
                        <label class="btn btn-success btn-round tg-fileuploadlabel" for="tg-photogallery1">

                            <span class="text-color-green" style="line-height:15px;">Add Attachment</span>
                            <input id="tg-photogallery1" class="tg-fileinput" type="file" name="" autocomplete="off" accept=".jpg, .jpeg, .png">
                        </label>
                    @endif

                </div>
                <br>
                <div class="form-group">
                    <p class="item_title">List of Attachment</p>
                    <br>
                    <table class="table">
                        <tbody>
                            @foreach ($attachments as $item)
                                <tr>
                                    <td>
                                        {{ $item->name }}
                                    </td>
                                    <td>
                                        <a href="/upload/attach/{{ $item->link }}" download="">Download</a>
                                    </td>
                                    <td>
                                        {{ date_format($item->created_at,"d/m/Y") }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <p class="item_title">List of Comment</p>

                <div class="height_fixed400">

                    <table class="table">
                        <tbody>
                            @foreach ($comments as $item)
                                <tr>
                                    <td>
                                        <span style="font-weight: 600;">{{ $item->user->name }} :</span>
                                        <p>{{ $item->content }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <form action="" id="comment-form" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $project->id }}">
                        <input type="hidden" name="taskid" value="4">
                        <textarea name="comment" id="comment" class="form-control" rows="2"></textarea>
                        <button type="button" id="btn-comment" class="btn btn-info btn-round">
                            Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="inviteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Invite Collaborator</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="invite-formM" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $project->id }}">
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="invite_name" id="invite_name" class="form-control">
                </div>
                <br>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" name="invite_email" id="invite_email" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btn-invite" class="btn btn-primary">Invite</button>
            </div>
        </form>
      </div>
    </div>
  </div>
  {{-- ---------------------------------- --}}

  <div class="modal fade" id="newTaskModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New Task</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="newtask-form" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $project->id }}">
            <div class="modal-body">

                <div class="form-group">
                    <label for="">Description</label>
                    <input type="text" name="description" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btn-newtask" class="btn btn-primary">Create</button>
            </div>
        </form>
      </div>
    </div>
  </div>

  {{-- ---------------------------- --}}

  <div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Task</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="edittask-form" method="post">
            @csrf
            <input type="hidden" id="task_id" name="id" value="">
            <div class="modal-body">

                <div class="form-group">
                    <label for="">Description</label>
                    <input type="text" id="task_description" name="description" class="form-control">
                </div>

                <div class="form-group">
                    <label for="">Status</label>
                    <select type="text" id="task_status" name="status" class="form-control">
                        <option value="0">Progressing</option>
                        <option value="1">Completed</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btn-updatetask" class="btn btn-primary">Update</button>
            </div>
        </form>
      </div>
    </div>
  </div>

{{-- ---------------------------------- --}}

<div class="modal fade" id="newCommentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New Comment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="comment-formM" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $project->id }}">
            <input type="hidden" name="taskid" id="taskidM" value="">
            <div class="modal-body">

                <div class="form-group">
                    <label for="">Content</label>
                    <textarea name="comment" id="commentM" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btn-commentM" class="btn btn-primary">Create</button>
            </div>
        </form>
      </div>
    </div>
  </div>


<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','#project_update',function(){
            var data = $("#project-form").serialize();
            if($('#title').val() == '')
            {
                swal({ title:"Something wrong!", text: "The title must be requeired.", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                return false;
            }
            else if($('#description').val() == '')
            {
                swal({ title:"Something wrong!", text: "The description must be requeired.", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                return false;
            }
            else
            {
                $.ajax({
                    url:"/ajax/update_project",
                    type: 'post',
                    dataType: 'json',
                    data: data,

                    success: function(result){
                        if(result)
                        {
                            swal({ title:"Successful updated!", text: "", type: "success", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                        }
                    }
                });
            }
        });

        $(document).on('click','#btn-newtask',function(){
            var data = $("#newtask-form").serialize();
            if($('#description').val() == '')
            {
                return false;
            }
            else
            {
                $.ajax({
                    url:"/ajax/create_task",
                    type: 'post',
                    dataType: 'json',
                    data: data,

                    success: function(result){
                        if(result)
                        {
                           location.reload();
                        }
                    }
                });
            }
        });

        $(document).on('click','#btn-editTask',function(){
            var description = $(this).data("description");
            var id = $(this).data("id");
            var status = $(this).data("status");
            $('#task_id').val(id);
            $('#task_description').val(description);
            $("#task_status option[value='"+status+"']").prop('selected', true);
        });

        $(document).on('click','#btn-updatetask',function(){
            var data = $("#edittask-form").serialize();
            if($('#task_description').val() == '')
            {
                return false;
            }
            else
            {
                $.ajax({
                    url:"/ajax/update_task",
                    type: 'post',
                    dataType: 'json',
                    data: data,

                    success: function(result){
                        if(result)
                        {
                           location.reload();
                        }
                    }
                });
            }
        });

        var file = document.getElementById('tg-photogallery1');
        var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'doc', 'docx', 'xls', 'xlsx'];


        $("#tg-photogallery1").change(function()
        {

            for (let index = 0; index < this.files.length; index++) {

                if (this.files && this.files[index])
                {
                    if(this.files[index].size > 2000000)
                    {
                        alert("File size should not exceed 2mb !");
                        return false;
                    }
                }
            }

            for (let index = 0; index < this.files.length; index++) {

                if (this.files && this.files[index])
                {
                    if ($.inArray(this.files[index]['name'].split('.').pop().toLowerCase(), fileExtension) == -1) {
                        alert("Only formats are allowed : "+fileExtension.join(', '));
                        $("#tg-photogallery1").val("");
                        return false;
                    }
                    if(this.files[index].size > 2000000)
                    {
                        alert("File size should be less than 2Mb.");
                        return false;
                    }
                    var formdata = new FormData;
                    formdata.append('attachment',this.files[index]);
                    formdata.append('id',$("#project_id").val());
                    formdata.append('_token',$('meta[name=csrf-token]').attr("content"));

                    $.ajax({
                        url: "/ajax/uploadattach",
                        data: formdata,
                        dataType: "json",
                        type: "post",

                        processData: false,
                        contentType: false,

                        success: function(data){
                            location.reload();
                        }
                    });
                }
            }

        });



        $(document).on('click','#btn-comment',function(){
            var data = $("#comment-form").serialize();
            if($('#comment').val() == '')
            {
                return false;
            }
            else
            {
                $.ajax({
                    url:"/ajax/create_comment",
                    type: 'post',
                    dataType: 'json',
                    data: data,

                    success: function(result){
                        if(result)
                        {
                           location.reload();
                        }
                    }
                });
            }
        });


        $(document).on('click','#btn-commentMM',function(){

            var id = $(this).data("id");
            console.log(id);
            $('#taskidM').val(id);

        });
        $(document).on('click','#btn-commentM',function(){
            var data = $("#comment-formM").serialize();
            if($('#commentM').val() == '')
            {
                return false;
            }
            else
            {
                $.ajax({
                    url:"/ajax/create_comment",
                    type: 'post',
                    dataType: 'json',
                    data: data,

                    success: function(result){
                        if(result)
                        {
                           location.reload();
                        }
                    }
                });
            }
        });
        $(document).on('click','#btn-invite',function(){

            var data = $("#invite-formM").serialize();
            if($('#invite_name').val() == '')
            {
                alert()
                return false;
            }
            else if($('#invite_email').val() == '')
            {
                alert();
                return false;
            }
            else
            {
                $.ajax({
                    url:"/ajax/create_invite",
                    type: 'post',
                    dataType: 'json',
                    data: data,

                    success: function(result){
                        if(result == '1')
                        {

                           $('#inviteModal').modal('hide');
                           swal({ title:"Successfuly sent!", text: "", type: "success", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                        }
                        else if(result == 'email')
                        {
                            $('#inviteModal').modal('hide');
                           swal({ title:"Something wrong!", text: "This email already exsist.", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                        }
                    }
                });
            }
        });

    });
</script>
@endsection
