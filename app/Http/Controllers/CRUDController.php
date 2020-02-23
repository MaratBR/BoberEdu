<?php


namespace App\Http\Controllers;


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

    private function getId(Request $request) {
        return $request->{$this->inputName};
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

    protected function getById($id) {
        return $this->class ? call_user_func($this->class . '::findOrFail', $id) : null;
    }

    protected function getAll() {
        return $this->class ? call_user_func($this->class . '::all') : null;
    }

    function show(Request $request) {
        $inst = $this->getById($this->getId($request));
        return response()->json($inst);
    }

    function store(Request $request) {
        $data = $request->validate($this->createValidationRules);
        $inst = call_user_func($this->class . '::create', $data);
        return response()->json($inst, 201);
    }

    function update(Request $request) {
        $data = $request->validate($this->updateValidationRules);
        $inst = $this->getById($this->getId($request));
        $inst->update($data);
        return response()->noContent();
    }

    function destroy(Request $request) {
        $this->getById($this->getId($request))->delete();
        return response()->noContent();
    }

    function index(Request $request) {
        return response()->json($this->getAll());
    }
}
