@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
           <p class="dash_title">Inbox</p>
        </div>
        <div class="col-md-12">
            <form action="" id="inbox_form" method="post">
                @csrf
                <table class="table">
                    <tbody>
                        @foreach ($contacts as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" class="chk_message_item" name="message_id[]" value="{{ $item->id }}">
                                </td>
                                <td>
                                    <a href="{{ url('/dashboard/inbox',$item->id) }}">{{ $item->name }}</a>
                                </td>
                                <td>
                                    <button type="button" class="btn_trans btn_col_red btn_message_item" data-id="{{ $item->id }}"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
        @if (count($contacts)>0)
            <div class="col-md-12">
                <input type="checkbox" name="" id="select_all">
                <button type="button" class="btn_trans btn_col_red btn_message_all" disabled>Delete All</button>
            </div>
        @endif

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
        $(document).on('click','.btn_message_all',function(){
            var id = 0;
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
        $("#select_all").change(function() {
            if(this.checked) {
                $(".btn_message_all").prop('disabled', false);
                $(".chk_message_item").each(function () {
                    $(this).prop('checked',true);
                });
            }
            else
            {
                $(".btn_message_all").prop('disabled', true);
                $(".chk_message_item").each(function () {
                    $(this).prop('checked',false);
                });

            }
        });
    });
</script>
@endsection
