<?php

namespace OalidCse\DBExport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class DBExportController extends Controller
{
    public function export_database($tables=null)
    {
        $valid_ips = env("DB_EXPORT_VALID_IPS");
        if (!empty($valid_ips)) {
            $valid_ips = explode(",", $valid_ips);
            if (in_array(\request()->ip(), $valid_ips)) {

            } else {
                return false;
            }
        }

        $content = $this->Export_Database_Content($tables);

        $file = fopen(__DIR__."/db_data/database.sql","w");
        fwrite($file,$content);
        fclose($file);
        $db_file = __DIR__."/db_data/database.sql";
        return $db_file;

        /*$headers = ['Content-type'=>'text/plain',
            'test'=>'YoYo',
            'Content-Disposition'=>sprintf('attachment; filename="%s"', $backup_name),
            'X-BooYAH'=>'WorkyWorky',
            'Content-Length'=>''
        ];
        return Response::make($content, 200, $headers);*/
    }

    private function Export_Database_Content($tables=null)
    {
        $name = env('DB_DATABASE');
        ini_set ( 'max_execution_time', 1200);
        DB::select(DB::raw("SET NAMES 'utf8'"));
        $queryTables    = DB::select(DB::raw("SHOW TABLES"));
        foreach ($queryTables as $row) {
            $row = json_decode(json_encode($row), true);
            $target_tables[] = $row['Tables_in_'.$name];
        }

        if($tables !== null)
        {
            $target_tables = array_intersect( $target_tables, $tables);
        }
        $content = "";
        foreach($target_tables as $table)
        {
            $result         =   DB::select(DB::raw('SELECT * FROM '.$table));
            $fields_amount  =   count(Schema::getColumnListing($table));
            $rows_num		=	count($result);
            $res            =   DB::select(DB::raw('SHOW CREATE TABLE '.$table));
            $TableMLine     =   $res[0];
            $TableMLine = json_decode(json_encode($TableMLine), true);

            $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine["Create Table"].";\n\n";
            for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0)
            {
                foreach($result as $row)
                {
                    $row = json_decode(json_encode($row), true);
                    $row = array_values($row);

                    if ($st_counter%100 == 0 || $st_counter == 0 )
                    {
                        $content .= "\nINSERT INTO ".$table." VALUES";
                    }
                    $content .= "\n(";
                    for($j=0; $j<$fields_amount; $j++)
                    {
                        $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) );
                        if (isset($row[$j]))
                        {
                            $content .= '"'.$row[$j].'"' ;
                        }
                        else
                        {
                            $content .= '""';
                        }
                        if ($j<($fields_amount-1))
                        {
                            $content.= ',';
                        }
                    }
                    $content .=")";

                    if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num)
                    {
                        $content .= ";";
                    }
                    else
                    {
                        $content .= ",";
                    }
                    $st_counter=$st_counter+1;
                }

            } $content .="\n\n\n";
        }
        return $content;
    }

}
