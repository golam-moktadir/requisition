<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Interface\UserRepositoryInterface;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Doctrine\ORM\Entity\AccountHeads;
use App\Doctrine\ORM\Repository\AccountHeadsRepository;

class UserController extends Controller
{
    protected EntityManager $entityManagerRegistry;
    function __construct(
            protected UserRepositoryInterface $userRepository,
            // protected EntityManager $entityManager,
            protected EntityManagerInterface $entityManager,
            // ManagerRegistry $managerRegistry,
            protected Connection $dbal
        ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        // $this->entityManagerRegistry = $managerRegistry->getManager('second');
        $this->dbal = $dbal;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        // $repository = $this->entityManager->getRepository(AccountHeads::class);
        // foreach ($repository->findAll() as $key => $head) {
        //     $head->getAccountHead();
        // }
        // $entity = $this->entityManager->find('App\Doctrine\ORM\Entity\AccountHeads', 1);
        // dd($entity, $repository->findOne(1), $repository->findAll());

        $sql        = "SELECT * FROM account_heads WHERE id = :id";
        $stmt       = $this->dbal->executeQuery($sql, ['id' => 1]);
        $results    = $stmt->fetchAllAssociative();
        foreach ($results as $row) {
            // echo $row['id'] . ' - ' . $row['account_head'] . PHP_EOL;
        }
        // Fetch single result
        // $row = $stmt->fetchOne();        
        // echo '<pre>';print_r($row);echo '</pre>';die;


        return view('admin/user/index', [
            'users'     => $this->userRepository->all(),
            'title'     => 'Users',
            'title_sub' => 'Create New User',
            'roles'     => DB::table('roles')->pluck('role_name', 'role_id'),
            'employees' => DB::table('employees')->orderBy('first_name', 'ASC')->pluck('first_name', 'emp_id'),
            'isEdit'    => false,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // $request->validate([
        //     'name' => 'required|max:250',
        //     'email1' => 'required|email:rfc,dns|max:99|unique:App\Models\User,email',
        //     'password' => 'required|min:8|max:16|confirmed',
        //     'password_confirmation' => 'required|min:8|max:16',
        //     'roles' => 'required',
        //     'emp_id' => 'required',
        // ],[
        //     'email1.required' => 'The email field is required.',
        //     'email1.max' => 'The email field max length 99.',
        //     'email1.unique' => 'The email field value is already exists.'
        // ]);
        
        // $validatedData = $request->validated();

        $request->merge([
            'email' => $request->get('email1'),
            'roles' => json_encode([$request->get('roles')]),
            'password' => Hash::make($request->get('password')),
        ]);
                
        User::create($request->all());
     
        return redirect()
            ->route('admin.user.index')
            ->with('success','User created successfully.'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.user.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if($user->id == Auth::id()){
            abort(404, "You don't have permission to edit this user.");
        }

        $user->roles = json_decode($user->roles);

        return view('admin/user/index', [
            'users'     => $this->userRepository->all(),
            'title'     => 'Users',
            'title_sub' => 'Update User',
            'roles'     => DB::table('roles')->pluck('role_name', 'role_id'),
            'employees' => DB::table('employees')->orderBy('first_name', 'ASC')->pluck('first_name', 'emp_id'),
            'isEdit'    => true,
            'user'      => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {  
        if($user->id == Auth::id()){
            abort(404, "You don't have permission to edit this user.");
        }

        // $validation = [
        //     'name' => 'required|max:250',
        //     'email1' => 'required|max:99|unique:App\Models\User,email,' . $user->id,
        //     'roles' => 'required',
        //     'status' => 'required',            
        // ];
        // if($request->get('password')!=''){
        //     $validation['password'] = 'required|min:8|max:16|confirmed';
        //     $validation['password_confirmation'] = 'required|min:8|max:16';
        // }        
        // $request->validate($validation,[
        //     'email1.required' => 'The email field is required.',
        //     'email1.max' => 'The email field max length 99.',
        //     'email1.unique' => 'The email field value is already exists.'
        // ]);
            
        if($request->get('password')!=''){
            $request->merge([
                'email' => $request->get('email1'),
                'roles' => json_encode([$request->get('roles')]),
                'password' => Hash::make($request->get('password')),
            ]);

            $user->update($request->all());
        }        
        else {

            $user->name = $request->get('name');
            $user->email = $request->get('email1');
            $user->roles = json_encode([$request->get('roles')]);
            $user->status = $request->get('status');

            $user->save();          
        }   
             
        return redirect()
            ->route('admin.user.edit', $user->id)
            ->with('success','User update successfully.'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('admin.user.index');
    }
}
