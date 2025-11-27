<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

/**
 * Trait RedirectWithMessage
 *
 * Provides methods to redirect with flash messages for success or error scenarios in Laravel controllers.
 */
trait RedirectWithMessage
{
    /**
     * Redirects to a route or URL with a success flash message.
     *
     * @param string $route The route name or URL to redirect to.
     * @param string|array $message The message content or an array of message data.
     * @param mixed ...$routeParams Optional parameters for the route.
     * @return RedirectResponse
     */
    protected function redirectWithSuccess(string $route, $message, ...$routeParams): RedirectResponse
    {
        return $this->redirectWithMessage($route, $message, 'success', ...$routeParams);
    }

    /**
     * Redirects to a route or URL with an error flash message.
     *
     * @param string $route The route name or URL to redirect to.
     * @param string|array $message The message content or an array of message data.
     * @param mixed ...$routeParams Optional parameters for the route.
     * @return RedirectResponse
     */
    protected function redirectWithError(string $route, $message, ...$routeParams): RedirectResponse
    {
        return $this->redirectWithMessage($route, $message, 'error', ...$routeParams);
    }

    /**
     * Redirects to a route or URL with a custom flash message.
     *
     * Sanitizes string messages to prevent XSS and supports both route names and URLs.
     *
     * @param string $route The route name or URL to redirect to.
     * @param string|array $message The message content or an array of message data.
     * @param string $key The session flash key (e.g., 'success', 'error').
     * @param mixed ...$routeParams Optional parameters for the route.
     * @return RedirectResponse
     * @throws \InvalidArgumentException If the route or key is empty, or if the message is invalid.
     */
    protected function redirectWithMessage(string $route, $message, string $key = 'success', ...$routeParams): RedirectResponse
    {
        if (empty($route)) {
            throw new \InvalidArgumentException('Route cannot be empty.');
        }

        if (empty($key)) {
            throw new \InvalidArgumentException('Flash message key cannot be empty.');
        }

        if ((is_string($message) && trim($message) === '') || (is_array($message) && empty($message))) {
            throw new \InvalidArgumentException('Message cannot be empty.');
        }

        $sanitizedMessage = is_string($message)
            ? Str::limit(strip_tags($message), 250, '')
            : $message;

        if (filter_var($route, FILTER_VALIDATE_URL)) {
            return redirect()->to($route)->with($key, $sanitizedMessage);
        }

        return redirect()->route($route, $routeParams)->with($key, $sanitizedMessage);
    }

}