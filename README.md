# OpenApi v.3 compatible data types
Attribute based type annotation and validation

## Examples

### Book
```php
final class BookData {
    public function __construct(
        #[IntegerData(minimum: 1, maximum: 999999)]
        public /*readonly*/ int $numPages,
        
        #[StringData(minLength: 1, maxLength: 100)]
        public /*readonly*/ string $authorName,
        
        #[IntegerData(minimum: 1, maximum: 9999)]
        public /*readonly*/ int $issueYear,
        
        #[StringData(minLength: 1, maxLength: 100)]
        public /*readonly*/ string $publisherName,
        
        #[StringData(minLength: 1, maxLength: 30)]
        public /*readonly*/ string $language
    ) {}
}
```

```php
class TestApi {

    public function __construct(

        #[IntegerData(minimum: 18)]
        public int $age,

        #[BooleanData]
        public bool $isConfirmed,

        #[NumberData(maximum: 1000, exclusiveMaximum: true)]
        public float $radius,

        #[StringData(minLength: 5)]
        public string $name,

        #[ArrayData(minItems: 2)]
        public array $players,

        #[ObjectData(required: ['city', 'zipCode'])]
        public object $info

    ) {}
}
```

```php
#[ObjectData(required: ['name', 'country'])]
class Zoo {
    public function __construct(
        #[StringData(minLength: 2, maxLength: 20)]
        public string $name,
        #[StringData(minLength: 2, maxLength: 20)]
        public string $country
    ) {}
}

#[ObjectData(minProperties: 2, additionalPropertiesIn: 'tigerInfo')]
class Tiger {
    public function __construct(
    
        #[StringData(minLength: 2, maxLength: 20)]
        public string $name,
        
        #[IntegerData(minimum: 0, maximum: 99)]
        public int $age,

        #[ArrayData(minItems: 1), StringData]
        public array $favoriteFood,

        #[RefValue(Zoo::class)]
        public Zoo $zoo,

        #[StringData(minLength: 2, maxLength: 20)]
        public array $tigerInfo, //for all other properties

        #[ArrayData(nullable: true), ArrayData, IntegerData(minimum: 3)]
        public ?array $int2, //int[][]

        #[ArrayData(nullable: true), RefValue(Zoo::class)]
        public ?array $otherZoos //Zoo[]    

    ) {}
}
```

```php
#[ObjectTypedValue]
class Order {
    public function __construct(
        #[IntegerTypedValue(format: 'int64')]
        public int $id,
        #[IntegerTypedValue(format: 'int64')]
        public int $petId,
        #[IntegerTypedValue(format: 'int32')]
        public int $quantity,
        #[StringTypedValue(format: 'date-time')]
        public string $shipDate,
        #[StringTypedValue(enum: ['placed', 'approved', 'delivered'])]
        public string $status,
        #[BooleanTypedValue]
        public bool $complete,
    ) {}
}
```

```php
final class Address {
    public function __construct(
        #[IntegerTypedValue(minimum: 1, maximum: 999999)]
        public int $id,
        #[StringTypedValue(minLength: 1, maxLength: 50)]
        public string $city,
        #[StringTypedValue(minLength: 1, maxLength: 100)]
        public string $street
    ) {}
}
final class Client {
    /**
     * Client constructor.
     * @param int $id
     * @param string $name
     * @param Address $address
     * @param Account|null $account
     * @param Contact[] $contacts
     */
    public function __construct(
        #[IntegerTypedValue(minimum: 1, maximum: 999999)]
        public int $id,
        #[StringTypedValue(minLength: 1, maxLength: 100)]
        public string $name,
        public Address $address,
        public ?Account $account,
        #[ArrayTypedValue, RefValue(targetClass: Contact::class)]
        public array $contacts
    ) {}
}
final class Account {
    public function __construct(
        #[IntegerTypedValue(minimum: 1, maximum: 999999)]
        public int $id,
        #[StringTypedValue(minLength: 1, maxLength: 100)]
        public string $account_name
    ) {}
}
final class Contact {
    /**
     * Account constructor.
     * @param int $id
     * @param string $contact_name
     * @param string $position
     * @param Phone[] $phones
     */
    public function __construct(
        #[IntegerTypedValue(minimum: 1, maximum: 999999)]
        public int $id,
        #[StringTypedValue(minLength: 1, maxLength: 100)]
        public string $contact_name,
        #[StringTypedValue(minLength: 1, maxLength: 50)]
        public string $position,
        #[ArrayTypedValue, RefValue(targetClass: Phone::class)]
        public array $phones
    ) {}
}
final class Phone {
    public function __construct(
        #[IntegerTypedValue(minimum: 1, maximum: 999999)]
        public int $id,
        #[StringTypedValue(minLength: 1, maxLength: 50)]
        public string $phone_number
    ) {}
}
```