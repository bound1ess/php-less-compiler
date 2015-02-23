# LESS compiler

Compile LESS into CSS with ease.

## How it works (will work)

1. Pass (file name/directory name/code) to `LessParser`, which will build a `LessTree`.
2. Pass the `LessTree` to `LessCompiler` which will produce a `CssTree`.
3. Pass the `CssTree` to `CssFormatter` to produce well-formed CSS code.

## License

The MIT license (MIT).
