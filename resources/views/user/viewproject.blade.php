@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 class="project-title">
                {{ $project->title }}
            </h1>
        </div>
        @if (Auth::user()->role == 1)
            <div class="col-md-6">
                <button class="btn_invite" data-toggle="modal" data-target="#inviteModal">Invite Collaborator</button>

            </div>
            <div class="col-md-6" style="text-align: right;">

                <form action="{{ route('deleteproject') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $project->id }}">
                    <button type="submit" class="btn btn-denger">Delete Project</button>
                </form>
            </div>
        </div>

        @endif
        <br>
        <br>

        <div class="col-md-12">
            <form action="" id="project-form" method="POST">
                @csrf
                <input type="hidden" name="id" id="project_id" value="{{ $project->id }}">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="">Description<span style="color: red;">*</span></label>
                            <textarea type="text" name="description" id="description" class="form-control" @if (Auth::user()->role < 1)
                                readonly
                            @endif>{{ $project->description }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Set a Due date(Optional)</label>
                            <br>
                            <input type="date" name="date" id="" class="form-control" value="{{ $project->date }}" @if (Auth::user()->role < 1)
                            readonly
                        @endif>
                        </div>
                    </div>
                </div>



                @if (Auth::user()->role == 1)
                    <button type="button" id="project_update" class="btn btn-primary pull-right">Update</button>
                @endif

            </form>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-7">
            @if (Auth::user()->role == 1)
                <div class="form-group" style="text-align: right;">
                    <button type="button" class="btn btn-success btn-round btn-newtask" data-toggle="modal" data-target="#newTaskModal">New Task</button>
                </div>
            @endif

            <div class="form-group">
                <div class="text-center">
                    <p class="item_title">Task List</p>
                </div>

                <br>
                <table class="table">
                    <tbody>
                        @foreach ($results as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" data-id="{{ $item['id'] }}" class="chk_task_id" @if ($item['status'] == 1) checked @endif>
                                </td>
                                <td class="">
                                    {{ $item['title'] }}
                                </td>
                                <td class="td-actions text-right">
                                    <form action="{{ route('deletetask') }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item['id'] }}">
                                        <button type="submit" rel="tooltip" class="btn btn-danger">
                                            <i class="material-icons">close</i>
                                        </button>
                                    </form>

                                    <button type="button" rel="tooltip" data-id="{{ $item['id'] }}" data-status="{{ $item['status'] }}" data-title="{{ $item['title'] }}" data-description="{{ $item['description'] }}" id="btn-editTask" data-toggle="modal" data-target="#editTaskModal" class="btn btn-success">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" rel="tooltip" class="btn btn-info @if (!empty($item['comment_id'])) alreadycomment @endif" data-id="{{ $item['id'] }}" data-cid="{{ $item['comment_id'] }}" data-comment="{{ $item['comment'] }}" data-toggle="modal" id="btn-commentMM" data-target="#newCommentModal">
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
                    @if (Auth::user()->role == 1)
                        <label class="btn btn-success btn-round tg-fileuploadlabel" for="tg-photogallery1">

                            <span class="text-color-green" style="line-height:15px;">Add Attachment</span>
                            <input id="tg-photogallery1" class="tg-fileinput" type="file" name="" autocomplete="off" accept=".jpg, .jpeg, .png">
                        </label>
                    @endif

                </div>
                <br>
                <div class="form-group">
                    <div class="text-center">
                        <p class="item_title">List of Attachment</p>
                    </div>
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
                <div class="text-center">
                    <p class="item_title">Comments</p>
                </div>
                <br>

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

                <div class="form-group">
                    @if (Auth::user()->role == 1)
                        <div>
                            <a class="btn btn-success" href="{{ route('dashboard') }}">Save</a>&nbsp;&nbsp;
                            <a class="btn btn-error" href="{{ route('dashboard') }}">Cancel</a>&nbsp;&nbsp;
                            @if ($project->status == '0')
                                <form action="{{ route('movetoarchieve') }}" style="display: inline-block" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $project->id }}">
                                    <button type="submit" class="btn btn-info">Complete Project</button>
                                </form>
                            @endif
                        </div>
                    @endif


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
                    <label for="">Title</label>
                    <input type="text" name="title" id="task_title" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <input type="text" name="description" id="task_description" class="form-control">
                </div>
                <div class="form-group">
                    <label class="btn btn-success btn-round tg-fileuploadlabel" for="task_attach">

                        <span class="text-color-green" style="line-height:15px;">Add Attachment</span>
                        <input id="task_attach" class="tg-fileinput" type="file" name="" autocomplete="off" accept=".jpg, .jpeg, .png">
                    </label>
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
                    <label for="">Title</label>
                    <input type="text" id="task_titleEdit" name="title" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <input type="text" id="task_descriptionEdit" name="description" class="form-control">
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
          <h5 class="modal-title" id="commentModalTitle">New Comment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="comment-formM" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $project->id }}">
            <input type="hidden" name="taskid" id="taskidM" value="">
            <input type="hidden" name="commentid" id="commentidM" value="">
            <div class="modal-body">

                <div class="form-group">
                    <label for="">Content</label>
                    <textarea name="comment" id="commentM" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer commentModalFooter">
                <button type="button" class="btn btn-secondary btn-normal" data-dismiss="modal">Close</button>
                <button type="button" id="btn-commentM" class="btn btn-primary">Save</button>
                <button type="button" id="btn-commentD" class="btn btn-denger" data-cid="">Delete</button>
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

            var file = document.getElementById('task_attach');
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'doc', 'docx', 'xls', 'xlsx'];


            if($('#task_title').val() == '')
            {
                alert("Please input title");
                return false;
            }
            else if($('#task_description').val() == '')
            {
                alert("Please input description");

                return false;
            }
            else
            {
                var formdata = new FormData;
                formdata.append('id',$("#project_id").val());
                formdata.append('title',$("#task_title").val());
                formdata.append('description',$("#task_description").val());
                formdata.append('_token',$('meta[name=csrf-token]').attr("content"));
                formdata.append('attach',file.files[0]);
                if (file.files[0])
                {

                    if ($.inArray(file.files[0]['name'].split('.').pop().toLowerCase(), fileExtension) == -1) {
                        alert("Only formats are allowed : "+fileExtension.join(', '));
                        $("#task_attach").val("");
                        return false;
                    }
                    if(file.files[0].size > 2000000)
                    {
                        alert("File size should be less than 2Mb.");
                        return false;
                    }
                    formdata.append('attach',file.files[0]);

                }

                $.ajax({
                    url: "/ajax/create_task",
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
        });

        $(document).on('click','.btn-newtask',function(){
            $("#task_attach").val("");
            $("#task_title").val("");
            $("#task_description").val("");
        });
        $(document).on('click','#btn-editTask',function(){
            var title = $(this).data("title");
            var description = $(this).data("description");
            var id = $(this).data("id");
            var status = $(this).data("status");
            $('#task_id').val(id);
            $('#task_titleEdit').val(title);
            $('#task_descriptionEdit').val(description);
            $("#task_status option[value='"+status+"']").prop('selected', true);
        });

        $(document).on('click','#btn-updatetask',function(){
            var data = $("#edittask-form").serialize();
            if($('#task_titleEdit').val() == '')
            {
                return false;
            }
            else if($('#task_descriptionEdit').val() == '')
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
            var commentId = $(this).data('cid');
            if(commentId)
            {
                $('#commentModalTitle').html('Edit Comment');
                $('#commentM').html($(this).data('comment'));
                $('#commentidM').val(commentId);
                $('#btn-commentM').html('Update');
                $('#btn-commentD').css('display','block');
                $('#btn-commentD').data('cid',commentId);
            }
            else
            {
                $('#commentModalTitle').html('Add Comment');
                $('#commentM').html("");
                $('#commentidM').val("");
                $('#btn-commentM').html('Save');
                $('#btn-commentD').css('display','none');
                $('#btn-commentD').data('cid','');
            }


        });

        $(document).on('click','#btn-commentD',function(){
            var cid = $(this).data('cid');
                $.ajax({
                    url:"/ajax/delete_comment",
                    type: 'get',
                    dataType: 'json',
                    data: {cid:cid},

                    success: function(result){
                        if(result)
                        {
                            location.reload();
                        }
                    }
                });
        });

        $(document).on('click','#btn-commentM',function(){
            var data = $("#comment-formM").serialize();
            if($('#commentM').val() == '')
            {
                return false;
            }
            else if($('#commentM').val() == '')
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

        $(".chk_task_id").change(function(){
            var id = $(this).data('id');
            if ($(this).is(":checked"))
            {
                var r = confirm("Are you sure this task to be completed?");
                if (r == true) {
                    var status = '1';

                    $.ajax({
                        url:"/ajax/complete_task",
                        type: 'get',
                        dataType: 'json',
                        data: {id:id,status:status},

                        success: function(result){
                            if(result == '1')
                            {
                                location.reload();
                            }
                        }

                    });
                }

            }
            else
            {
                var r = confirm("Are you sure this task to be processing?");
                if (r == true) {
                    var status = '0';
                    $.ajax({
                        url:"/ajax/complete_task",
                        type: 'get',
                        dataType: 'json',
                        data: {id:id,status:status},

                        success: function(result){
                            if(result == '1')
                            {
                                location.reload();
                            }
                        }

                    });
                }
            }
        });

    });
</script>
@endsection
