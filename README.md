Simple template engine
========================
Build a simple template engine in a language of your choice (extra credit for PHP),
that takes [template.tmpl](template.tmpl) (no touching) and the following variables (format them for your chosen language) as input.

Variables:
```
Name  = "Your name goes here"
Stuff = [
  [
    Thing = "roses",
    Desc  = "red"
  ],
  [
    Thing = "violets",
    Desc  = "blue"
  ],
  [
    Thing = "you",
    Desc  = "able to solve this"
  ],
  [
    Thing = "we",
    Desc  = "interested in you"
  ]
]
```

More extra credit:

Use (and handle) [extra.tmpl](extra.tmpl) instead of [template.tmpl](template.tmpl)

## Execution of the code
Please remember to run 
```
composer install
```

Then, if you want to see the engine in action, please run
```
php engine.php extra.tmpl
```

...or, if you want to execute tests, please run 
```
 ./bin/phpunit
 ```
