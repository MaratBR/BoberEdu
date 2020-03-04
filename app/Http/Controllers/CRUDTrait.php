<?php


namespace App\Http\Controllers;



use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

trait CRUDTrait
{
    protected $eloquentModel = null;
    /**
     * @param $cls
     * @param $method
     * @param mixed ...$args
     * @return mixed
     */
    private static function callMethod($cls, $method, ...$args)
    {
        return call_user_func("$cls::$method", ...$args);
    }

    protected function byId($id, $class = null)
    {
        return $this->getQueryBuilder($this->cls($class))->findOrFail($id);
    }

    protected function updById($id, $data, string $class = null)
    {
        if ($data == [])
            return;
        $obj = $this->byId($id, $this->cls($class));
        $obj->update($data);
        $obj->save();
    }

    protected function delById($id, $class = null)
    {
        $class = $this->cls($class);
        $this->getQueryBuilder($class)
            ->where(\app($class)->getKeyName(), '=', $id);
    }

    protected function create($data, $class = null)
    {
        return self::callMethod($this->cls($class), 'create', $data);
    }

    protected function paginate(Request $request, Builder $builder = null, string $class = null)
    {
        $builder = $builder ?? $this->getQueryBuilder($this->cls($class));

        return $builder->paginate();
    }

    protected function getQueryBuilder(?string $class = null): Builder
    {
        return self::callMethod($this->cls($class), 'query');
    }

    private function cls(?string $cls)
    {
        $cls = $cls ?? $this->eloquentModel;
        if ($cls == null)
            throw new \InvalidArgumentException("Model is not specified, please set model class either by setting \$modelName in controller or by passing \$class parameter in methods' calls");
        return $cls;
    }
}
