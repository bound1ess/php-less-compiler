# LESS compiler

Compile LESS into CSS with ease.

## Roadmap

1. Implement `LessCompiler\Css\PrettyPrinter` with the support of various pretty printers.
2. Implement `LessCompiler\Parser` that will parse LESS code into an AST.
3. Implement `LessCompiler\Compiler` that will tie all the pieces together.

### CSS pretty printers

- standard (*Google CSS style guide*)

### LESS features support

- variables [ ]
- mixins [ ]
- nested rules [ ]
- media queries [ ]
- operations [ ]
- functions [ ]
- namespaces and accessors [ ]
- scope [ ]
- comments [ ]
- importing [ ]

## How it works (raw)

1. Pass (file name/directory name/code) to `Parser`, which will build a `Less\LessTree`.
2. Pass the `Less\LessTree` to `Compiler` which will produce a `Css\CssTree`.
3. Pass the `Css\CssTree` to `Css\PrettyPrinter` to produce well-formed CSS code.

## License

The MIT license (MIT).
