<?php namespace Modules\Members\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\Http\Controllers\BaseAdminController;
use Modules\Members\Http\Requests\FormRequest;
use Modules\Members\Imports\MembersImport;
use Modules\Members\Repositories\MemberInterface as Repository;
use Modules\Members\Entities\Member;

class MembersController extends BaseAdminController {

    protected $import = MembersImport::class;

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function index()
    {
        $module = $this->repository->getTable();
        $title = trans($module . '::global.group_name');
        return view('members::admin.index')
            ->with(compact('title', 'module'));
    }

    public function create()
    {
        $module = $this->repository->getTable();
        $form = $this->form(config($module.'.form'), [
            'method' => 'POST',
            'url' => route('admin.'.$module.'.store')
        ]);
        return view('core::admin.create')
            ->with(compact('module','form'));
    }

    public function edit(Member $model)
        {
            $module = $model->getTable();
            $form = $this->form(config($module.'.form'), [
                'method' => 'PUT',
                'url' => route('admin.'.$module.'.update',$model),
                'model'=>$model
            ]);
            return view('core::admin.edit')
                ->with(compact('model','module','form'));
        }

    public function store(FormRequest $request)
    {
        $data = $request->all();

        $data['company_id'] = isset($this->company->id) ? $this->company->id : 0;

        $model = $this->repository->create($data);

        return $this->redirect($request, $model, trans('core::global.new_record'));
    }

    public function update(Member $model,FormRequest $request)
    {
        $data = $request->all();

        $data['id'] = $model->id;

        $model = $this->repository->update($data);

        return $this->redirect($request, $model, trans('core::global.update_record'));
    }


    public function show($member) {
       
        $module = 'members';
        $model =  $member;
        return view('members::admin.' . "show")
            ->with(compact('model', 'module'));
    }

}
