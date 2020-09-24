@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <p class="dash_title">New Project</p>
        </div>
        <div class="col-md-12">
            <form action="{{ route('storeproject') }}" id="newproject-form" method="POST">
                @csrf
                <div class="form-group">
                    <label for="">Title<span style="color: red;">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="">Description<span style="color: red;">*</span></label>
                    <textarea type="text" name="description" id="description" class="form-control" required></textarea>
                </div>
                <br>
                <div class="form-group">
                    <label for="">Set a Due date(Optional)</label>
                    <input type="date" name="date" id="" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Select Color</label>&nbsp;&nbsp;
                    <input type="color" name="color">
                </div>

                <button type="submit" class="btn btn-primary pull-right">Save</button>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">

</script>
@endsection
