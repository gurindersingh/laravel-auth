<?php

namespace Gurinder\LaravelAuth\Exceptions;


use Exception;

class UnverifiedEmailException extends Exception
{

    /**
     * @var null
     */
    protected $email;

    public function __construct($email = NULL)
    {
        parent::__construct();

        $this->email = $email;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return redirect()->route( 'email.confirmation.show' )
            ->with( [
                'email'   => $this->email ?: NULL,
                'message' => 'Please verify your email before proceeding.'
            ] );
    }

}