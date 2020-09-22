@extends('layouts.user')
@section('style')
	{{-- <link href="{{ asset('css/circlechart.css') }}" rel="stylesheet"> --}}
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
        <div class="col-md-4">
            <a href="{{ route('newproject') }}" class="btn btn-primary">Create New Project</a>
        </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <ul class="project_list">
                @foreach ($results as $item)
                    <li>
                        <a href="{{ url('/dashboard/project',$item['id']) }}">
                            <div class="project_item">
                                <div>
                                    <i class="fas fa-folder"></i>
                                    <span class="project_title">
                                        {{ $item['title'] }}
                                    </span>
                                </div>
                                <div class="project_progress">
                                    <span class="chart" data-percent="{{ $item['pro'] }}">
                                        <span class="percent @if ($item['pro'] == '100')
                                            adjust
                                        @endif"></span>
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
    $(function() {
		$('.chart').easyPieChart({
			easing: 'easeOutBounce',
			onStep: function(from, to, percent) {
				$(this.el).find('.percent').text(Math.round(percent));
			}
		});
	});
</script>
@endsection
