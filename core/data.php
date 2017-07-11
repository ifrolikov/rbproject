<?php

namespace core;
use core\validators\abstractions\Validator;

/**
 * Class Data
 * @package core
 */
abstract class Data
{
    /**
     * @var array
     */
    static public $data = [];
    /** @var integer */
    public $id;

    /**
     * @param array $conditions
     * @return static|null
     */
    static public function findOne(array $conditions = [])
    {
        $founded = self::find($conditions);
        return array_shift($founded);
    }

    /**
     * @param array $conditions
     * @return static[]
     */
    static public function find(array $conditions = []): array
    {
        $data = self::getData();
        foreach ($conditions as $field => $value) {
            $data = array_filter($data, function ($raw) use ($field, $value) {
                try {
                    return $raw[$field] === $value;
                } catch (\Exception $e) {
                    return false;
                }
            });
        }

        $result = [];
        foreach ($data as $raw) {
            $class = get_called_class();
            $result[$raw['id']] = new $class();
            foreach ($raw as $field => $value) {
                $result[$raw['id']]->$field = $value;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    static private function getData(): array
    {
        $class = self::getDataKey();
        if (!isset(static::$data[$class])) {
            static::$data[$class] = App::getData()['decode'](file_get_contents(self::getDataFilePath()));
        }
        return static::$data[$class];
    }

    /**
     * @return string
     */
    static private function getDataKey(): string
    {
        return get_called_class();
    }

    /**
     * @return string
     */
    static private function getDataFilePath(): string
    {
        $class = explode('\\', get_called_class());
        $name = strtolower(array_pop($class) . '.' . App::getData()['ext']);
        $path = App::getData()['path'] . '/' . $name;
        if (!file_exists($path)) {
            touch($path);
            chmod($path, 0777);
        }
        return $path;
    }

    /**
     * @param array $data
     */
    static private function saveData(array $data)
    {
        $fullData = self::getData();
        $data['id'] = $data['id'] ?? count($fullData);
        $fullData[$data['id']] = $data;
        static::$data[self::getDataKey()] = $fullData;

        file_put_contents(self::getDataFilePath(), App::getData()['encode']($fullData));
    }

    /**
     * @param array $conditions
     */
    static public function delete(array $conditions)
    {
        $data = self::getData();
        $founded = self::find($conditions);
        $ids = array_map(function (Data $data) {
            return $data->id;
        }, $founded);
        foreach ($ids as $id) {
            unset($data[$id]);
        }
        static::$data[self::getDataKey()] = $data;
        file_put_contents(self::getDataFilePath(), App::getData()['encode']($data));
    }

    /**
     * save model (like AR)
     */
    public function save(bool $validate = true)
    {
        if ($validate) {
            foreach ($this->rules() as $rule) {
                if (count($rule) < 1) {
                    throw new \Exception('incorrect validation rule');
                }

                $fields = (array)array_shift($rule);
                /** @var string|Validator $validatorClass */
                $validatorClass = '\\core\\validators\\' . ucfirst(strtolower(array_shift($rule))) . 'Validator';
                $validator = $validatorClass::getInstance($this);

                foreach ($fields as $field) {
                    if (!$validator->validate($field, $rule)) {
                        throw  new \Exception($field . ' value is not valid "' . $this->$field . '"');
                    }
                }
            }
        }

        $data = (array)$this;
        self::saveData($data);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['id'], 'numeric']
        ];
    }
}