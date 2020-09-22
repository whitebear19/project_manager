@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
           <p class="dash_title">Payment Method</p>
        </div>
        <div class="col-md-12">
            <div class="card card-plain">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">PayPal</h4>
                </div>

            </div>
            <div class="card-body">
                <form action="" id="paypal-form" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">PAYPAL_SANDBOX_API_USERNAME</label>
                    <input type="text" name="paypal_username" id="paypal_username" class="form-control" value="{{ env('PAYPAL_SANDBOX_API_USERNAME') }}">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="">PAYPAL_SANDBOX_API_PASSWORD</label>
                        <input type="text" name="paypal_password" id="paypal_password" class="form-control" value="{{ env('PAYPAL_SANDBOX_API_PASSWORD') }}">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="">PAYPAL_SANDBOX_API_SECRET</label>
                        <input type="text" name="paypal_secret" id="paypal_secret" class="form-control" value="{{ env('PAYPAL_SANDBOX_API_SECRET') }}">
                    </div>
                    <button type="button" id="paypal_save" class="btn btn-primary pull-right">Save</button>
                </form>

            </div>
        </div>

        <div class="col-md-12 mt-50">
            <div class="card card-plain">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Square</h4>
                </div>

            </div>
            <div class="card-body">
                <form action="" method="POST">
                    @csrf

                    <button type="button" class="btn btn-primary pull-right">Save</button>
                </form>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','#paypal_save',function(){
            var data = $("#paypal-form").serialize();
            $.ajax({
                url:"/ajax/update_paypal",
                type: 'post',
                dataType: 'json',
                data: data,

                success: function(result){
                    console.log(result);
                    if(result)
                    {
                        swal({ title:"Successful updated!", text: "", type: "success", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                    }
                    else
                    {
                        swal({ title:"Something wrong!", text: "Please try again.", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                    }
                }
            });

        });
    });
</script>
@endsection
