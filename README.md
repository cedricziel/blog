# Blog System

Private blog system. Heavily based on AppEngine built-in services.

## Development

Use the appengine sdk to start the development environment.
Substitute `$application_id` with your appengine project id
or a valid example string. Make sure you set the `php_executable_path`
argument to the correct `php-cgi` binary.

```bash
python2 ~/google-cloud-sdk/bin/dev_appserver.py \
    --application=$application_id \
    --php_executable_path=/usr/bin/php-cgi \
    app.yaml
```

## Deployment

The application is deployed automatically by travis-ci.

## License

MIT
