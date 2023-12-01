<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\UserPayRecord;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class UserPayRecordController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new UserPayRecord(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('user_id');
            $grid->column('user_order_no');
            $grid->column('user_point');
            $grid->column('user_payment');
            $grid->column('user_payment_firm');
            $grid->column('user_payment_status');
            $grid->column('user_point_before');
            $grid->column('user_point_after');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });

            // 禁用
            $grid->disableActions();
            // 禁用創建
            $grid->disableCreateButton(); 
            // 禁用顯示
            $grid->disableViewButton();
            // 禁用刪除
            $grid->disableDeleteButton();
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new UserPayRecord(), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('user_order_no');
            $show->field('user_point');
            $show->field('user_payment');
            $show->field('user_payment_firm');
            $show->field('user_payment_status');
            $show->field('user_point_before');
            $show->field('user_point_after');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new UserPayRecord(), function (Form $form) {
            $form->display('id');
            $form->text('user_id');
            $form->text('user_order_no');
            $form->text('user_point');
            $form->text('user_payment');
            $form->text('user_payment_firm');
            $form->text('user_payment_status');
            $form->text('user_point_before');
            $form->text('user_point_after');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
