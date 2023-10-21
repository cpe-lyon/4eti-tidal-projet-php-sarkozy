# How to use templates

## Basic usage

By default, views are stored in the `/views` directory at the root of the project. You can create a new view by adding an HTML file in this directory.\
You can use your template as an HTML file, and add variables and other instructions such as conditions. Supported instructions are stored in `le-temp-des-templates/src/TemplateInstructionEnum.php`.

## Variables

When creating a template, you need to declare your variables with the `Template::assign()` method which take the name of your variable and the its value as arguments. Alternatively you can use the `Template::array_assign()` 
method to declare multiples variables at once with an array. The array format must be as follows:

```php
$vars = array(
  'var' => 'value',
  'var2' => 'value2'
);
```

In your template file, insert your variables surrounding them with double braces as follows:

```html
<h1>{{ my_title }}</h1>

<p>{{ my_content }}</p>
```

## Conditions

You can add conditions in your templates with `if`, `else`, and `end` instructions. Supported conditions are `<`, `<=`, `==`, `>=`, `>`.\

> :warning: The `===` condition is not supported.\

Insert a condition surrounding it with braces in your template file as follows:

```html
{{ if { my_var == 3 } }}
<p>Condition passed!</p>
{{ end }}
```

Or with an `else` statement:

```html
{{ if { my_var == 3 } }}
<p>Condition passed!</p>
{{ else }}
<p>Condition not passed :(</p>
{{ end }}
```
