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

            <form action="" class="form-inline">
                <div class="row mb-2">
                    <div class="col-md-6 mb-3">
                       <label for="hieght" class="form-label">Height/Length (Inch)</label>
                       <input id="hieght" placeholder="Height (Inch)" value="" name="hieght" tabindex="1" type="text" class="form-control @error('hieght') is-invalid @enderror" autofocus autocomplete="hieght" />
                       @error('hieght')
                       <div class="form-text text-danger">{{ $message }}</div>
                       @enderror                        
                    </div>

                    <div class="col-md-6 mb-3">
                       <label for="width" class="form-label">Width (Inch)</label>
                       <input id="width" placeholder="Width (Inch)" value="" name="width" tabindex="2" type="text" class="form-control @error('width') is-invalid @enderror" autofocus autocomplete="width" />
                       @error('width')
                       <div class="form-text text-danger">{{ $message }}</div>
                       @enderror                        
                    </div>

                    <div class="col-md-6 mb-3">
                       <label for="price" class="form-label">Price</label>
                       <input id="price" placeholder="Price by square feet" value="0" name="price" tabindex="3" type="text" class="form-control @error('price') is-invalid @enderror" autofocus autocomplete="price" />
                       @error('price')
                       <div class="form-text text-danger">{{ $message }}</div>
                       @enderror                        
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="number_of_sheets" class="form-label">Number of sheets</label>
                        <input id="number_of_sheets" placeholder="Number of sheets" value="1" name="number_of_sheets" tabindex="4" type="text" class="form-control @error('number_of_sheets') is-invalid @enderror" autofocus autocomplete="number_of_sheets" />
                        @error('number_of_sheets')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror                        
                    </div>

                </div>
            </form>
            
            <h3>
                Result# <span id="totalSquareFeet">0</span> Square feet <br/> <br/>
                Total price# <span id="totalPrice">0</span> TK
            </h3>            
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