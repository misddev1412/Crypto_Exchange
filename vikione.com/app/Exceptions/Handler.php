<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\APIException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use \Softnio\LaravelInstaller\Helpers\MigrationsHelper;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // HTTP API Errors 
        if( $exception instanceof APIException ) {
            $json = [
                'success' => false,
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];

            return response()->json($json, $exception->getCode(), [], JSON_PRETTY_PRINT);
        }

        $migrations = $this->getMigrations();
        $dbMigrations = $this->getExecutedMigrations();
        $need_update = count($migrations) - count($dbMigrations);
        
        if($request->ajax() || $request->wantsJson()){
            $response = [
                'msg' => 'error', 
                'message' => 'Something is wrong!',
                'errors' => $exception->getMessage()
            ];
            return response()->json($response, 200, [], JSON_PRETTY_PRINT);
        }
        if($exception instanceof QueryException)
        {
            if($need_update > 0){
                return response()->view('errors.db_error', compact('check_dt', 'need_update'));
            }

            $check_dt = \IcoHandler::checkDB();
            if(empty($check_dt)){
                $heading = 'Something is wrong in Database!';
                $message = 'Please re-check your database connection, tables and columns etc.';
                return response()->view('errors.custom', ['heading'=>$heading, 'message'=>$message, 'need_update' => $need_update]);
            }
            else{
                $migrations = $this->getMigrations();
                $dbMigrations = $this->getExecutedMigrations();
                $need_update = count($migrations) - count($dbMigrations);
                return response()->view('errors.db_error', compact('check_dt', 'need_update'));
            }
            
        }
        if($exception instanceof \PDOException)
        {
            if($need_update > 0){
                return response()->view('errors.db_error', compact('check_dt', 'need_update'));
            }
            $heading = 'Unable to Connect Database!';
            $message = 'Please re-check your database name, username and password.';
            return response()->view('errors.custom', ['heading'=>$heading, 'message'=>$message]);
        }
        return parent::render($request, $exception);
    }
}
