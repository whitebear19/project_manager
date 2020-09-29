@extends('layouts.user')

@section('content')
<script>
    $(document).ready(function(){
        $(".datepicker").datepicker();
        $(".datepicker").change(function(){

            $(".datepicker_caption").html($(this).val());

            $('.btn_delete_date').css('display','inline-block');

        });

        $('.btn_delete_date').click(function(){
            $('.datepicker').val('');
            $(".datepicker_caption").html('');
            $('.btn_delete_date').css('display','none');
        });
    });

</script>
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
                            <div class="text-center">
                                <label for="" class="text-border" style="border: 1px solid #222222;padding:5px;border-radius:5px;">Description<span style="color: red;">*</span></label>
                            </div>

                            <textarea type="text" name="description" id="description" rows="6" class="form-control input-border" @if (Auth::user()->role < 1)
                                readonly
                            @endif>{{ $project->description }}</textarea>
                            <div class="text-center">
                                @if (Auth::user()->role == 1)
                                    <button type="button" id="project_update" class="btn btn-primary">Update</button>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="text-center">
                                <label for="">Set a Due date(Optional)</label>
                                <br>
                                <div class="pos-rel" style="width:70px;height:80px;margin:auto;">
                                    <i class="fas fa-calendar-week" id="btn_calander_show" style="font-size: 80px;color:#20a4b9;" aria-hidden="true"></i>
                                    <input type="text" name="date" class="datepicker date_customize" value="{{ $project->date }}">
                                </div>
                                <br>
                                <span class="datepicker_caption">
                                    {{ $project->date }}
                                </span>
                                &nbsp;&nbsp;

                                <button class="btn_trans btn_delete_date @if (empty($project->date)) dispnone @endif" type="button"><i class="fas fa-times"></i></button>

                            </div>
                        </div>
                    </div>
                </div>





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
                <table class="table input-border tasktable">
                    <tbody>
                        @foreach ($results as $item)
                            <tr>
                                <td width="20">
                                    <input type="checkbox" data-id="{{ $item['id'] }}" class="chk_task_id" @if ($item['status'] == 1) checked @endif>
                                </td>
                                <td class="">
                                    <span data-content="{{ $item['description'] }}" data-attach="{{ $item['attach'] }}" class="btn_viewTaskContent @if ($item['status'] == 1)
                                        text-cross
                                    @endif" data-toggle="modal" data-target="#viewTaskContent">{{ $item['title'] }}</span>
                                </td>
                                <td class="td-actions text-right" width="110">
                                    <form action="{{ route('deletetask') }}" method="POST" style="display: inline-block;margin-bottom:0px;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item['id'] }}">
                                        <button type="submit" rel="tooltip" class="btn btn-danger">
                                            <i class="material-icons">close</i>
                                        </button>
                                    </form>

                                    <button type="button" rel="tooltip" data-id="{{ $item['id'] }}" data-status="{{ $item['status'] }}" data-title="{{ $item['title'] }}" data-description="{{ $item['description'] }}" id="btn-editTask" data-toggle="modal" data-target="#editTaskModal" class="btn btn-success">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button type="button" rel="tooltip" class="btn btn-info @if (!empty($item['comment_id'])) alreadycomment @endif" data-id="{{ $item['id'] }}" data-cid="{{ $item['comment_id'] }}" data-comment="{{ $item['comment'] }}" data-view="{{ $item['view'] }}" data-link="{{ $item['link'] }}" data-toggle="modal" id="btn-commentMM" data-target="#newCommentModal">
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
                <div class="form-group">
                    <div class="text-center">
                        <p class="item_title">List of Attachment &nbsp;&nbsp;&nbsp;
                            @if (Auth::user()->role == 1)
                            <label class="btn btn-success btn-round tg-fileuploadlabel" for="tg-photogallery1">

                                <span class="text-color-green" style="line-height:15px;">Add Attachment</span>
                                <input id="tg-photogallery1" class="tg-fileinput" type="file" name="" autocomplete="off" accept=".jpg, .jpeg, .png">
                            </label>
                        @endif</p>
                    </div>
                    <br>
                    <table class="table">
                        <tbody>
                            @foreach ($attachArray as $item)
                                <tr>
                                    <td>
                                        {{ $item['name'] }}
                                    </td>
                                    <td>
                                        @if (!empty($item['view']))
                                            <button class="btn_trans btn_viewattach" data-view="{{ $item['link'] }}" data-toggle="modal" data-target="#viewAttach">View</button>
                                        @else
                                            <a href="/upload/attach/{{ $item['link'] }}">View</a>
                                        @endif

                                    </td>
                                    <td>
                                        <a href="/upload/attach/{{ $item['link'] }}" download="">Download</a>
                                    </td>
                                    <td>
                                        {{ date_format($item['created_at'],"d/m/Y") }}
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

                <div class="height_fixed400 input-border">
                    <ul class="ul-list-none">
                        @foreach ($comments as $item)
                            <li class="">
                                 <span style="font-weight: 600;">{{ $item->user->name }} :</span>
                                <span>{{ $item->content }}</span>
                                @if (!empty($item->attach))
                                    <br>
                                    <a href="/upload/attach/{{ $item->attach }}" download="">{{ $item->attach }}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="form-group">
                    <form action="" id="comment-form" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="project_id_page" value="{{ $project->id }}">
                        <input type="hidden" name="taskid" value="">
                        <textarea name="comment" id="comment" class="form-control" rows="2" required></textarea>
                        <button type="button" id="btn_comment_page" class="btn btn-info btn-round">
                            Post
                        </button>
                        &nbsp;&nbsp;
                        <label class="tg-fileuploadlabel" for="comment_attachPost">

                            <span class="text-color-green" style="line-height:15px;color:#222222;cursor:pointer;"><i class="fas fa-paperclip"></i></span>
                            <input id="comment_attachPost" class="tg-fileinput" type="file" name="attach" autocomplete="off" accept=".jpg, .jpeg, .png">
                        </label>
                    </form>
                </div>

                <div class="form-group">
                    @if (Auth::user()->role == 1)
                        <div>
                            <a class="btn btn-success" href="{{ route('dashboard') }}" style="float: left;">Return</a>

                            @if ($project->status == '0')
                                <form action="{{ route('movetoarchieve') }}" style="display: inline-block; float:right;" method="post">
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
                    <textarea type="text" name="description" rows="5" id="task_description" class="form-control"></textarea>
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
                    <textarea type="text" id="task_descriptionEdit" rows="5" name="description" class="form-control"></textarea>
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
            <input type="hidden" name="id" id="proejct_id" value="{{ $project->id }}">
            <input type="hidden" name="taskid" id="taskidM" value="">
            <input type="hidden" name="commentid" id="commentidM" value="">
            <div class="modal-body">

                <div class="form-group">
                    <label for="">Content</label>
                    <textarea name="comment" id="commentM" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label for="">Attachment</label>
                    <img src="" class="comment_attach" style="width: 100%;" alt="">
                    <a href="" class="download_comment_attach" style="display: none;" download="">Download</a>
                    <input type="hidden" class="delattach" name="delattach" value="0">
                </div>
                <div class="form-group">
                    <label class="btn btn-success btn-round tg-fileuploadlabel" for="comment_attach">
                        <span class="text-color-green" style="line-height:15px;">Add Attachment</span>
                        <input id="comment_attach" class="tg-fileinput" type="file" name="" autocomplete="off" accept=".jpg, .jpeg, .png">
                    </label>
                    &nbsp;&nbsp;
                    <button type="button" class="btn_trans btn_remove_attach_comment">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="modal-footer commentModalFooter">
                <button type="button" id="btn-commentD" class="btn btn-denger" data-cid="">Delete</button>
                <button type="button" class="btn btn-secondary btn-normal" data-dismiss="modal">Close</button>
                <button type="button" id="btn-commentM" class="btn btn-primary">Save</button>

            </div>
        </form>
      </div>
    </div>
  </div>
{{-- ---------------------------------- --}}

<div class="modal fade" id="viewTaskContent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="commentModalTitle">View Task Content</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

            <div class="modal-body">

                <div class="form-group">
                   <p class="text_taskContent">
                   </p>
                   <a href="" download class="attach_taskContent"></a>
                </div>

            </div>
            <div class="modal-footer commentModalFooter">
                <button type="button" class="btn btn-secondary btn-normal" data-dismiss="modal">Close</button>
            </div>

      </div>
    </div>
  </div>

  {{-- ---------------------------------- --}}

<div class="modal fade" id="viewAttach" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="commentModalTitle">View Attachment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

            <div class="modal-body">

                <div class="form-group text-center">
                    <img src="" class="img_attach" style="width:100%;" alt="">
                </div>

            </div>
            <div class="modal-footer commentModalFooter">
                <button type="button" class="btn btn-secondary btn-normal" data-dismiss="modal">Close</button>
            </div>

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

        $(document).on('click','#btn_comment_page',function(){

            var file = document.getElementById('comment_attachPost');
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'doc', 'docx', 'xls', 'xlsx'];

            if($('#comment').val() == '')
            {
                return false;
            }
            else
            {
                var formdata = new FormData;
                formdata.append('comment',$("#comment").val());
                formdata.append('id',$("#project_id_page").val());
                formdata.append('taskid','');
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
                    url:"/ajax/create_comment",
                    type: 'post',
                    dataType: 'json',
                    data: formdata,

                    processData: false,
                    contentType: false,
                    success: function(result){
                        if(result)
                        {
                           location.reload();
                        }
                    }
                });
            }

        });

        $(document).on('click','#btn-comment',function(){
            var file = document.getElementById('comment_attach');
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'doc', 'docx', 'xls', 'xlsx'];

            if($('#comment').val() == '')
            {
                return false;
            }
            else
            {
                var formdata = new FormData;
                formdata.append('comment',$("#commentM").val());
                formdata.append('id',$("#proejct_id").val());
                formdata.append('taskid',$("#taskidM").val());
                formdata.append('commentid',$("#commentidM").val());

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
                    url:"/ajax/create_comment",
                    type: 'post',
                    dataType: 'json',
                    data: formdata,

                    processData: false,
                    contentType: false,
                    success: function(result){
                        if(result)
                        {
                           location.reload();
                        }
                    }
                });
            }
        });
        $(document).on('click','.btn_remove_attach_comment',function(){
            $('.download_comment_attach').css('display','none');
            $('.comment_attach').attr('src','');
            $('.delattach').val('1');
            $('.btn_remove_attach_comment').css('display','none');
        });

        $(document).on('click','#btn-commentMM',function(){
            $('.download_comment_attach').css('display','none');
            $('.download_comment_attach').attr('href','');
            $('.comment_attach').attr('src','');
            $('.btn_remove_attach_comment').css('display','none');
            var id = $(this).data("id");
            var view = $(this).data('view');
            var link = $(this).data('link');
            if(view != "")
            {
                $('.comment_attach').attr('src','/upload/attach/'+link);
                $('.btn_remove_attach_comment').css('display','inline-block');
            }
            else
            {
                if(link != '')
                {
                    $('.download_comment_attach').css('display','block');
                    $('.download_comment_attach').attr('href','/upload/attach/'+link);
                }
            }
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
            var file = document.getElementById('comment_attach');
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'doc', 'docx', 'xls', 'xlsx'];

            if($('#commentM').val() == '')
            {
                return false;
            }
            else
            {
                var formdata = new FormData;
                formdata.append('comment',$("#commentM").val());
                formdata.append('id',$("#proejct_id").val());
                formdata.append('taskid',$("#taskidM").val());
                formdata.append('commentid',$("#commentidM").val());
                formdata.append('delattach',$(".delattach").val());

                formdata.append('_token',$('meta[name=csrf-token]').attr("content"));

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
                    url:"/ajax/create_comment",
                    type: 'post',
                    dataType: 'json',
                    data: formdata,

                    processData: false,
                    contentType: false,
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
        $(document).ready(function(){
            $(document).on('click','.btn_viewTaskContent',function(){
                $('.text_taskContent').html($(this).data('content'));
                var attach = $(this).data('attach');
                if(attach !='')
                {
                    $(".attach_taskContent").attr('href','/upload/attach/'+attach);
                    $(".attach_taskContent").html(attach);
                }
                else
                {
                    $(".attach_taskContent").attr('href','');
                    $(".attach_taskContent").html('');
                }
                console.log(attach);
            });
            $('.btn_viewattach').click(function(){
                $('.img_attach').attr('');
                var attach = $(this).data('view');
                if(attach !='')
                {
                    $('.img_attach').attr('src','/upload/attach/'+attach);
                }
            });
        });


    });
</script>
@endsection
