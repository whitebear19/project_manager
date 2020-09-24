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
                            <a href="{{ url('/dashboard/project',$item->id) }}">
                                {{ $item->title }}
                            </a>
                        </td>
                        <td width="120">
                            {{ $item->date }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
@endsection
