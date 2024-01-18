<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductUnitCreateFormRequest;
use App\Http\Requests\Product\ProductUnitUpdateFormRequest;
use App\Services\Permission\PermissionService;
use App\Services\Role\RoleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleController extends Controller
{
    private $roleService;

    public function __construct()
    {
        $this->roleService = new RoleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $view = $request->input('view') ?$request->input('view'): null;
            $page = (int)request()->page ?? 1;
            $search = request()->search ? request()->search : null;
            $data = $this->roleService->roleList($view,$page,$search);
            if (isset($data['error'])){
                return ApiResponse($data['error']['message'],$data['error']['status_code']);
            }
            return ApiResponse('success',200,$data);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
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
    public function store(ProductUnitCreateFormRequest $request)
    {
        try {
                $data = $this->roleService->store($request->all());
          if (isset($data['error'])){
              return ApiResponse($data['error']['message'],$data['error']['status_code']);
          }
            return ApiResponse($data['message']['message'],$data['message']['status_code'],$data);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data = $this->roleService->show((int)$id);
            if (isset($data['error'])){
                return ApiResponse($data['error']['message'],$data['error']['status_code']);
            }
            return ApiResponse("success",200,$data);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $data = $this->roleService->getEditData((int)$id);
            if (isset($data['error'])){
                return ApiResponse($data['error']['message'],$data['error']['status_code']);
            }
            return ApiResponse("success",200,$data);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUnitUpdateFormRequest $request, $id)
    {
        try {
            $data = $this->roleService->update($request->all(), (int)$id);
            if (isset($data['error'])){
                return ApiResponse($data['error']['message'],$data['error']['status_code']);
            }
            return ApiResponse($data['message']['message'],$data['message']['status_code'],$data);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $data = $this->roleService->destroy((int)$id);
            if (isset($data['error'])){
                return ApiResponse($data['error']['message'],$data['error']['status_code']);
            }
            return ApiResponse($data['message']['message'],$data['message']['status_code']);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }
    public function checkPermissions(Request $request)
    {
        try {
            $roleId = Auth::user()->role_id;
            $role = $this->roleService->show((int)$roleId);
            if (!$role) {
                return response()->json([
                    'error' => 'Role not found'
                ], 404);
            }
            $permissionIds =  $role->permission_id;
            $permissions = array();
            $permissionService = new PermissionService;
            foreach ($permissionIds as $permissionId) {
                $permission = $permissionService->show($permissionId);
                if ($permission) {
                    $permissions[] = $permission->name;
                }
            }
            $requestedPermissions = $request->input('permissions');
            $response = [];
            foreach ($requestedPermissions as $permissionName) {

                $response[$permissionName] = in_array($permissionName, $permissions);
            }
            return response()->json(['permissions' => $response]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
