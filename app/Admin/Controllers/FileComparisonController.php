<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\FileComparison;
use App\Models\FileComparison as ModelFileComparison;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

class FileComparisonController extends AdminController
{
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
            $grid->column('created_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });
            // 禁用
            $grid->disableActions();
            // 禁用顯示
            $grid->disableViewButton();
            // 禁用刪除
            $grid->disableDeleteButton();
            // 禁止
            $grid->toolsWithOutline(false);
            // 計畫內容分析
            $grid->quickSearch(function ($model, $query) {
                if ( isset($query) && $query !== '' ) {
                    ini_set('memory_limit', '2048M');
                    // 繁體 詳情參考 https://github.com/fukuball/jieba-php
                    Jieba::init( array('mode'=>'default','dict'=>'big') );
                    Finalseg::init();
                    $seg_list = Jieba::cut($query, false);
                    Log::info('中文分詞 : ' . json_encode($seg_list, JSON_UNESCAPED_UNICODE) );

                    $orWhere = [];
                    foreach ($seg_list as $v) {
                        $orWhere[] = ['file_content', 'like', "%{$v}%"];
                    }

                    $model->where('file_content', $query)->orWhere($orWhere);
                    Log::info('計畫內容分析 : ' . json_encode($orWhere, JSON_UNESCAPED_UNICODE)) ;
                }
            })->placeholder('計畫內容分析')->auto(false);
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
        $disk = $this->disk('public');
        // 刪除文件
        if ( $this->isDeleteRequest() ) {
            return $this->deleteFileAndResponse($disk);
        }

        if ( $request->hasFile('_file_') ) {
            try {
                // 文件
                $file      = $request->file('_file_');
                $extension = $file->getClientOriginalExtension();
                $filename  = $file->getClientOriginalName();
                $filename  = explode('.' . $extension, $filename);
                $filename  = $filename['0']; 
                // 判斷副檔名
                switch ($extension) {
                    case 'doc':
                        return $this->responseErrorMessage('文件不可上傳 .doc, 請手動轉檔成 .docx');
                    break;
                    default:
                    break;
                }

                $dir     = 'FileComparison/' . Date('Ymd');
                $newName = $filename . '.' .  $extension;
                $result  = $disk->putFileAs($dir, $file, $newName);
                $path    = "{$dir}/$newName";
                Log::info('文件上傳 : ' . json_encode($result));

                if ($result) {
                    // 讀取文件內容轉檔
                    $where = [
                        'file_title' => $filename,
                        'file_type'  => $extension,
                    ];
                    if ( !ModelFileComparison::where($where)->exists() ) {
                        // Load the Word file
                        $file_path     = storage_path('app') . '/public/' . $dir;
                        $new_file      = $file_path . '/' . $filename;
                        $new_file_name = $new_file . '.' . $extension;
                        Log::info('file_path -> ' . $file_path);
                        Log::info('new_file -> ' . $new_file);
                        Log::info('new_file_name -> ' . $new_file_name);
                        // WORD => HTML
                        $word   = new \PhpOffice\PhpWord\Reader\Word2007;
                        Log::info('文件內容轉檔2 -> ' . $new_file_name);
                        $result = $word->load($new_file_name);
                        $write  = new \PhpOffice\PhpWord\Writer\HTML($result);
                        $write->save($new_file . '.html');
                        // HTML 內容
                        $document = new \DOMDocument();
                        $document->loadHTML( file_get_contents( $new_file . '.html' ) );
                        $html     = simplexml_import_dom($document);
                        $html     = json_encode($html, JSON_UNESCAPED_UNICODE);
                        $html     = str_replace(' ', '', $html);

                        $data     = [
                            'file_title'   => $filename,
                            'file_type'    => $extension,
                            'file_content' => $html,
                        ];
                        if ( ModelFileComparison::insert($data) ) {
                            Log::info('文件內容轉檔成功');
                            return $this->responseUploaded($path, $disk->url($path));
                        }                        
                    }
                    else {
                        Log::info('文件已存在 : ' . json_encode($result));
                        return $this->responseErrorMessage('文件已存在 : ' . $filename . '.' . $extension); 
                    }
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
