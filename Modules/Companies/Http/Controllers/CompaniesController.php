<?php namespace Modules\Companies\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Modules\Core\Http\Controllers\BaseAdminController;
use Modules\Companies\Http\Requests\FormRequest;
use Modules\Companies\Repositories\CompanyInterface as Repository;
use Modules\Companies\Entities\Company;
use Modules\Users\Repositories\RoleInterface;
use Modules\Users\Repositories\UserInterface;

class CompaniesController extends BaseAdminController
{

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function index()
    {
        $module = $this->repository->getTable();
        $title = trans($module . '::global.group_name');
        return view('core::admin.index')
            ->with(compact('title', 'module'));
    }

    public function create()
    {
        $module = $this->repository->getTable();
        $form = $this->form(config($module . '.form'), [
            'method' => 'POST',
            'url' => route('admin.' . $module . '.store')
        ]);
        return view('core::admin.create')
            ->with(compact('module', 'form'));
    }

    public function edit(Company $model)
    {
        $module = $model->getTable();
        $form = $this->form(config($module . '.form'), [
            'method' => 'PUT',
            'url' => route('admin.' . $module . '.update', $model),
            'model' => $model
        ]);
        return view('core::admin.edit')
            ->with(compact('model', 'module', 'form'));
    }

    public function store(FormRequest $request, UserInterface $user_repo, RoleInterface $role_repo)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();

            $model = $this->repository->create($data);

            $user_data = [
                'first_name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['email'],
                'last_name' => $data['registration_number'],
                'password' => $data['registration_number'],
                'permissions' => [
                    'dashboard.index' => true,
                    /* Users */
                    'users.index' => true,
                    'users.create' => true,
                    'users.store' => true,
                    'users.edit' => true,
                    'users.update' => true,
                    'users.destroy' => true,
                    /* Balances */
                    'balances.index' => true,
                    'balances.create' => true,
                    'balances.store' => true,
                    'balances.edit' => true,
                    'balances.update' => true,
                    'balances.destroy' => true,
                    'balances.bulk_upload' => true,
                    /* Profile */
                    'companies.show' => true,
                    'companies.edit_profile' => true,
                    'companies.update_profile' => true,
                    /* Balances */
                    'contributions.index' => true,
                    'contributions.create' => true,
                    'contributions.store' => true,
                    'contributions.edit' => true,
                    'contributions.update' => true,
                    'contributions.destroy' => true,
                    'contributions.bulk_upload' => true,
                    /* Members */
                    'members.index' => true,
                    'members.create' => true,
                    'members.store' => true,
                    'members.edit' => true,
                    'members.update' => true,
                    'members.destroy' => true,
                    'members.bulk_upload' => true,
                    /* ledgers */
                    'ledgers.index' => true,
                    'ledgers.create' => true,
                    'ledgers.store' => true,
                    'ledgers.edit' => true,
                    'ledgers.update' => true,
                    'ledgers.destroy' => true,
                    'ledgers.bulk_upload' => true,
                ]
            ];

            $role = $role_repo->findByName('Company');

            $user = $user_repo->createWithRoles($user_data, [$role->id], true);

            $model->users()->attach($user->id);

            DB::commit();

            return $this->redirect($request, $model, trans('core::global.new_record'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError('User Already exist with the email address');
        }
    }

    public function show($model = null)
    {
        $module = 'companies';
        $view = is_admin_role() ? 'show' : 'show_profile';
        $model = empty($model) ? $this->company : $model;
        return view('companies::admin.' . $view)
            ->with(compact('model', 'module'));
    }

    public function update(Company $model, FormRequest $request)
    {
        $data = $request->all();

        $data['id'] = $model->id;

        $model = $this->repository->update($data);

        return $this->redirect($request, $model, trans('core::global.update_record'));
    }

    public function editProfile()
    {
        $model = current_user_company();
        $module = $model->getTable();
        $form = $this->form(config($module . '.form'), [
            'method' => 'PUT',
            'url' => route('admin.' . $module . '.update_profile', $model),
            'model' => $model
        ]);
        return view('core::admin.edit')
            ->with(compact('model', 'module', 'form'));
    }

    public function updateProfile(FormRequest $request)
    {
        $data = $request->all();

        $model = current_user_company();

        $data['id'] = $model->id;

        $model = $this->repository->update($data);

        return $this->redirect($request, $model, trans('core::global.update_record'));
    }

}
