<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\FileComparison;
use App\Models\FileComparison as ModelFileComparison;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Traits\HasUploadedFile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileComparisonController extends AdminController
{
    use HasUploadedFile;

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new FileComparison(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('file_title');
            $grid->column('file_type');
            $grid->column('file_content');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
            });

            // 禁用
            $grid->disableActions();
            // 禁用創建
            // $grid->disableCreateButton(); 
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
        return Show::make($id, new FileComparison(), function (Show $show) {
            $show->field('id');
            $show->field('file_type');
            $show->field('file_title');
            $show->field('file_content');
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
        return Form::make(new FileComparison(), function (Form $form) {
            $form->multipleFile()->threads(5)->chunkSize(1024)->override()->url('/file/upload')->required()->autoUpload();
        });
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        if ( $request->hasFile('_file_') ) {
            try {
                // 文件
                $file      = $request->file('_file_');
                $extension = $file->getClientOriginalExtension();
                $filename  = $file->getClientOriginalName();
                $filename  = explode('.' . $extension, $filename);
                $filename  = $filename['0']; 

                $disk      = $this->disk('public');
                $dir       = 'FileComparison/' . Date('Ymd');
                $newName   = $filename . '.' .  $extension;
                $result    = $disk->putFileAs($dir, $file, $newName);
                $path      = "{$dir}/$newName";
                Log::info('文件上傳 : ' . json_encode($result));

                if ($result) {
                    // 讀取文件內容轉檔
                    $where = [
                        'file_title' => $filename,
                        'file_type'  => $extension,
                    ];
                    if ( !ModelFileComparison::where($where)->exists() ) {
                        Log::info('文件內容轉檔');  
                        return $this->responseUploaded($path, $disk->url($path));
                    }
                    Log::info('文件已存在 : ' . json_encode($result));                
                }
                
                return $this->responseErrorMessage('文件上傳失敗');
            } catch (\Exception $e) {
                Log::info('文件上傳失敗：' . $e->getMessage());
                return $this->responseErrorMessage('文件上傳失敗：' . $e->getMessage());
            }
        }
        return $this->responseErrorMessage('不是文件');
    }
}
