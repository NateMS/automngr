<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <style>
            *, *::before, *::after { box-sizing: border-box; }
            body {
                margin: 0;
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
                background: #fff;
                color: #000;
                -webkit-font-smoothing: antialiased;
            }
            .page {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 2rem;
                min-height: 100vh;
                padding: 2rem 1rem;
            }
            .heading { display: flex; align-items: center; font-size: 1.125rem; }
            .code { padding: 0 1rem; border-right: 1px solid #cbd5e0; }
            .message { margin-left: 1rem; }
            .button {
                display: inline-block;
                padding: .5rem 1rem;
                border-radius: .375rem;
                background: #1f2937;
                color: #fff;
                font-size: .75rem;
                font-weight: 600;
                letter-spacing: .1em;
                text-transform: uppercase;
                text-decoration: none;
                transition: background-color .15s;
            }
            .button:hover, .button:focus { background: #374151; }
            .button:active { background: #111827; }
        </style>
    </head>
    <body>
        <div class="page" role="main">
            <div class="heading">
                <h1 class="code" style="margin: 0; font-size: inherit; font-weight: inherit;">@yield('code')</h1>
                <div class="message">@yield('message')</div>
            </div>

            <a href="{{ url('/') }}" class="button">{{ __('Back to the application') }}</a>
        </div>

        <script>
            // Inertia shows error responses in a sandboxed iframe that cannot navigate the
            // top window, so ask the application to close the dialog instead.
            if (window.parent !== window) {
                document.querySelector('.button').addEventListener('click', function (event) {
                    event.preventDefault();
                    window.parent.postMessage('inertia-error-dialog:close', '*');
                });
            }
        </script>
    </body>
</html>
