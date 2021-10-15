<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

/*
 * Middleware используемая для проверки технической корректности приходящего ответа на вопрос
 *
 * @author Oleg Pyatin
 */
class AdminEntrance
{
    public function handle($request, Closure $next)
    {
        if (Gate::denies('admin-entrance')) {
            return redirect(route('login'));
        }

        return $next($request);
    }
}