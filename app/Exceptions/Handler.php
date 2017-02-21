<?php namespace App\Exceptions;

use App\Domain\DomainException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\SessionInterface;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
	}

    /**
     * @param \Illuminate\Http\Request $request
     * @param Exception $e
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
	public function render($request, Exception $e)
	{

        if($e instanceof NotFoundHttpException)
        {
            return Redirect::to('home');
        }
        if ($this->isHttpException($e))
		{
			return $this->renderHttpException($e);
		}
        if($e instanceof DomainException)
        {
            Session::flash('alert-class','alert-danger');
            Session::flash('message',$e->getMessage());

            return Redirect::back()->withInput();
        }
        else
        {
            return parent::render($request, $e);
        }
    }

}
