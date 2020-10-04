<?php
//----------------------------
//developer   : Sh.Jafarkhani
//date        : 2020/09/27
//----------------------------

namespace App\Core;

use App\Core\StatusCodes;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ResponseHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return mixed
     */
    function __invoke(Request $request, Response $response, callable $next)
    {
        if ($request->isGet() && $request->getContentType() !== "application/json"){
            return $response->withStatus(StatusCodes::HTTP_UNSUPPORTED_MEDIA_TYPE                
            )->write(StatusCodes::getMessageForCode(StatusCodes::HTTP_UNSUPPORTED_MEDIA_TYPE));            
        }
        
    
        /** @var \Slim\Http\Response $response */
        $response = $next($request, $response);
        
        return $response;
    }
}