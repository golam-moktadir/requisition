@extends('admin.layouts.datatableWithForm')

@section('breadcrumbs')

<li class="breadcrumb-item">
    {{ $title }}
</li>
@endsection

@section('content-body')
<div class="col-lg-12 mb-2 order-0 mb-3">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="card-body">
            <h5 class="card-title text-primary">
                {{ $title_sub }}
            </h5>
            @if(session('success'))     
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>                                       
                </div>
            </div>            
            @endif

            <div class="row">
                <div class="offset-md-2 col-md-6">
                    <form action="{{ route('admin.store.update.password') }}" method="POST" autocomplete="off">
                        @csrf <input type="hidden" value="{{ $user_id }}" name="logged_in_user_id" />
                        <input autocomplete="false" name="hidden" type="text" style="display:none;">
                        
                        <div class="form-group mb-3">
                            <label for="current_password">Current Password</label>
                            <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required autocomplete="new-password"/>
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="new_password">New Password</label>
                            <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required />
                            @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input id="new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" required />
                        </div>

                        <button type="submit" class="btn btn-primary">Change Password</button>

                        </div>
                    </form>
                </div>
            </div>
                     
        </div>
      </div>
    </div>
  </div>


@endsection

@section('footerjs')
<script>
    function roundToDecimal(num, decimalPlaces) {
        var factor = Math.pow(10, decimalPlaces);
        return Math.round(num * factor) / factor;
    }   
    function getHeight(){
        var value = parseInt($("#hieght").val());
        if(isNaN(value)){
            value = 0;
        }        
        return value;
    }   
    function getWidth(){
        var value = parseInt($("#width").val());
        if(isNaN(value)){
            value = 0;
        }        
        return value;
    }   
    function getPrice(){
        var value = parseInt($("#price").val());
        if(isNaN(value)){
            value = 0;
        }        
        return value;
    }   
    function getSheets(){
        var value = parseInt($("#number_of_sheets").val());
        if(isNaN(value)){
            value = 0;
        }        
        return value;
    } 
    function calculatSquareFeet(height, width){
        var amount = roundToDecimal((height/12)*(width/12), 2);
        $("#totalSquareFeet").text(amount);
        var value2 = parseInt($("#price").val());
        if(isNaN(value2)){
            value2 = 0;
        }         
        $("#totalPrice").text(roundToDecimal(amount*value2*getSheets(), 2));
    }
    $(document).ready(function() {   
        $("#hieght").keyup(function(event) {
            calculatSquareFeet(getHeight(), getWidth());
        });
        $("#width").keyup(function(event) {
            calculatSquareFeet(getHeight(), getWidth());
        });  
        $("#price").keyup(function(event) {
            calculatSquareFeet(getHeight(), getWidth());          
        });   
        $("#number_of_sheets").keyup(function(event) {
            calculatSquareFeet(getHeight(), getWidth());          
        });    
    });    
  </script>
@endsection