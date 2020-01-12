<?php

/**
 * @package   yii2-grid
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2017
 * @version   3.1.7
 */

namespace frontend\modules\reports\controllers;

use Yii;
use yii\base\InvalidCallException;
use yii\helpers\HtmlPurifier;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use kartik\base\Config;
use kartik\grid\GridView;
use kartik\mpdf\Pdf;
use kartik\grid\Module;

/**
 * ExportController manages actions for downloading the [[GridView]] tabular content in various export formats.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class ExportController extends Controller
{
    /**
     * Download the exported file
     *
     * @return mixed
     */
    public function actionDownload()
    {
        /**
         * @var Module $module
         */
        $request = Yii::$app->request;
        $moduleId = $request->post('module_id', Module::MODULE);
        $module = Config::getModule($moduleId, Module::className());
        $type = $request->post('export_filetype', 'html');
        $name = $request->post('export_filename', Yii::t('kvgrid', 'export'));
        $content = $request->post('export_content', Yii::t('kvgrid', 'No data found'));
        $mime = $request->post('export_mime', 'text/plain');
        $encoding = $request->post('export_encoding', 'utf-8');
        $bom = $request->post('export_bom', 1);
        $config = $request->post('export_config', '{}');
        $startDate = $request->post('from_date');
        $endDate = $request->post('to_date');
        //$title = 'Accomplishment Report';
        //$oldHash = $request->post('export_hash');
        //$newData = $moduleId . $name . $mime . $encoding . $bom . $config;
        // $security = Yii::$app->security;
        // $salt = $module->exportEncryptSalt;
        // $newHash = $security->hashData($newData, $salt);
        // if (!$security->validateData($oldHash, $salt) || $oldHash !== $newHash) {
        //     $params = "\nOld Hash:{$oldHash}\nNew Hash:{$newHash}\n";
        //     throw new InvalidCallException("The parameters for yii2-grid export seem to be tampered. Please retry!{$params}");
        // }
        //$filename = $name.' ('.$startDate.'_'.$endDate.')';
        if ($type == GridView::PDF) {
            $config = Json::decode($config);
            $this->generatePDF($content, "{$name}.pdf", $config);
            //$this->generatePDF($content, "{$filename}.pdf", $config);
            /** @noinspection PhpInconsistentReturnPointsInspection */
            return;
        } elseif ($type == GridView::HTML) {
            $content = HtmlPurifier::process($content);
        } elseif ($type == GridView::CSV || $type == GridView::TEXT) {
            if ($encoding != 'utf-8') {
                $content = mb_convert_encoding($content, $encoding, 'utf-8');
            } elseif ($bom) {
                $content = chr(239) . chr(187) . chr(191) . $content; // add BOM
            }
        } //elseif ($type == GridView::EXCEL) {

        //}

        /*$title = empty($this->caption) ? Yii::t('kvgrid', 'Grid Export') : $this->caption;
        $pdfHeader = [
            'L' => [
                'content' => Yii::t('kvgrid', 'Yii2 Grid Export (PDF)'),
                'font-size' => 8,
                'color' => '#333333',
            ],
            'C' => [
                'content' => $title,
                'font-size' => 16,
                'color' => '#333333',
            ],
            'R' => [
                'content' => Yii::t('kvgrid', 'Generated') . ': ' . date('D, d-M-Y g:i a T'),
                'font-size' => 8,
                'color' => '#333333',
            ],
        ];
        $pdfFooter = [
            'L' => [
                'content' => Yii::t('kvgrid', '© Krajee Yii2 Extensions'),
                'font-size' => 8,
                'font-style' => 'B',
                'color' => '#999999',
            ],
            'R' => [
                'content' => '[ {PAGENO} ]',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' => 'serif',
                'color' => '#333333',
            ],
            'line' => true,
        ];
        $defaultExportConfig = [
            self::EXCEL => [
                'label' => Yii::t('kvgrid', 'Excel'),
                'icon' => $isFa ? 'file-excel-o' : 'floppy-remove',
                'iconOptions' => ['class' => 'text-success'],
                'showHeader' => true,
                'showPageSummary' => true,
                'showFooter' => true,
                'showCaption' => true,
                'filename' => Yii::t('kvgrid', 'grid-export'),
                'alertMsg' => Yii::t('kvgrid', 'The EXCEL export file will be generated for download.'),
                'options' => ['title' => Yii::t('kvgrid', 'Microsoft Excel 95+')],
                'mime' => 'application/vnd.ms-excel',
                'config' => [
                    'worksheet' => Yii::t('kvgrid', 'ExportWorksheet'),
                    'cssFile' => '',
                ],
            ],
            self::PDF => [
                'label' => Yii::t('kvgrid', 'PDF'),
                'icon' => $isFa ? 'file-pdf-o' : 'floppy-disk',
                'iconOptions' => ['class' => 'text-danger'],
                'showHeader' => true,
                'showPageSummary' => true,
                'showFooter' => true,
                'showCaption' => true,
                'filename' => Yii::t('kvgrid', 'grid-export'),
                'alertMsg' => Yii::t('kvgrid', 'The PDF export file will be generated for download.'),
                'options' => ['title' => Yii::t('kvgrid', 'Portable Document Format')],
                'mime' => 'application/pdf',
                'config' => [
                    'mode' => 'UTF-8',
                    'format' => 'A4-L',
                    'destination' => 'D',
                    'marginTop' => 20,
                    'marginBottom' => 20,
                    'cssInline' => '.kv-wrap{padding:20px;}' .
                        '.kv-align-center{text-align:center;}' .
                        '.kv-align-left{text-align:left;}' .
                        '.kv-align-right{text-align:right;}' .
                        '.kv-align-top{vertical-align:top!important;}' .
                        '.kv-align-bottom{vertical-align:bottom!important;}' .
                        '.kv-align-middle{vertical-align:middle!important;}' .
                        '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                        '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                        '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
                    'methods' => [
                        'SetHeader' => [
                            ['odd' => $pdfHeader, 'even' => $pdfHeader],
                        ],
                        'SetFooter' => [
                            ['odd' => $pdfFooter, 'even' => $pdfFooter],
                        ],
                    ],
                    'options' => [
                        'title' => $title,
                        'subject' => Yii::t('kvgrid', 'PDF export generated by kartik-v/yii2-grid extension'),
                        'keywords' => Yii::t('kvgrid', 'krajee, grid, export, yii2-grid, pdf'),
                    ],
                    'contentBefore' => '',
                    'contentAfter' => '',
                ],
            ],
        ];*/

        // Remove PDF if dependency is not loaded.
        //if (!class_exists('\\kartik\\mpdf\\Pdf')) {
        //    unset($defaultExportConfig[self::PDF]);
        //}

        //$this->exportConfig = self::parseExportConfig($this->exportConfig, $defaultExportConfig);



        $this->setHttpHeaders($type, $name, $mime, $encoding);
        return $content;
    }

    /**
     * Generates the PDF file
     *
     * @param string $content the file content
     * @param string $filename the file name
     * @param array  $config the configuration for yii2-mpdf component
     *
     * @return void
     */
    protected function generatePDF($content, $filename, $config = [])
    {
        unset($config['contentBefore'], $config['contentAfter']);
        $config['filename'] = $filename;
        $config['methods']['SetAuthor'] = ['Generated from EULIMS Referral (Accomplishment Report) '.date('F j, Y h:i:s a')];
        $config['methods']['SetCreator'] = ['OneLab - Referral © '.date('Y')];
        // $config['methods']['SetHeader']['even'] = $pdfHeader;
        // $config['methods']['SetHeader']['odd'] = $pdfHeader;
        // $config['methods']['SetFooter']['odd'] = $pdfFooter;
        // $config['methods']['SetFooter']['even'] = $pdfFooter;
        // $config['options']['title'] = 'OneLab Accomplishment Report';
        $config['content'] = $content;
        $pdf = new Pdf($config);
        echo $pdf->render();
    }

    /**
     * Sets the HTTP headers needed by file download action.
     *
     * @param string $type the file type
     * @param string $name the file name
     * @param string $mime the mime time for the file
     * @param string $encoding the encoding for the file content
     *
     * @return void
     */
    protected function setHttpHeaders($type, $name, $mime, $encoding = 'utf-8')
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        if (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE') == false) {
            header('Cache-Control: no-cache');
            header('Pragma: no-cache');
        } else {
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        }
        header('Expires: Sat, 26 Jul 1979 05:00:00 GMT');
        header("Content-Encoding: {$encoding}");
        header("Content-Type: {$mime}; charset={$encoding}");
        header("Content-Disposition: attachment; filename={$name}.{$type}");
        header('Cache-Control: max-age=0');
    }
}
