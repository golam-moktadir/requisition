@if(session('success'))     
<div class="row mb-2">
    <div class="col-md-12">
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>                                       
    </div>
</div>            
@endif
@if(session('warning'))     
<div class="row mb-2">
    <div class="col-md-12">
        <div class="alert alert-danger" role="alert">{{ session('warning') }}</div>                                       
    </div>
</div>            
@endif
@if(session('error'))     
<div class="row mb-2">
    <div class="col-md-12">
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>                                       
    </div>
</div>            
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif