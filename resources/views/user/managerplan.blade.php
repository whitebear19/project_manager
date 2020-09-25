@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
           <p class="dash_title">Manager Plan</p>
        </div>
        <div class="col-md-6" style="text-align: right;">
            <button class="btn btn-success" id="btn_addPlan" data-toggle="modal" data-target="#addPlanModal">Add Plan</button>
         </div>
        <div class="col-md-12">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td><b>Name</b></td>
                                    <td><b>Period</b></td>
                                    <td><b>Price</b></td>
                                    <td><b>Action</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $item)
                                    <tr>
                                        <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            {{ $item->period }} &nbsp; Month
                                        </td>
                                        <td>
                                            ${{ $item->price }}
                                        </td>
                                        <td>
                                            <button type="button" rel="tooltip" data-id="{{ $item->id }}" data-period="{{ $item->period }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}" data-toggle="modal" data-target="#addPlanModal" id="btn_editPlan" class="btn btn-success btn-mini btn-editUser">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <form action="{{ route('deleteplan') }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <button type="submit" rel="tooltip" class="btn btn-danger btn-mini">
                                                    <i class="material-icons">close</i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
{{-- ------------------ --}}
<div class="modal fade" id="addPlanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="planTitle">Add Plan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="addPlan-form" method="post">
            @csrf
            <input type="hidden" name="id" id="plan_id">
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Period</label>
                    <select type="text" name="period" id="plan_period" class="form-control">
                        <option value="1">1 Month</option>
                        <option value="2">2 Month</option>
                        <option value="3">3 Month</option>
                        <option value="4">4 Month</option>
                        <option value="5">5 Month</option>
                        <option value="6">6 Month</option>
                        <option value="7">7 Month</option>
                        <option value="8">8 Month</option>
                        <option value="9">9 Month</option>
                        <option value="10">10 Month</option>
                        <option value="11">11 Month</option>
                        <option value="12">12 Month</option>
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label for="">Price($)</label>
                    <input type="text" name="price" id="plan_price" class="form-control">
                </div>
                <br>
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" id="plan_name" class="form-control">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btn-addPlan" class="btn btn-primary">Add</button>
            </div>
        </form>
      </div>
    </div>
  </div>


<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','#btn-addPlan',function(){

        var data = $("#addPlan-form").serialize();
        if($('#plan_price').val() == '')
        {
            return false;
        }
        else if($('#plan_name').val() == '')
        {
            return false;
        }
        else
        {
            $.ajax({
                url:"/ajax/create_plan",
                type: 'post',
                dataType: 'json',
                data: data,

                success: function(result){
                    if(result == '1')
                    {
                        $('#addUserModal').modal('hide');
                        swal({ title:"Successfuly Stored!", text: "", type: "success", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                        location.reload();
                    }
                    else
                    {
                        $('#addUserModal').modal('hide');
                        swal({ title:"Something wrong!", text: "Please try again.", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-success"});
                    }
                }
            });
        }
        });

        $(document).on('click','#btn_editPlan',function(){
            $('#planTitle').html("Edit Plan");
            $('#plan_id').val($(this).data('id'));
            $('#plan_price').val($(this).data('price'));
            $('#plan_name').val($(this).data('name'));
            var period = $(this).data('period');
            $("#plan_period").val(period).prop('selected', true);
            $('#btn-addPlan').html('Store');
        });

        $(document).on('click','#btn_addPlan',function(){
            $('#planTitle').html("Add Plan");
            $('#plan_id').val('0');
            $('#plan_price').val('');
            $('#plan_name').val('');
            $('#btn-addPlan').html('Add');
            $("#plan_period").val('');
        });

    });
</script>
@endsection
