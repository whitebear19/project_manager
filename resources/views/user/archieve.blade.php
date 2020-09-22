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
                <option value="">Name</option>
                <option value="">Latest</option>
            </select>
        </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <ul class="project_list">
                @foreach ($results as $item)
                    <li>
                        <a href="{{ url('/dashboard/project',$item->id) }}">
                            <div class="project_item">
                                <div>
                                    <i class="fas fa-folder"></i>
                                    <span class="project_title">
                                        {{ $item->title }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">

</script>
@endsection
