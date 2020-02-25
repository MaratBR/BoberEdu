<?php


namespace App\Http\Controllers;


use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CRUDController extends Controller
{
    /**
     * @var string
     */
    private $class;
    /**
     * @var string
     */
    private $inputName;
    private $createValidationRules;
    private $updateValidationRules;

    //#region Configuration final methods

    protected final function callMethod(string $name, ...$args) {
        return $this->class ? call_user_func($this->class . '::' . $name, ...$args) : null;
    }

    protected final function setClass(string $class) {
        $this->class = $class;
    }

    protected final function setCreateRules($rules) {
        $this->createValidationRules = $rules;
    }

    protected final function setUpdateRules($rules) {
        $this->updateValidationRules = $rules;
    }

    protected final function setInputName(string $name) {
        $this->inputName = $name;
    }

    protected final function configure($class, $createRules, $updateRules, $inputName) {
        $this->setClass($class);
        $this->setCreateRules($createRules);
        $this->setUpdateRules($updateRules);
        $this->setInputName($inputName);
    }

    //#endregion

    protected function getId(Request $request) {
        return $request->{$this->inputName};
    }

    protected function getQueryBuilder(): Builder {
        return $this->callMethod('query');
    }

    protected function getColumns(Request $request): array {
        return ['*'];
    }

    protected function getListColumns(Request $request): array {
        return $this->getColumns($request);
    }

    protected function getById($id, Request $request) {
        $scope = $this->scope(
            $this->getQueryBuilder(), $request
        );
        return $scope ? $scope->findOrFail($id, $this->getColumns($request)) : null;
    }

    protected function getAll(Request $request) {
        $scope = $this->scope($this->getQueryBuilder(), $request);
        return $scope ? $scope->get($this->getListColumns($request)) : [];
    }
    protected function scope(Builder $q, Request $request): ?Builder {
        return $q;
    }

    protected function storeDataPipe($data) {
        return $data;
    }

    protected function updateDataPipe($data) {
        return $data;
    }

    function show(Request $request) {
        $inst = $this->getById($this->getId($request), $request);
        return response()->json($inst);
    }

    function store(Request $request) {
        $data = $request->validate($this->createValidationRules);
        $data = $this->storeDataPipe($data);
        $inst = $this->callMethod('create', $data);
        return response()->json($inst, 201);
    }

    function update(Request $request) {
        $data = $request->validate($this->updateValidationRules);
        $data = $this->updateDataPipe($data);
        $inst = $this->getById($this->getId($request), $request);
        $inst->update($data);
        return response()->noContent();
    }

    function destroy(Request $request) {
        $this->getById($this->getId($request), $request)->delete();
        return response()->noContent();
    }

    function index(Request $request) {
        return response()->json($this->getAll($request));
    }
}
