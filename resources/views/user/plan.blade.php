@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
           <p class="dash_title">Plan</p>
        </div>
        <div class="col-md-12">
            @if (Session::has('message'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ Session::get("message") }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
        <div class="col-md-12">
            <div class="card card-plain">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Select Payment Method</h4>
                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-check-radio">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="selectPayment" id="selectPayment1" value="paypal" >
                                Paypal
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        <br>
                        <div class="form-check form-check-radio">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="selectPayment" id="selectPayment2" value="square">
                                Square
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selectplan">Select Plan</label>
                            <select class="form-control selectpicker" data-style="btn btn-link" id="selectplan">
                                <option value="">----Select Plan----</option>
                                @foreach ($plans as $item)
                                    <option value="{{ $item->price }}">{{  $item->period }} &nbsp; Month ( ${{ $item->price }} )</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="area_paypal" class="text-center mt-30">
                            <form action="{{ route('make.payment') }}" method="post">
                                @csrf
                                <input type="hidden" required id="price" name="price" value="">
                                <button type="submit" id="btn_pay" class="btn btn-info" disabled>Pay</button>
                            </form>
                        </div>
                        <div id="area_square" class="text-center mt-30">
                            square
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $('input[type=radio][name=selectPayment]').change(function() {
            if (this.value == 'paypal') {
                $("#area_paypal").css("display",'block');
                $("#area_square").css("display",'none');
            }
            else if (this.value == 'square') {
                $("#area_paypal").css("display",'none');
                $("#area_square").css("display",'block');
            }
        });

        $('#selectplan').on('change',function(){
            var price = this.value;
            if(price == "")
            {
                $("#btn_pay").prop('disabled', true);
                $("#btn_pay").html('Select Plan.');
                $("#price").val(price);
                swal({ title:"Something wrong!", text: "You have to select one.", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                return false;
            }
            else
            {
                $("#price").val(price);
                $("#btn_pay").prop('disabled', false);
                $("#btn_pay").html('Pay with $'+price);
            }
        });
    });
</script>
@endsection
