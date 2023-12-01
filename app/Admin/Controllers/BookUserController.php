<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\BookUser;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class BookUserController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new BookUser(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('email');
            $grid->column('username');
            // $grid->column('password');
            $grid->column('gender');
            // $grid->column('avatar');
            $grid->column('birthday');
            $grid->column('age');
            // $grid->column('remember_token');
            $grid->column('point');
            $grid->column('acc_type');
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
        return Show::make($id, new BookUser(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('email');
            $show->field('username');
            $show->field('password');
            $show->field('gender');
            $show->field('avatar');
            $show->field('birthday');
            $show->field('age');
            $show->field('remember_token');
            $show->field('point');
            $show->field('acc_type');
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
        return Form::make(new BookUser(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->text('email');
            $form->text('username');
            $form->text('password');
            $form->text('gender');
            $form->text('avatar');
            $form->text('birthday');
            $form->text('age');
            $form->text('remember_token');
            $form->text('point');
            $form->text('acc_type');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
