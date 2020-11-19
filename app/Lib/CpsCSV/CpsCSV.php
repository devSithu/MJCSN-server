<?php
/**
 * Created by PhpStorm.
 * User: yujiro.takezawa
 * Date: 2016/01/20
 * Time: 19:37
 */

namespace App\Lib\CpsCSV;

class CpsCSV
{
    /**
     * @param $array
     *
     * @return string
     */
    public function toLineFromArray(array $array = [], $type = null, array $textAreaIndex = [])
    {
        $line = "";
        foreach ($array as $key => $text) {
            if (is_numeric($text) || strtotime($text)) {
                $line .= '="' . $text . '",';
            } else if ($type == 'header') {
                /**
                 * for header only.
                 */
                $line .= '"' . str_replace('"', '""', $text) . '",';
            } else if (in_array($key, $textAreaIndex)) {
                $line .= '"' . str_replace('"', '""', preg_replace('/\n/', ' ', preg_replace('/\r\n/', ' ', $text))) . '",';
            } else {
                $line .= ((strpos($text, ",") !== false) ? '' : '=') . '"' . str_replace('"', '""', preg_replace('/\n/', ' ', preg_replace('/\r\n/', ' ', $text))) . '",';
            }
        }
        $line = rtrim($line, ",") . "\n";

        return $line;
    }

    public function toCell($text, $isTextArea)
    {
        if ($isTextArea == true) {
            return '"' . str_replace('"', '""', preg_replace('/\n/', ' ', preg_replace('/\r\n/', ' ', $text))) . '"';
        } else {
            return ((strpos($text, ",") !== false) ? '' : '=') . '"' . str_replace('"', '""', preg_replace('/\n/', ' ', preg_replace('/\r\n/', ' ', $text))) . '"';
        }
    }

    /**
     * @param $text
     * @param $filename
     *
     * @return \Illuminate\Http\Response
     */
    public function download($text, $filename)
    {
        $headers = array('Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"');

        return \Response::make($text, 200, $headers);
    }

    /**
     * // アップロードしたCSVファイルの内容を配列に格納して返す
     * @param $uploaded_file
     * @return array
     */
    public function getContents($uploaded_file)
    {
        $file = $uploaded_file;
        setlocale(LC_ALL, 'ja_JP.UTF-8');
        $fp = tmpfile();
        $tmp_name = $file->getPathName();
        fwrite($fp, mb_convert_encoding(file_get_contents($tmp_name), 'UTF-8', 'SJIS-win'));
        rewind($fp);

        $csv_contents = [];
        while ($line = fgetcsv($fp)) {
            $csv_contents[] = $line;
        }

        return $csv_contents;
    }

    /**
     * read content of csv file into array
     * @param $path path to the csv file
     * @return array
     */
    public function getContentsFromFile($path)
    {
        setlocale(LC_ALL, 'ja_JP.UTF-8');
        $fp = tmpfile();
        fwrite($fp, mb_convert_encoding(file_get_contents($path), 'UTF-8', 'sjis-win'));
        rewind($fp);

        $csv_contents = [];
        while ($line = fgetcsv($fp)) {
            $csv_contents[] = $line;
        }

        return $csv_contents;
    }

    /**
     * normalized and encode the header and body data.
     * @param $text array
     * @param $type default null
     * @return $text array
     */
    public function normalizeData($text, $type = null)
    {
        foreach ($text as $key => $value) {
            if ($type == 'header') {
                $text[$key] = mb_convert_encoding(str_replace('"', '""', $value), 'SJIS-win', 'UTF-8');
            } else {
                $text[$key] = mb_convert_encoding('="' . str_replace('"', '""', preg_replace('/\n/', ' ', preg_replace('/\r\n/', ' ', $value))) . '"', 'SJIS-win', 'UTF-8');
            }
        }

        return $text;
    }

    /**
     * export/download the csv file.
     * @param $csv_header array
     * @param $csv_body array
     * @param $file_name string
     * @return void
     */
    public function export($csv_header, $csv_body, $file_name)
    {
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $file_name . '"',
        );

        $call_back = function () use ($csv_header, $csv_body) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csv_header);

            foreach ($csv_body as $key => $value) {
                fputcsv($file, $value);
            }

            fclose($file);
        };

        return response()->stream($call_back, 200, $headers);
    }
}
