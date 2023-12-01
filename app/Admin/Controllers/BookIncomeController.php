<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\BookIncome;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class BookIncomeController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new BookIncome(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('auth_id');
            $grid->column('book_id');
            $grid->column('parent_id');
            $grid->column('book_point');
            $grid->column('book_income');
            $grid->column('book_fee');
            $grid->column('auth_point_before');
            $grid->column('auth_point_after');
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
            // 禁止
            $grid->toolsWithOutline(false);
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
        return Show::make($id, new BookIncome(), function (Show $show) {
            $show->field('id');
            $show->field('auth_id');
            $show->field('book_id');
            $show->field('parent_id');
            $show->field('book_point');
            $show->field('book_income');
            $show->field('book_fee');
            $show->field('auth_point_before');
            $show->field('auth_point_after');
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
        return Form::make(new BookIncome(), function (Form $form) {
            $form->display('id');
            $form->text('auth_id');
            $form->text('book_id');
            $form->text('parent_id');
            $form->text('book_point');
            $form->text('book_income');
            $form->text('book_fee');
            $form->text('auth_point_before');
            $form->text('auth_point_after');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
