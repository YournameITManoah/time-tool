<!doctype html>
<html lang="en" data-theme-pref="{{ $config->get('ui.theme', 'system') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $config->get('ui.title', config('app.name') . ' - API Docs') }}</title>

    <script src="https://unpkg.com/@stoplight/elements@8.3.4/web-components.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/@stoplight/elements@8.3.4/styles.min.css">

    <script>
        const onThemeChange = ({
            matches
        }) => {
            const pref = document.documentElement.getAttribute('data-theme-pref')
            if (pref === 'system') {
                document.documentElement.setAttribute('data-theme', matches ? 'dark' : 'light');
            } else {
                document.documentElement.setAttribute('data-theme', pref);
            }
        };
        onThemeChange(window.matchMedia('(prefers-color-scheme: dark)'));
        window.matchMedia('(prefers-color-scheme: dark)').removeEventListener('change', onThemeChange);
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', onThemeChange);
    </script>

    <script>
        const originalFetch = window.fetch;

        // intercept TryIt requests and add the XSRF-TOKEN header,
        // which is necessary for Sanctum cookie-based authentication to work correctly
        window.fetch = (url, options) => {
            const CSRF_TOKEN_COOKIE_KEY = "XSRF-TOKEN";
            const CSRF_TOKEN_HEADER_KEY = "X-XSRF-TOKEN";
            const getCookieValue = (key) => {
                const cookie = document.cookie.split(';').find((cookie) => cookie.trim().startsWith(key));
                return cookie?.split("=")[1];
            };

            const updateFetchHeaders = (
                headers,
                headerKey,
                headerValue,
            ) => {
                if (headers instanceof Headers) {
                    headers.set(headerKey, headerValue);
                } else if (Array.isArray(headers)) {
                    headers.push([headerKey, headerValue]);
                } else if (headers) {
                    headers[headerKey] = headerValue;
                }
            };
            const csrfToken = getCookieValue(CSRF_TOKEN_COOKIE_KEY);
            if (csrfToken) {
                const {
                    headers = new Headers()
                } = options || {};
                updateFetchHeaders(headers, CSRF_TOKEN_HEADER_KEY, decodeURIComponent(csrfToken));
                return originalFetch(url, {
                    ...options,
                    headers,
                });
            }

            return originalFetch(url, options);
        };
    </script>

    <style>
        html[data-theme="dark"] span.token.punctuation,
        html[data-theme="dark"] div.sl-code-highlight__ln {
            color: rgb(227, 227, 227) !important;
        }

        html[data-theme="dark"] span.token.property {
            color: rgb(128, 203, 196) !important;
        }

        html[data-theme="dark"] span.token.operator {
            color: rgb(255, 123, 114) !important;
        }

        html[data-theme="dark"] span.token.string {
            color: rgb(165, 214, 255) !important;
        }

        html[data-theme="dark"] span.token.number {
            color: rgb(247, 140, 108) !important;
        }

        html[data-theme="dark"] span.token.boolean {
            color: rgb(121, 192, 255) !important;
        }
    </style>
</head>

<body style="height: 100vh; overflow-y: hidden">
    <elements-api id="docs" tryItCredentialsPolicy="{{ $config->get('ui.try_it_credentials_policy', 'include') }}"
        router="hash" @if ($config->get('ui.hide_try_it')) hideTryIt="true" @endif logo="{{ $config->get('ui.logo') }}" />
    <script>
        (async () => {
            const docs = document.getElementById('docs');
            docs.apiDescriptionDocument = @json($spec);
        })();
    </script>
</body>

</html>
