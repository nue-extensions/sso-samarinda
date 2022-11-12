<?php

namespace Nue\SSOSamarinda\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SSO Users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->models = config('nue.database.users_model');
        $this->data = new $this->models;
        
        $this->prefix = 'sso-users';
        $this->view = 'nue-sso-samarinda::users';

        $this->tCreate = __('Created', ['title' => $this->title]);
        $this->tUpdate = __('Updated', ['title' => $this->title]);
        $this->tDelete = __('Deleted', ['title' => $this->title]);

        view()->share([
            'title' => $this->title, 
            'view' => $this->view, 
            'prefix' => $this->prefix
        ]);
    }

    /**
     * Index interface.
     *
     * @param Request $request
     *
     * @return Content
     */
    public function index(Request $request)
    {
        $data = $this->data->query();

        if($request->has('datatable')):
            return $this->datatable($data);
        endif;

        return nue_view("{$this->view}.index", [
            'title' => $this->title, 
            'data' => $data
        ]);
    }

    /**
     * Create interface.
     *
     * @param Request $request
     *
     * @return Content
     */
    public function create(Request $request)
    {
        $permissions = config('nue.database.permissions_model')::pluck('name', 'id');

        return nue_view("$this->view.create", [
            'title' => $this->title, 
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token', 'pengguna', 'roles', 'permissions', 'uid']);

        $explode = explode("|||" , $request->pengguna);
        $input['uid'] = $explode[0];
        $input['email'] = $explode[1];
        $input['name'] = $explode[2];

        $random = Str::random(10);
        $input['password'] = bcrypt($random);

        $user = $this->data->create($input);
        $user->roles()->sync($request->roles);
        $user->permissions()->sync($request->permissions);

        nue_notify($this->tCreate, 'success');
        return redirect(route("$this->prefix.index"));
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Request $request
     *
     * @return Content
     */
    public function show(Request $request, $id)
    {
        abort(404);
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Request $request
     *
     * @return Content
     */
    public function edit(Request $request, $id)
    {
        $edit = $this->data->findOrFail($id);
     
        $permissions = config('nue.database.permissions_model')::pluck('name', 'id');

        return nue_view("$this->view.create", [
            'title' => $this->title, 
            'edit' => $edit, 
            'permissions' => $permissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $edit = $this->data->findOrFail($id);

        $input = $request->all();
        $edit->update($input);
        $edit->roles()->sync($request->roles);
        $edit->permissions()->sync($request->permissions);

        nue_notify($this->tUpdate, 'success');
        return redirect(route("$this->prefix.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->has('pilihan')):
            foreach($request->pilihan as $temp):
                $data = $this->data->findOrFail($temp);
                switch($id):
                    case 'bulk-delete':
                        $data->forceDelete();
                    break;
                    default:
                    break;
                endswitch;
            endforeach;
            return 'success';
        endif;
    }

    /**
     * Datatable API
     * @param  $data
     * @return Datatable
     */
    public function datatable($data) 
    {
        return datatables()->of($data)
            ->editColumn('pilihan', function($data) {
                return '<div class="form-check mb-0">
                    <input type="checkbox" class="form-check-input pilihan" id="pilihan['.$data->id.']" name="pilihan[]" value="'.$data->id.'">
                        <label class="form-check-label" for="pilihan['.$data->id.']"></label>
                    </div>';
            })
            ->editColumn('uid', function($data) {
                if(!is_null($data['uid'])):
                    return '<i class="bi bi-check-lg text-success"></i>';
                endif;
            })
            ->editColumn('roles', function($data) {
                $return = '';
                foreach($data->roles as $temp) {
                    $return .= '<label class="badge bg-soft-success text-success">
                        <span class="legend-indicator bg-success"></span>'.$temp->name.'
                    </label>';
                }
                return $return;
            })
            ->editColumn('last_login_at', function($data) {
                if(!is_null($data['last_login_at'])):
                    return date('d/m/Y H:i A', strtotime($data->last_login_at));
                endif;
            })
            ->editColumn('action', function($data) {
                return '<a href="'.route("$this->prefix.edit", $data->id).'" class="btn btn-xs btn-info rounded-xs" data-pjax>
                        <i class="bi bi-pencil-square"></i>
                        '.__('Edit').'
                    </a>';
            })
            ->escapeColumns(['*'])->toJson();
    }
}
