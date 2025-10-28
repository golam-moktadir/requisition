<form class="form-inline" id="employeeCreateForm" name="employeeCreateForm" action="{{ route('admin.employee.update', $employee->emp_id) }}" method="POST">   
    @csrf 
    @method('PUT') 
      
      <div class="row mb-2">
         <div class="col-md-4 mb-3">
            <label for="first_name" class="form-label">first name</label>
            <input value="{{ old('first_name', $employee->first_name) }}" name="first_name" tabindex="1" type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" placeholder="Your First Name" autofocus autocomplete="first_name" />
            @error('first_name')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror                        
         </div>
  
         <div class="col-md-4 mb-3">
            <label for="last_name" class="form-label">last name</label>
            <input value="{{ old('last_name',$employee->last_name) }}" name="last_name" tabindex="2" type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" placeholder="Your Last Name" autofocus autocomplete="last_name" />
            @error('last_name')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror                        
         </div>
  
         <div class="col-md-4 mb-3">
            <label for="father_name" class="form-label">father name</label>
            <input value="{{ old('father_name', $employee->father_name) }}" name="father_name" tabindex="3" type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" placeholder="Your Father Name" autofocus autocomplete="father_name" />
            @error('father_name')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror                        
         </div>
  
         <div class="col-md-4 mb-3">
            <label for="mother_name" class="form-label">mother name</label>
            <input value="{{ old('mother_name', $employee->mother_name) }}" name="mother_name" tabindex="4" type="text" class="form-control @error('mother_name') is-invalid @enderror" id="mother_name" placeholder="Your Mother Name" autofocus autocomplete="mother_name" />
            @error('mother_name')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror                        
         </div>
  
         <div class="col-md-4 mb-3">
            <label for="date_of_birth" class="form-label">Date of birth</label>
            <input value="{{ old('date_of_birth', $employee->date_of_birth) }}" name="date_of_birth" tabindex="5" type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" placeholder="Date of birth" autofocus autocomplete="date_of_birth" />
            @error('date_of_birth')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror                        
         </div>
  
         <div class="col-md-4 mb-3">
            <label for="emp_birth_reg_no" class="form-label">Birh Reg. Number</label>
            <input value="{{ old('emp_birth_reg_no', $employee->emp_birth_reg_no) }}" name="emp_birth_reg_no" tabindex="6" type="number" class="form-control @error('emp_birth_reg_no') is-invalid @enderror" id="emp_birth_reg_no" placeholder="Birh Reg. Number" autofocus autocomplete="emp_birth_reg_no" maxlength="20" />
            @error('emp_birth_reg_no')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror                        
         </div>
  
         <div class="col-md-4 mb-3">
            <label for="emp_nid" class="form-label">NID</label>
            <input value="{{ old('emp_nid', $employee->emp_nid) }}" name="emp_nid" tabindex="7" type="number" class="form-control @error('emp_nid') is-invalid @enderror" id="emp_nid" placeholder="NID" autofocus autocomplete="emp_nid" maxlength="12" />
            @error('emp_nid')
            <div class="form-text text-danger">{{ $message }}</div>
            @enderror                        
         </div>
         
         <div class="col-md-4 mb-3">
           <label for="present_address" class="form-label">Present Address</label>
           <input value="{{ old('present_address', $employee->present_address) }}" name="present_address" tabindex="8" type="text" class="form-control @error('present_address') is-invalid @enderror" id="present_address" placeholder="Present Address" autofocus autocomplete="present_address" />
           @error('present_address')
           <div class="form-text text-danger">{{ $message }}</div>
           @enderror                        
        </div>
                 
        <div class="col-md-4 mb-3">
           <label for="permanaunt_address" class="form-label">Permanent Address</label>
           <input value="{{ old('permanaunt_address', $employee->permanaunt_address) }}" name="permanaunt_address" tabindex="9" type="text" class="form-control @error('permanaunt_address') is-invalid @enderror" id="permanaunt_address" placeholder="Permanent Address" autofocus autocomplete="permanaunt_address" />
           @error('permanaunt_address')
           <div class="form-text text-danger">{{ $message }}</div>
           @enderror                        
        </div>
                 
        <div class="col-md-4 mb-3">
           <label for="emp_mobile_no" class="form-label">Mobile No.</label>
           <input value="{{ old('emp_mobile_no', $employee->emp_mobile_no) }}" name="emp_mobile_no" tabindex="10" type="text" class="form-control @error('emp_mobile_no') is-invalid @enderror" id="emp_mobile_no" placeholder="Permanent Address" autofocus autocomplete="emp_mobile_no" />
           @error('emp_mobile_no')
           <div class="form-text text-danger">{{ $message }}</div>
           @enderror                        
        </div>
                 
        <div class="col-md-4 mb-3">
           <label for="last_edu_certificate" class="form-label">Last Edu. Certificate Name</label>
           <input value="{{ old('last_edu_certificate', $employee->last_edu_certificate) }}" name="last_edu_certificate" tabindex="11" type="text" class="form-control @error('last_edu_certificate') is-invalid @enderror" id="last_edu_certificate" placeholder="Last Edu. Certificate Name" autofocus autocomplete="last_edu_certificate" />
           @error('last_edu_certificate')
           <div class="form-text text-danger">{{ $message }}</div>
           @enderror                        
        </div>
                 
        <div class="col-md-4 mb-3">
           <label for="emp_experiance_details" class="form-label">Experience</label>
           <input value="{{ old('emp_experiance_details', $employee->emp_experiance_details) }}" name="emp_experiance_details" tabindex="13" type="text" class="form-control @error('last_edu_certificate') is-invalid @enderror" id="emp_experiance_details" placeholder="Experience" autofocus autocomplete="emp_experiance_details" />
           @error('emp_experiance_details')
           <div class="form-text text-danger">{{ $message }}</div>
           @enderror                        
        </div>
                 
        <div class="col-md-4 mb-3">
           <label for="emp_contact_person_name" class="form-label">Contact person name</label>
           <input value="{{ old('emp_contact_person_name', $employee->emp_contact_person_name) }}" name="emp_contact_person_name" tabindex="14" type="text" class="form-control @error('last_edu_certificate') is-invalid @enderror" id="emp_contact_person_name" placeholder="Contact person name" autofocus autocomplete="emp_contact_person_name" />
           @error('emp_contact_person_name')
           <div class="form-text text-danger">{{ $message }}</div>
           @enderror                        
        </div>    
  
        <div class="col-md-4 mb-3">
           <label for="emp_contact_person_mobile" class="form-label">Contact person mobile</label>
           <input value="{{ old('emp_contact_person_mobile', $employee->emp_contact_person_mobile) }}" name="emp_contact_person_mobile" tabindex="15" type="text" class="form-control @error('last_edu_certificate') is-invalid @enderror" id="emp_contact_person_mobile" placeholder="Contact person mobile" autofocus autocomplete="emp_contact_person_mobile" />
           @error('emp_contact_person_mobile')
           <div class="form-text text-danger">{{ $message }}</div>
           @enderror                        
        </div>
  
        <div class="col-md-4 mb-3">
           <label for="joining_date" class="form-label">Joining date</label>
           <input value="{{ old('joining_date', $employee->joining_date) }}" name="joining_date" tabindex="16" type="date" class="form-control @error('last_edu_certificate') is-invalid @enderror" id="joining_date" placeholder="Joining date" autofocus autocomplete="joining_date" />
           @error('joining_date')
           <div class="form-text text-danger">{{ $message }}</div>
           @enderror                        
        </div>
  
        <div class="col-md-4 mb-3">
           <label for="emp_remark" class="form-label">Special Note/Remark</label>
           <input value="{{ old('emp_remark', $employee->emp_remark) }}" name="emp_remark" tabindex="16" type="text" class="form-control @error('last_edu_certificate') is-invalid @enderror" id="emp_remark" placeholder="Special Note" autofocus autocomplete="emp_remark" />
           @error('emp_remark')
           <div class="form-text text-danger">{{ $message }}</div>
           @enderror                        
        </div>
         
        <div class="col-md-4 mb-3">
           <label for="emp_status" class="form-label">Status</label>
           <select name="emp_status" tabindex="6" class="form-select @error('status') is-invalid @enderror" id="emp_status" aria-label="emp_status">           
              <option value="1"{{"1" == old('emp_status', $employee->emp_status)  ? ' selected' : ''}}>Active</option>           
              <option value="0"{{"0" == old('emp_status', $employee->emp_status)  ? ' selected' : ''}}>InActive</option>
           </select>
           @error('emp_status')
           <div class="form-text text-danger">{{ $message }}</div>
           @enderror                        
        </div>
        <div class="clearboth"></div>
         <div class="col-md-6 mb-3">
            <button class="btn btn-primary" type="submit" tabindex="20" name="employee-submit">Submit</button>
         </div>
      </div>
   </form>