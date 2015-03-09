# LESS compiler

Compile LESS into CSS with ease.

## Components

- php-less-compiler/selector-parser
- php-less-compiler/css-printer
- php-less-compiler/scope-manager
- ???

## LESS features support

I write this project, first of all, *for myself*.
That means that I won't implement features that I don't use (and there is a bunch of them).
If you're interested in my work and want to contribute - you're welcome!

### What is NOT supported

- extending
- mixin and CSS guards
- loops
- merging
- some very small features related to mixins etc

### What is supported

- variables (scopes, interpolating)
- mixins (selectors, namespaces, parametric)
- nested rules
- importing [x]
- parent selector (&)
- comments [x]

#### Variables

LESS:

```less
@foo: bar;

#box > .item {
    @baz: fizz;

    input[type="text"] {
        @foo: 123;

        color: @foo;
        font-size: @baz;
    }
}
```

CSS:

```css
#box > .item input[type="text"] {
    color: 123;
    font-size: fizz;
}
```

LESS:

```less
@someSelector: box;

#@{someSelector} {
    color-@{someSelector}: black;
}
```

CSS:

```css
#box {
    color-box: black;
}
```

## License

The MIT license (MIT).
