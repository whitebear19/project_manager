@extends('layouts.user')

@section('content')
<script>
    $(document).ready(function(){
        $(".datepicker").datepicker();
        $(".datepicker").change(function(){

            $(".datepicker_caption").html($(this).val());
        });
    });

</script>
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

                            <textarea type="text" name="description" id="description" rows="6" class="form-control input-border"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="text-center">
                                <label for="">Set a Due date(Optional)</label>
                                <br>
                                <div class="pos-rel" style="width:70px;height:80px;margin:auto;">
                                    <i class="fas fa-calendar-week" id="btn_calander_show" style="font-size: 80px;color:#20a4b9;" aria-hidden="true"></i>
                                    <input type="text" name="date" class="datepicker date_customize">
                                </div>
                                <br>
                                <span class="datepicker_caption">

                                </span>
                            </div>


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
    $(document).ready(function(){
        $('#btn_calander_show').click(function(){
            if($('#project_calander').hasClass('disp-none'))
            {
                $('#project_calander').removeClass('disp-none');
            }
            else
            {
                $('#project_calander').addClass('disp-none');
            }
        });
    });
</script>
@endsection
