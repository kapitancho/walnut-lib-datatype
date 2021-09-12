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