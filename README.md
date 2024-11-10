# Buildify (Feeling OOP Vibes in PHP!)

> Buildify improves PHP OOP with enhanced getter and setter functionality inspired by Builder and Fluent Patterns.

Buildify is a lightweight PHP package designed to facilitate the dynamic creation and management of objects
through a fluent interface.
But the main part is that you have full control over the getter and setter processes,
You can define a getter and setter for each field that will be executed when retrieving and modifying the value.
You can also take advantage of the refresh method, which is triggered whenever any field in the object is updated.
Additionally, there is the ability to modify various settings as well.

<hr>

## 🫡 Usage

### 🚀 Installation

You can install the package via composer:

```bash
composer require nabeghe/buildify
```

<hr>

### Example - Person Class

```php
/**
 * Person Class.
 *
 * Properties:
 *
 * @property string|null $firstName
 * @property string|null $lastName
 * @property string|null $gender
 * @property string|null $maritalStatus
 * @property bool $isMarried
 * @property bool $isSingle
 * @property int|null $penisSize
 * @property int|null $vaginalSize
 *
 * Getters/Setters:
 *
 * @method self|string|null firstName(string|null $value = false)
 * @method self|string|null lastName(string|null $value = false)
 * @method self|string|null gender(string|null $value = false)
 * @method self|string|null maritalStatus(string|null $value = false)
 * @method self|int|null penisSize(int|null $value = false)
 * @method self|bool isMarried()
 * @method self|bool isSingle()
 * @method self|int|null vaginalSize(int|null $value = false)
 */
class Person extends \Nabeghe\Buildify\Buildify
{
    // You can use `BuildifyTrait` instead of extending the Buildify class.
    // You can also override class constants to enable or disable various features.

    public const REFRESHABLE = true;

    public function defaults(): array
    {
        return [
            'gender' => 'unknown',
            'maritalStatus' => 'single',
        ];
    }

    public function refresh($name, $newValue, $oldValue): void
    {
        parent::refresh($name, $newValue, $oldValue);

        if ($newValue == $oldValue) {
            return;
        }

        if ($name == 'gender') {
            if ($this->gender === 'male') {
                unset($this->vaginalSize);
                echo "Vaginal Size Removed!\n";
            } elseif ($this->gender === 'female') {
                unset($this->penisSize);
                echo "Penis Size Removed!\n";
            }
        }
    }

    protected function setPenisSize(&$value): bool
    {
        if ($this->gender == 'male') {
            return true;
        }

        echo "The penis size is not applicable to women.\n";
        return false;
    }

    protected function setVaginalSize(&$value): bool
    {
        if ($this->gender == 'female') {
            return true;
        }

        echo "The vaginal size is not applicable to men.\n";
        return false;
    }

    protected function setFirstNameLength(&$value): bool
    {
        return strlen($this->firstName);
    }

    protected function getIsMarried(): bool
    {
        return $this->maritalStatus === 'married';
    }

    protected function setIsMarried(&$value): bool
    {
        return false;
    }

    protected function getIsSingle(): bool
    {
        return $this->maritalStatus === 'single';
    }

    protected function setISingle(&$value): bool
    {
        return false;
    }
}

$person = Person::new()
    ->firstName('Hadi')
    ->lastName('Akbarzadeh');

echo "First Name = ".$person->firstName."\n";
echo "Last Name  = ".$person->lastName()."\n";
echo "Gender     = ".$person->gender()."\n";
echo "-----\n";

$person->gender = 'male';
echo "New Gender = ".$person->gender."\n";
echo "-----\n";

$person->penisSize = 1; // lol
echo "Penis Size = ".$person->penisSize."\n";
echo "-----\n";

echo "Setting the vaginal size to 2 ...\n";
$person->vaginalSize = 2; // It won't be set.
echo "-----\n";

$person->gender = 'female';
$person->vaginalSize = 3;
$person->firstName = 'Hermione';
echo "First Name = ".$person->firstName."\n";
echo "Last Name = ".$person->lastName."\n";
echo "New Gender = ".$person->gender."\n";
echo "Vaginal Size = ".$person->vaginalSize."\n";
echo "-----\n";

echo "Marital Status = ".$person->maritalStatus()."\n";
echo $person->isMarried ? "Married\n" : "Not Married\n";
echo $person->isSingle ? "Single\n" : "Not Single\n";
$person->maritalStatus('married');
echo "New Marital Status = ".$person->maritalStatus()."\n";
echo $person->isMarried ? "Married\n" : "Not Married\n";
echo $person->isSingle ? "Single\n" : "Not Single\n";

/*
    First Name = Hadi
    Last Name  = Akbarzadeh
    Gender     = unknown
    -----
    New Gender = male
    -----
    Penis Size = 1
    -----
    Setting the vaginal size to 2 ...
    The vaginal size is not applicable to men.
    -----
    First Name = Hermione
    Last Name = Akbarzadeh
    New Gender = female
    Vaginal Size = 3
    -----
    Marital Status = single
    Not Married
    Single
    New Marital Status = married
    Married
    Not Single
 */
```

<hr>

## 📖 License

Copyright (c) Hadi Akbarzadeh

Licensed under the MIT license, see [LICENSE.md](LICENSE.md) for details.