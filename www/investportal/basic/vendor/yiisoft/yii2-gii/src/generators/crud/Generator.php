<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\gii\generators\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;

/**
 * Generates CRUD controller and views.
 *
 * @property-read array $columnNames Model column/attribute names.
 * @property-read string $controllerID The controller ID (without the module ID prefix).
 * @property-read string $nameAttribute
 * @property-read array $searchAttributes Searchable attributes.
 * @property-read \yii\db\TableSchema|bool $tableSchema
 * @property-read string $viewPath The controller view path.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\Generator
{
    /**
     * @var string
     */
    public $modelClass;
    /**
     * @var string
     */
    public $controllerClass;
    /**
     * @var string The controller view path
     */
    public $viewPath;
    /**
     * @var string
     */
    public $baseControllerClass = 'yii\web\Controller';
    /**
     * @var string
     */
    public $indexWidgetType = 'grid';
    /**
     * @var string
     */
    public $searchModelClass = '';
    /**
     * @var bool whether to wrap the `GridView` or `ListView` widget with the `yii\widgets\Pjax` widget
     * @since 2.0.5
     */
    public $enablePjax = false;
    /**
     * @var bool whether to use strict inflection for controller IDs (insert a separator between two consecutive uppercase chars)
     * @since 2.1.0
     */
    public $strictInflector = true;


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'CRUD Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model.';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['controllerClass', 'modelClass', 'searchModelClass', 'baseControllerClass'], 'filter', 'filter' => 'trim'],
            [['modelClass', 'controllerClass', 'baseControllerClass', 'indexWidgetType'], 'required'],
            [['searchModelClass'], 'compare', 'compareAttribute' => 'modelClass', 'operator' => '!==', 'message' => 'Search Model Class must not be equal to Model Class.'],
            [['modelClass', 'controllerClass', 'baseControllerClass', 'searchModelClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            ['modelClass', 'validateClass', 'params' => ['extends' => BaseActiveRecord::className()]],
            [['baseControllerClass'], 'validateClass', 'params' => ['extends' => Controller::className()]],
            [['controllerClass'], 'match', 'pattern' => '/Controller$/', 'message' => 'Controller class name must be suffixed with "Controller".'],
            [['controllerClass'], 'match', 'pattern' => '/(^|\\\\)[A-Z][^\\\\]+Controller$/', 'message' => 'Controller class name must start with an uppercase letter.'],
            [['controllerClass', 'searchModelClass'], 'validateNewClass'],
            ['indexWidgetType', 'in', 'range' => ['grid', 'list']],
            ['modelClass', 'validateModelClass'],
            [['enableI18N', 'enablePjax'], 'boolean'],
            ['messageCategory', 'validateMessageCategory', 'skipOnEmpty' => false],
            ['viewPath', 'safe'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'modelClass' => 'Model Class',
            'controllerClass' => 'Controller Class',
            'viewPath' => 'View Path',
            'baseControllerClass' => 'Base Controller Class',
            'indexWidgetType' => 'Widget Used in Index Page',
            'searchModelClass' => 'Search Model Class',
            'enablePjax' => 'Enable Pjax',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelClass' => 'This is the <code>BaseActiveRecord</code> class associated with the table that CRUD will be built upon.
                You should provide a fully qualified class name, e.g., <code>app\models\Post</code>.',
            'controllerClass' => 'This is the name of the controller class to be generated. You should
                provide a fully qualified namespaced class (e.g. <code>app\controllers\PostController</code>),
                and class name should be in CamelCase with an uppercase first letter. Make sure the class
                is using the same namespace as specified by your application\'s controllerNamespace property.',
            'viewPath' => 'Specify the directory for storing the view scripts for the controller. You may use path alias here, e.g.,
                <code>/var/www/basic/controllers/views/post</code>, <code>@app/views/post</code>. If not set, it will default
                to <code>@app/views/ControllerID</code>',
            'baseControllerClass' => 'This is the class that the new CRUD controller class will extend from.
                You should provide a fully qualified class name, e.g., <code>yii\web\Controller</code>.',
            'indexWidgetType' => 'This is the widget type to be used in the index page to display list of the models.
                You may choose either <code>GridView</code> or <code>ListView</code>',
            'searchModelClass' => 'This is the name of the search model class to be generated. You should provide a fully
                qualified namespaced class name, e.g., <code>app\models\PostSearch</code>.',
            'enablePjax' => 'This indicates whether the generator should wrap the <code>GridView</code> or <code>ListView</code>
                widget on the index page with <code>yii\widgets\Pjax</code> widget. Set this to <code>true</code> if you want to get
                sorting, filtering and pagination without page refreshing.',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return ['controller.php'];
    }

    /**
     * {@inheritdoc}
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['baseControllerClass', 'indexWidgetType']);
    }

    /**
     * Checks if model class is valid
     */
    public function validateModelClass()
    {
        $class = $this->modelClass;
        if (!method_exists($class, 'primaryKey') || !$class::primaryKey()) {
            $this->addError('modelClass', "The table associated with $class must have primary key(s).");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');

        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        if (!empty($this->searchModelClass)) {
            $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
            $files[] = new CodeFile($searchModel, $this->render('search.php'));
        }

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }

        return $files;
    }

    /**
     * @return string the controller ID (without the module ID prefix)
     */
    public function getControllerID()
    {
        $pos = strrpos($this->controllerClass, '\\');
        $class = substr($this->controllerClass, $pos + 1, -10);

        return Inflector::camel2id($class, '-', $this->strictInflector);
    }

    /**
     * @return string the controller view path
     */
    public function getViewPath()
    {
        if (empty($this->viewPath)) {
            return Yii::getAlias('@app/views/' . $this->getControllerID());
        }

        return Yii::getAlias(str_replace('\\', '/', $this->viewPath));
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        foreach ($this->getColumnNames() as $name) {
            if (!strcasecmp($name, 'name') || !strcasecmp($name, 'title')) {
                return $name;
            }
        }
        /* @var $class \yii\db\ActiveRecord */
        $class = $this->modelClass;
        $pk = $class::primaryKey();

        return $pk[0];
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$form->field(\$model, '$attribute')->passwordInput()";
            }

            return "\$form->field(\$model, '$attribute')";
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        }

        if ($column->type === 'text') {
            return "\$form->field(\$model, '$attribute')->textarea(['rows' => 6])";
        }

        if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
            $input = 'passwordInput';
        } else {
            $input = 'textInput';
        }

        if (is_array($column->enumValues) && count($column->enumValues) > 0) {
            $dropDownOptions = [];
            foreach ($column->enumValues as $enumValue) {
                $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
            }
            return "\$form->field(\$model, '$attribute')->dropDownList("
                . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
        }

        if ($column->phpType !== 'string' || $column->size === null) {
            return "\$form->field(\$model, '$attribute')->$input()";
        }

        return "\$form->field(\$model, '$attribute')->$input(['maxlength' => true])";
    }

    /**
     * Generates code for active search field
     * @param string $attribute
     * @return string
     */
    public function generateActiveSearchField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false) {
            return "\$form->field(\$model, '$attribute')";
        }

        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        }

        return "\$form->field(\$model, '$attribute')";
    }

    /**
     * Generates column format
     * @param \yii\db\ColumnSchema $column
     * @return string
     */
    public function generateColumnFormat($column)
    {
        if ($column->phpType === 'boolean') {
            return 'boolean';
        }

        if ($column->type === 'text') {
            return 'ntext';
        }

        if (stripos($column->name, 'time') !== false && $column->phpType === 'integer') {
            return 'datetime';
        }

        if (stripos($column->name, 'email') !== false) {
            return 'email';
        }

        if (preg_match('/(\b|[_-])url(\b|[_-])/i', $column->name)) {
            return 'url';
        }

        return 'text';
    }

    /**
     * Generates validation rules for the search model.
     * @return array the generated validation rules
     */
    public function generateSearchRules()
    {
        if (($table = $this->getTableSchema()) === false) {
            return ["[['" . implode("', '", $this->getColumnNames()) . "'], 'safe']"];
        }
        $types = [];
        foreach ($table->columns as $column) {
            switch ($column->type) {
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                default:
                    $types['safe'][] = $column->name;
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }

        return $rules;
    }

    /**
     * @return array searchable attributes
     */
    public function getSearchAttributes()
    {
        return $this->getColumnNames();
    }

    /**
     * Generates the attribute labels for the search model.
     * @return array the generated attribute labels (name => label)
     */
    public function generateSearchLabels()
    {
        $class = $this->modelClass;
        /* @var $model \yii\db\BaseActiveRecord */
        $model = new $class();
        $attributeLabels = $model->attributeLabels();
        $labels = [];
        foreach ($this->getColumnNames() as $name) {
            if (isset($attributeLabels[$name])) {
                $labels[$name] = $attributeLabels[$name];
            } else {
                if (!strcasecmp($name, 'id')) {
                    $labels[$name] = 'ID';
                } else {
                    $label = Inflector::camel2words($name);
                    if (!empty($label) && substr_compare($label, ' id', -3, 3, true) === 0) {
                        $label = substr($label, 0, -3) . ' ID';
                    }
                    $labels[$name] = $label;
                }
            }
        }

        return $labels;
    }

    /**
     * Generates search conditions
     * @return array
     */
    public function generateSearchConditions()
    {
        $columns = [];
        if (($table = $this->getTableSchema()) === false) {
            $class = $this->modelClass;
            /* @var $model \yii\db\BaseActiveRecord */
            $model = new $class();
            foreach ($model->attributes() as $attribute) {
                $columns[$attribute] = 'unknown';
            }
        } else {
            foreach ($table->columns as $column) {
                $columns[$column->name] = $column->type;
            }
        }

        $likeConditions = [];
        $hashConditions = [];
        foreach ($columns as $column => $type) {
            switch ($type) {
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $hashConditions[] = "'{$column}' => \$this->{$column},";
                    break;
                default:
                    $likeKeyword = $this->getClassDbDriverName() === 'pgsql' ? 'ilike' : 'like';
                    $likeConditions[] = "->andFilterWhere(['{$likeKeyword}', '{$column}', \$this->{$column}])";                    
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }

        return $conditions;
    }

    /**
     * Generates URL parameters
     * @return string
     */
    public function generateUrlParams()
    {
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        $isMongoModel = is_subclass_of($class, '\yii\mongodb\ActiveRecord');
        $params = [];
        foreach ($pks as $pk) {
            if ($isMongoModel) {
                $params[] = "'$pk' => (string) \$model->$pk";
            } else {
                $params[] = "'$pk' => \$model->$pk";
            }
        }

        return implode(', ', $params);
    }

    /**
     * Generates action parameters.
     *
     * @return string
     */
    public function generateActionParams()
    {
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        return '$' . implode(', $', $pks);
    }

    /**
     * Generates parameter tags for PhpDoc.
     *
     * @return string[]
     */
    public function generateActionParamComments()
    {
        $table = $this->getTableSchema();
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        $model = new $class();
        $labels = $model->attributeLabels();
        $aliasTypes = ['boolean' => 'bool', 'integer' => 'int', 'double' => 'float'];

        $comments = [];
        foreach ($pks as $pk) {
            if ($table) {
                $type = $table->columns[$pk]->phpType;
                if (isset($aliasTypes[$type])) {
                    $type = $aliasTypes[$type];
                }
            } else {
                $type = strtolower(substr($pk, -3)) === '_id' ? 'int' : 'string';
            }
            $descr = isset($labels[$pk]) ? ' ' . $labels[$pk] : '';
            $comments[] = '@param ' . $type . ' $' . $pk . $descr;
        }

        return $comments;
    }

    /**
     * Returns table schema for current model class or false if it is not an active record
     * @return \yii\db\TableSchema|bool
     */
    public function getTableSchema()
    {
        $class = $this->modelClass;
        if (is_subclass_of($class, '\yii\db\BaseActiveRecord')) {
            return $class::getTableSchema();
        }

        return false;
    }

    /**
     * @return array model column/attribute names
     */
    public function getColumnNames()
    {
        $schema = $this->getTableSchema();
        if ($schema) {
            return $schema->getColumnNames();
        }

        $class = $this->modelClass;
        /* @var $model \yii\db\BaseActiveRecord */
        $model = new $class();

        return $model->attributes();
    }

    /**
     * @return string|null driver name of modelClass db connection.
     * In case db is not instance of \yii\db\Connection null will be returned.
     * @since 2.0.6
     */
    protected function getClassDbDriverName()
    {
        if (is_subclass_of($this->modelClass, '\yii\db\ActiveRecord')) {
            $class = $this->modelClass;
            $db = $class::getDb();
            return $db instanceof \yii\db\Connection ? $db->driverName : null;
        }

        return null;
    }
}
