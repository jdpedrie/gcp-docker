# Docker Utilities for PHP development

This set of tools allows running PHP development scripts in any PHP version, with or without grpc and protobuf. It includes xDebug support.

It includes a CLI called `phpd` to execute commands within the container.

```
$ phpd run foo/bar.php
```

Set the PHP version:

```
$ phpd run foo/bar.php -p 7.1
```

This tool is specialized for the development I do with Google Cloud Platform. It could be generalized without too much additional effort.

The tool expects a symlink in the project root called `gcp`, linking to your project root directory.
