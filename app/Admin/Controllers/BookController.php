<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Book;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Http\JsonResponse;

use Illuminate\Http\Request;

use App\Models\Book as BookBook;
class BookController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Book(), function (Grid $grid) {
            $grid->column('book_id')->sortable();
            $grid->column('book_frontcover');
            $grid->column('book_name_ch');
            $grid->column('book_name_en');
            $grid->column('book_author');
            $grid->column('book_author_id');
            $grid->column('style');
            $grid->column('book_verify')->switch('', true);
            $grid->column('book_shelf')->switch('', true);
            $grid->column('book_state');
            $grid->column('cover_image_1');
            $grid->column('cover_image_2');
            $grid->column('cover_image_3');
            $grid->column('cover_image_4');
            $grid->column('preface');
            $grid->column('book_backcover');
            $grid->column('story_main');
            $grid->column('book_share');
            $grid->column('book_free');
            $grid->column('book_like');
            $grid->column('book_recommend');
            $grid->column('user_input');
            $grid->column('en_user_input');
            $grid->column('book_point');
            $grid->column('preview_page');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('book_id');
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
        return Show::make($id, new Book(), function (Show $show) {
            $show->field('book_id');
            $show->field('book_frontcover');
            $show->field('book_name_ch');
            $show->field('book_name_en');
            $show->field('book_author');
            $show->field('book_author_id');
            $show->field('style');
            $show->field('book_verify');
            $show->field('book_shelf');
            $show->field('book_state');
            $show->field('cover_image_1');
            $show->field('cover_image_2');
            $show->field('cover_image_3');
            $show->field('cover_image_4');
            $show->field('preface');
            $show->field('book_backcover');
            $show->field('story_main');
            $show->field('book_share');
            $show->field('book_free');
            $show->field('book_like');
            $show->field('book_recommend');
            $show->field('user_input');
            $show->field('en_user_input');
            $show->field('book_point');
            $show->field('preview_page');
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
        return Form::make(new Book(), function (Form $form) {
            $form->display('book_id');
                    
            $form->display('created_at');
            $form->display('updated_at');
        });
    }

    protected function saveBook(Request $request, $book_id)
    {
        $content   = $request->all();
        $inputData = [];
        // 更新審核
        if ( isset($content['book_verify']) ) {
            $inputData['book_verify'] = $content['book_verify'];
        }
        // 更新上架
        if ( isset($content['book_shelf']) ) {
            $inputData['book_shelf']  = $content['book_shelf'];
        }

        if ( BookBook::query()->where('book_id', $book_id)->update($inputData) ) {
            return JsonResponse::make()->success('更新成功！');
        }
        return JsonResponse::make()->error('更新失敗');
    }
}
