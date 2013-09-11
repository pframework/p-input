P_InputSet
==========

P_InputSet is a small component that handles sets of input.  For each particular
piece of input an array defined to handle how the input is mapped,
filtered/validated, if it is required to be in the set, and what a particular
error message might be if it is not valid input.

A full array might look like this:
```php
$config = [
    'first-name' => [
        'name' => 'first_name', // mapped name
        'required' => true,
        'process' => function ($value, $source) {
            if (strlen($value) < 2) { // validation
                return false;
            }
            return $value; // could potentially filter before returning
        },
        'error' => 'First name must be more than 2 characters'
    ]
];
$is = new P\InputSet($config);
$result = $is->process($_POST); // use post in this case
$result->isValid();
echo $result['first_name'];

```

Examples
========

This is a 2 part example, a controller and the corresponding form in html.  Note
that the InputSet component does not attempt to do any kind of escaping of
output, that would be the job of any particular view layer you employ the use
of.

```php
class FormController {
    public function handle() {
        if ($_POST) {
            $inputResult = $this->getUserInput($_POST);
            if ($inputResult->isValid()) {
                var_dump($inputResult);
                exit;
            }
        }
        include 'form.phtml';
    }
    
    protected function getUserInput($source) {
        $p = new P\InputSet([
            'username' => [
                'required' => true,
                'process' => function ($value, $source) {
                    if (!preg_match('#^[a-zA-Z0-9_]*$#', $value) || strlen($value) <= 5) {
                        return false;
                    }
                    return $value;
                },
                'error' => 'Username must be at least 5 characters and be only numbers and letters'
            ],
            'password' => [
                'required' => true,
                'error' => 'Password is required'
            ]
        ]);
        return $p->process($source);
    }
}

(new FormController)->handle();
```

And the form:
```html
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" accept-charset="UTF-8">
    <input class="span3" placeholder="Username" type="text" name="username" value="<?= (isset($username)) ? $username : '' ?>" /><br />
    <?= (isset($inputResult['errors']['username'])) ? 'Error: ' . $inputResult['errors']['username'] :''; ?><br />
    <input class="span3" placeholder="Password" type="password" name="password" /><br />
    <?= (isset($inputResult->errors->password)) ? 'Error: ' . $inputResult->errors->password :''; ?><br />
    <button class="btn-info btn" type="submit">Login</button>
</form>
```