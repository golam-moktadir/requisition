<form class="form-inline" id="userForm" name="userForm" action="{{ route('admin.user.store') }}" method="POST">
    @csrf
    <div class="row mb-2">
       <div class="col-md-6 mb-3">
          <label for="name" class="form-label">Name</label>
          <input value="{{ old('name') }}" name="name" tabindex="1" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Your name" autofocus autocomplete="name" />
          @error('name')
          <div class="form-text text-danger">{{ $message }}</div>
          @enderror                        
       </div>
       <div class="col-md-6 mb-3">
          <label for="email1" class="form-label">Email address</label>
          <input id="email1" value="{{ old('email1') }}" name="email1" tabindex="2" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="email@domain.com">
          @error('email1')
          <div class="form-text text-danger">{{ $message }}</div>
          @enderror                        
       </div>
       <div class="col-md-6 mb-3">
          <label for="password" class="form-label">Password</label>
          <input name="password" tabindex="3" class="form-control @error('name') is-invalid @enderror" type="password" id="password" placeholder="********" autocomplete="off" />
          @error('password')
          <div class="form-text text-danger">{{ $message }}</div>
          @enderror                        
       </div>
       <div class="col-md-6 mb-3">
          <label for="password_confirmation" class="form-label">Confirm password </label>
          <input name="password_confirmation" tabindex="4" class="form-control @error('password_confirmation') is-invalid @enderror" type="password" id="password_confirmation" placeholder="********" >
          @error('password_confirmation')
          <div class="form-text text-danger">{{ $message }}</div>
          @enderror                        
       </div>
       <div class="col-md-6 mb-3">
          <label for="roles" class="form-label">Roles</label>
          <select name="roles" tabindex="5" class="form-select @error('name') is-invalid @enderror" id="roles" aria-label="Role">
             <option value="">Select Role</option>
             @foreach ($roles as $role)
             <option value="{{ $role }}"{{$role == old('roles')  ? ' selected' : ''}}>
             {{ $role }}
             </option>
             @endforeach  
          </select>
          @error('roles')
          <div class="form-text text-danger">{{ $message }}</div>
          @enderror                        
       </div>
       <div class="col-md-6 mb-3">
          <label for="emp_id" class="form-label">Employee</label>
          <select name="emp_id" tabindex="6" class="form-select @error('emp_id') is-invalid @enderror" id="emp_id" aria-label="Emp-ID">
             <option value="">Select Employee</option>
             @foreach ($employees as $empid => $employee)
             <option value="{{ $empid }}"{{$empid == old('emp_id')  ? ' selected' : ''}}>{{ $employee }}</option>
             @endforeach                          
          </select>
          @error('emp_id')
          <div class="form-text text-danger">{{ $message }}</div>
          @enderror                        
       </div>
       <div class="col-md-6 mb-3">
          <button class="btn btn-primary" type="submit" tabindex="10" name="user-submit">Submit</button>
       </div>
    </div>
 </form>