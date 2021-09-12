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