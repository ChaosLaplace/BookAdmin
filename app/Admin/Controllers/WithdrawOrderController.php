<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\WithdrawOrder;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class WithdrawOrderController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new WithdrawOrder(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('parent_id');
            $grid->column('bank_id');
            $grid->column('phone');
            $grid->column('book_point');
            $grid->column('payment');
            $grid->column('status');
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
        return Show::make($id, new WithdrawOrder(), function (Show $show) {
            $show->field('id');
            $show->field('parent_id');
            $show->field('bank_id');
            $show->field('phone');
            $show->field('book_point');
            $show->field('payment');
            $show->field('status');
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
        return Form::make(new WithdrawOrder(), function (Form $form) {
            $form->display('id');
            $form->text('parent_id');
            $form->text('bank_id');
            $form->text('phone');
            $form->text('book_point');
            $form->text('payment');
            $form->text('status');
            $form->text('user_point_before');
            $form->text('user_point_after');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}