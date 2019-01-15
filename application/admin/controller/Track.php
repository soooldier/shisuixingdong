<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 善款追踪
 *
 * @icon fa fa-circle-o
 */
class Track extends Backend
{
    
    /**
     * Track模型对象
     * @var \app\admin\model\Track
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Track');
        $this->view->assign("isReadList", $this->model->getIsReadList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function index()
    {
        
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {

            $student_id = input('param.student_id');

            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->with(['student'])
                ->where($where)
                ->where('student_id', $student_id)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['student'])
                ->where($where)
                ->where('student_id', $student_id)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();


            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        $student_id = input('param.ids');
        $this->assign('student_id', $student_id);
        return $this->view->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }

                    $student = model('student')->where('id', $params['student_id'])->find();
                    $params['donor'] = $student['donor'];
                    $params['donation_id'] = $student['donation_id'];
                    
                    $result = $this->model->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $student_id = input('param.student_id');
        $this->assign('student_id', $student_id);
        return $this->view->fetch();
    }
    

}
