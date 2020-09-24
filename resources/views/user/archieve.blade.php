@extends('layouts.user')
@section('style')

@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="{{ asset('js/jquery.easypiechart.min.js') }}" type="text/javascript"></script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        @if (Auth::user()->role > 0)
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
            <label for="">Sort By:</label> &nbsp;&nbsp;
            <select name="" class="form-control" id="" style="width:100px;display:inline-block;">
                <option value="">Oldest</option>
                <option value="">Latest</option>
            </select>
        </div>
        @endif
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">

            <table class="table">
                @foreach ($results as $item)
                    <tr>
                        <td>
                            <input type="checkbox" class="chk_message_item" name="message_id[]" value="{{ $item->id }}">
                        </td>
                        <td>
                            <a href="{{ url('/dashboard/project',$item->id) }}">
                                {{ $item->title }}
                            </a>
                        </td>
                        <td width="100">
                            <form action="{{ route('deleteproject') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <input type="hidden" name="page" value="1">
                                <button type="submit" class="btn btn-denger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        @if (count($results)>0)
            <div class="col-md-12">
                <input type="checkbox" name="" id="select_all">
                <button type="button" class="btn_trans btn_col_red btn_message_all" disabled>Delete All</button>
            </div>
        @endif
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','.btn_message_all',function(){
            var id = 0;
            $.ajax({
                url:"/ajax/delete_project_all",
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
