<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Storage;
ini_set('max_execution_time', 300);
class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);
        $files = $disk->files('Laravel');
        $backups = [];
        // make an array of backup files, with their filesize and creation date
        foreach ($files as $k => $f) {
            // only take the zip files into account
            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $backups[] = [
                    'file_path' => $f,
                    'file_name' => str_replace(config('laravel-backup.backup.name') . '/', '', $f),
                    'file_size' => $disk->size($f),
                    'last_modified' => date("Y-m-d", $disk->lastModified($f)),
                ];
            }
        }
        // reverse the backups, so the newest one would be on top
        $backups = array_reverse($backups);

        return view("backup.backups")->with(compact('backups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            // start the backup process
            \Illuminate\Support\Facades\Artisan::call('backup:run');
            $output = \Illuminate\Support\Facades\Artisan::output();
            // log the results
            Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n" . $output);
            // return the results as a response to the ajax call
//            Alert::success('New backup created');
            return redirect()->back()->with('message', 'Backup Created Successfully!')->with('alertType', 'alert-success');
        } catch (Exception $e) {
//            Flash::error($e->getMessage());
            return redirect()->back()->with('message', 'Backup Failed, Try again')->with('alertType', 'alert-warning');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($file_name)
    {
        $file_name = str_replace("Laravel","",$file_name);
        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);
        if ($disk->exists('Laravel') . '/' . $file_name) {
            $disk->delete('Laravel/'.$file_name);
            return redirect()->back()->with('message','Deleted Successfully! ')->with('alertType', 'alert-danger');
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }

    public function downloadBackup(Request $request)
    {
        $file_name = $request->backup_name;
        $file_name = str_replace("Laravel","",$file_name);
        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);
        if ($disk->exists('Laravel') . '/' . $file_name) {
//            return Response::download($disk->url($file_name), 'backup.zip', array('Content-Type: application/octet-stream','Content-Length: '. filesize($disk->url($file_name))));
            return response()->download(storage_path('app/Laravel'. '/'.  $file_name));
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }

}
