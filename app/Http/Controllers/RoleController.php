<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Requests\role\CreateRoleRequest;
use App\Http\Requests\role\UpdateRoleRequest;
use App\Http\Resources\role\RoleResource;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage_role');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Role::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoleRequest $request)
    {
        $role = Role::create([
            'name' => request('name'),
            'label' => ucwords(request('name'))
        ]);

        if(request('abilities'))
        {
            $role->abilities()->attach(request('abilities'));
        }

        return $role;
    }


    public function show(Role $role)
    {
        return $role;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update([
            'name' => request('name'),
            'label' => ucwords(request('name'))
        ]);
        $existingIds = $role->abilities()->pluck('id')->unique()->toArray();

        if(array_diff(request('abilities'),$existingIds) === request('abilities') )
        {
            $remove = array_merge(request('abilities'),$existingIds);
        }
        else
        {

            if(sizeof(request('abilities')) > sizeof($existingIds))
            {

                $remove = array_diff(request('abilities'),$existingIds);

            }
            else
            {
                $remove = array_diff($existingIds,request('abilities'));
            }

        }
        $role->abilities()->toggle($remove);

        return response(null,Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete_role');
        return $role->delete();
    }
}
