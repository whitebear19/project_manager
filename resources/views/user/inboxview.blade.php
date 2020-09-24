@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
           <p class="dash_title">Inbox</p>

        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn_trans btn_col_red btn_message_item" data-id="{{ $message->id }}"><i class="fas fa-trash-alt"></i></button>
        </div>
        <div class="col-md-12">
            <div class="for-group">
                <label for="">Title:</label> <span>{{ $message->name }}</span>
            </div>
            <div class="for-group">
                <label for="">Email:</label> <span>{{ $message->email }}</span>
            </div>
            <div class="for-group">
                <label for="">Content:</label>
                <p>{{ $message->content }}</p>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','.btn_message_item',function(){
            var id = $(this).data('id');
            $.ajax({
                url:"/ajax/delete_message",
                type: 'get',
                dataType: 'json',
                data: {id:id},

                success: function(result){
                   if(result)
                   {
                       location.reload();
                   }
                }
            });
        });
    });
</script>
@endsection
