<form class="form-inline" id="customerCreateForm" name="customerCreateForm" action="{{ route('admin.customer.update', $customer->cust_id) }}" method="POST">   
    @csrf 
    @method('PUT') 
      
    <div class="row mb-2">
       <div class="col-md-4 mb-3">
          <label for="first_name" class="form-label">Customer Name</label>
          <input value="{{ old('first_name', $customer->first_name) }}" name="first_name" tabindex="1" type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" placeholder="Customer Name" autofocus autocomplete="first_name" required maxlength="80"/>
          @error('first_name')
          <div class="form-text text-danger">{{ $message }}</div>
          @enderror                        
       </div>

       <div class="col-md-4 mb-3">
          <label for="org_name" class="form-label">Organization name</label>
          <input value="{{ old('org_name', $customer->org_name) }}" name="org_name" tabindex="2" type="text" class="form-control @error('org_name') is-invalid @enderror" id="org_name" placeholder="Organization name"  autocomplete="org_name" required maxlength="255" />
          @error('org_name')
          <div class="form-text text-danger">{{ $message }}</div>
          @enderror                        
       </div>

       <div class="col-md-4 mb-3">
          <label for="cust_mobile_no" class="form-label">Mobile No</label>
          <input value="{{ old('cust_mobile_no', $customer->cust_mobile_no) }}" name="cust_mobile_no" tabindex="4" type="text" class="form-control @error('cust_mobile_no') is-invalid @enderror" id="cust_mobile_no" placeholder="Mobile No"  autocomplete="cust_mobile_no" maxlength="20" required/>
          @error('cust_mobile_no')
          <div class="form-text text-danger">{{ $message }}</div>
          @enderror                        
       </div>

       <div class="col-md-4 mb-3">
          <label for="cust_email_address" class="form-label">Email Address</label>
          <input value="{{ old('cust_email_address', $customer->cust_email_address) }}" name="cust_email_address" tabindex="5" type="email" class="form-control @error('cust_email_address') is-invalid @enderror" id="cust_email_address" placeholder="Email Address" autofocus autocomplete="cust_email_address" required maxlength="100"/>
          @error('cust_email_address')
          <div class="form-text text-danger">{{ $message }}</div>
          @enderror                        
       </div>

         <div class="col-md-8 mb-3">
            <label for="org_address" class="form-label">Address</label>
            <input value="{{ old('org_address', $customer->org_address) }}" name="org_address" tabindex="3" type="text" class="form-control @error('org_address') is-invalid @enderror" id="org_address" placeholder="Address"  autocomplete="org_address" required maxlength="250" />
            @error('org_address')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror                        
         </div>

      <div class="clearboth"></div>
       <div class="col-md-6 mb-3">
          <button class="btn btn-primary" type="submit" tabindex="20" name="employee-submit">Submit</button>
       </div>
    </div>
 </form>