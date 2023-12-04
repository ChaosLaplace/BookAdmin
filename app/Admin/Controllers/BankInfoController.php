<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\BankInfo;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class BankInfoController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new BankInfo(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('parent_id');
            $grid->column('bank_country');
            $grid->column('bank_swift');
            $grid->column('bank_name');
            $grid->column('bank_code');
            $grid->column('bank_branch_code');
            $grid->column('bank_branch_addr');
            $grid->column('bank_account');
            $grid->column('bank_number');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
            });
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
        return Show::make($id, new BankInfo(), function (Show $show) {
            $show->field('id');
            $show->field('parent_id');
            $show->field('bank_country');
            $show->field('bank_swift');
            $show->field('bank_name');
            $show->field('bank_code');
            $show->field('bank_branch_code');
            $show->field('bank_branch_addr');
            $show->field('bank_account');
            $show->field('bank_number');
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
        return Form::make(new BankInfo(), function (Form $form) {
            $form->display('id');
            $form->text('parent_id');
            $form->text('bank_country');
            $form->text('bank_swift');
            $form->text('bank_name');
            $form->text('bank_code');
            $form->text('bank_branch_code');
            $form->text('bank_branch_addr');
            $form->text('bank_account');
            $form->text('bank_number');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
