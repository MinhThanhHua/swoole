<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * News1 Model
 *
 * @method \App\Model\Entity\News1 get($primaryKey, $options = [])
 * @method \App\Model\Entity\News1 newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\News1[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\News1|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\News1 saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\News1 patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\News1[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\News1 findOrCreate($search, callable $callback = null, $options = [])
 */
class News1Table extends Table
{
    protected $_accessible = ['id' => false, '*' => true];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('news1');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        return $validator;
    }

    public function getAllNews()
    {
        return $this->find()
            ->select(['id', 'title'])
            ->orderAsc('title')
            ->enableHydration(true);
    }
}
