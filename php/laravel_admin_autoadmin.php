<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class GosAutoAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gos:admin {table} {model} {modelTitle}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动生成后台代码';

    /**
     * @var array
     */
    protected $formats = [
        'form_field'  => "\$form->%s('%s', __('%s'))",
        'show_field'  => "\$show->field('%s', __('%s'))",
        'grid_column' => "\$grid->column('%s', __('%s'))",
    ];

    /**
     * @var array
     */
    protected $fieldTypeMapping = [
        'ip'       => 'ip',
        'email'    => 'email|mail',
        'password' => 'password|pwd',
        'url'      => 'url|link|src|href',
        'mobile'   => 'mobile|phone',
        'color'    => 'color|rgb',
        'image'    => 'image|img|avatar|pic|picture|cover',
        'file'     => 'file|attachment',
    ];

     /**
      * 控制器名称
     * @var string
     */
    protected $controllerName;

    /**
     * 模型名称
     * @var string
     */
    protected $modelName;

     /**
     * 数据表名称
     * @var string
     */
    protected $tableName;

    /**
     * 页面显示的标题
     * @var string
     */
    protected $modelTitle;

     /**
     * 所有表字段
     * @var string
     */
    protected $columns;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->modelName = $this->getModelName();
        $this->modelTitle = $this->getModelTitle();
        $this->tableName = $this->getTableName();
        if (empty($this->modelName) || empty($this->tableName) || empty($this->modelTitle)) {
            $this->error('命令不正确!');
            return false;
        }

        $this->columns = $this->getColumns();
        $modelExists = $this->modelExists();
        $canMakeController = false;
        if (!$modelExists) {
            $createModel = $this->replaceModel();
            if ($createModel !== false) {
                $canMakeController = true;
            } else {
                $this->error('创建模型失败!');
                return false;
            }
        } else {
            $canMakeController = true;
        }

        if ($canMakeController) {
            $this->controllerName = $this->getControllerName();
            $createController = $this->replaceController();
            if ($createController === false) {
                $this->error('创建控制器失败!');
                return false;
            } 
            $this->addRoute();
        } 

        $this->info('创建'.$this->modelTitle.'成功!');
        $this->info($this->modelName);
        $this->info($this->controllerName);
        return true;
    }

    /**
     * 获取模型名称，包括命名空间
     */
    protected function getModelName()
    {
        $model = $this->argument('model');
        return 'App\\Models\\' . $model;
    }

    /**
     * 获取表名称
     */
    protected function getTableName()
    {
        return$this->argument('table');
    }

    /**
     * 获取控制器名称，包括命名空间
     */
    protected function getControllerName()
    {
        $model = $this->argument('model');
        $namespace = $this->getAdminNamespace();
        return $namespace . '\\' . $model . "Controller";
    }

    /**
     * 获取页面标题
     */
    protected function getModelTitle()
    {
        return $this->argument('modelTitle');
    }

    /**
     * 获取控制器模板
     */
    protected function getControllerStub()
    {
        
        if ($this->modelName) {
            return __DIR__.'/stubs/controller.stub';
        }
    }

     /**
     * 获取控制器模板
     */
    protected function getModelStub()
    {
        if ($this->modelName) {
            return __DIR__.'/stubs/model.stub';
        }
    }

    /**
     * 获取路由文件
     */
    protected function getRouteFile() 
    {
        return app_path().'/Admin/routes.php';
    }

    /**
     * 获取命名空间
     */
    protected function getAdminNamespace()
    {
        return config('admin.route.namespace');
    }

    protected function indentCodes($code)
    {
        $indent = str_repeat(' ', 8);

        return rtrim($indent.preg_replace("/\r\n/", "\r\n{$indent}", $code));
    }

    /**
     * 获取表字段
     */
    protected function getColumns()
    {
        $schema = DB::connection()->getDoctrineSchemaManager($this->tableName);
        return $schema->listTableColumns($this->tableName);
    }

    /**
     * 判断模型是否存在
     */
    protected function modelExists()
    {
        return class_exists($this->modelName) && \is_subclass_of($this->modelName, Model::class);
    }
   

    /**
     * 控制器模板变量替换
     */
    protected function replaceController()
    {
        $stub = $this->getControllerStub();
        $template = file_get_contents($stub);
        $namespace = $this->getAdminNamespace();
        $className = class_basename($this->controllerName);
        $file =  app_path() . '/Admin/Controllers/' . $className . '.php';
        
      

        $content =  str_replace(
            [
                'DummyNamespace',
                'DummyModelNamespace',
                'DummyClass',
                'DummyTitle',
                'DummyModel',
                'DummyGrid',
                'DummyShow',
                'DummyForm',
            ],
            [
                $namespace,
                $this->modelName,
                $className,
                $this->getModelTitle(),
                class_basename($this->modelName),
                $this->indentCodes($this->generateGrid()),
                $this->indentCodes($this->generateShow()),
                $this->indentCodes($this->generateForm()),
            ],
            $template
        );
        return file_put_contents($file, $content);
    }

    /**
     * 模型模板变量替换
     */
    protected function replaceModel()
    {
        $stub = $this->getModelStub();
        $template = file_get_contents($stub);
        $className = class_basename($this->modelName);
        $file =  app_path() . '/Models/' . $className . '.php';
        $fileds = '';
        if (count($this->columns) > 0) {
            foreach($this->columns as $column) {
                $columnName = $column->getName();
                $fileds .= sprintf("'%s',", $columnName);
            }
            $fileds = rtrim($fileds, ',');
        }

        $content = str_replace(
            [
                'DummyModelClass',
                'TableName',
                'DummyFills'
            ],
            [
                $className,
                '"'.$this->tableName . '"',
                $fileds
            ],
            $template
        );
        return file_put_contents($file, $content);
    }

    /**
     * 添加路由
     */
    protected function addRoute()
    {
        $file = $this->getRouteFile();
        $template = file_get_contents($file);
        $className = class_basename($this->controllerName);
        //$router->resource('users', UserController::class);
        $resource =  "\t \$router->resource('%s', %s)";
        $newEnd = sprintf($resource, $this->tableName, $className.'::class') . ";\r\n});";
        $content = str_replace(
           '});',
           $newEnd,
            $template
        );
        return file_put_contents($file, $content);
    }

    /**
     * @return string
     */
    public function generateForm()
    {
    
        $output = '';

        foreach ($this->columns as $column) {
            $name = $column->getName();
            if (in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }
           
            $type = $column->getType()->getName();
            $default = $column->getDefault();

            $defaultValue = '';

            // set column fieldType and defaultValue
            switch ($type) {
                case 'boolean':
                case 'bool':
                    $fieldType = 'switch';
                    break;
                case 'json':
                case 'array':
                case 'object':
                    $fieldType = 'text';
                    break;
                case 'string':
                    $fieldType = 'text';
                    foreach ($this->fieldTypeMapping as $type => $regex) {
                        if (preg_match("/^($regex)$/i", $name) !== 0) {
                            $fieldType = $type;
                            break;
                        }
                    }
                    $defaultValue = "'{$default}'";
                    break;
                case 'integer':
                case 'bigint':
                case 'smallint':
                case 'timestamp':
                    $fieldType = 'number';
                    break;
                case 'decimal':
                case 'float':
                case 'real':
                    $fieldType = 'decimal';
                    break;
                case 'datetime':
                    $fieldType = 'datetime';
                    $defaultValue = "date('Y-m-d H:i:s')";
                    break;
                case 'date':
                    $fieldType = 'date';
                    $defaultValue = "date('Y-m-d')";
                    break;
                case 'time':
                    $fieldType = 'time';
                    $defaultValue = "date('H:i:s')";
                    break;
                case 'text':
                case 'blob':
                    $fieldType = 'textarea';
                    break;
                default:
                    $fieldType = 'text';
                    $defaultValue = "'{$default}'";
            }

            $defaultValue = $defaultValue ?: $default;

            $label = $this->formatLabel($name);

            $output .= sprintf($this->formats['form_field'], $fieldType, $name, $label);

            if (trim($defaultValue, "'\"")) {
                $output .= "->default({$defaultValue})";
            }

            $output .= ";\r\n";
        }

        return $output;
    }

    public function generateShow()
    {
        $output = '';

        foreach ($this->columns as $column) {
            $name = $column->getName();

            // set column label
            $label = $this->formatLabel($name);

            $output .= sprintf($this->formats['show_field'], $name, $label);

            $output .= ";\r\n";
        }

        return $output;
    }

    public function generateGrid()
    {
        $output = '';

        foreach ($this->columns as $column) {
            $name = $column->getName();
            $label = $this->formatLabel($name);

            $output .= sprintf($this->formats['grid_column'], $name, $label);
            $output .= ";\r\n";
        }

        return $output;
    }

    /**
     * Format label.
     *
     * @param string $value
     *
     * @return string
     */
    protected function formatLabel($value)
    {
        return ucfirst(str_replace(['-', '_'], ' ', $value));
    }
}
