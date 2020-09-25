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

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="text-center">
                                <label for="" class="text-border" style="border: 1px solid #222222;padding:5px;border-radius:5px;">Description<span style="color: red;">*</span></label>
                            </div>

                            <textarea type="text" name="description" id="description" rows="6" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="text-center">
                                <label for="">Set a Due date(Optional)</label>
                                <br>
                                <i class="fas fa-calendar-week" style="font-size: 80px;color:#20a4b9;" aria-hidden="true"></i>
                                <br>
                            </div>
                            <br>
                            <input type="date" name="date" id="" class="form-control">
                        </div>
                    </div>
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
