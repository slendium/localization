# Slendium/Localization

A framework-agnostic PHP library for localization. Includes:

* PHPDocs with type hints for static analyzers
* Works with PHP-native standards ([RFC 4646, CLDR](https://www.php.net/manual/en/class.locale.php))

## Installation

Requires **PHP >= 8.4**. Simply run `composer install slendium/localization` to add it to your project.

## Example

Create a domain-specific object with a translatable `$name` property.

```php
class Product {
  /** @var Localizable<non-empty-string> */
  public Localizable $name;
}
```

Now consume this object in an `EmailGenerator` template.

```php
class OrderEmailGenerator extends EmailGenerator {

  public function __construct(
    /** @var list<Product> */
    private array $products
  ) { }

  #[Override]
  public function generateBody(): iterable {
    yield $this->getSalutation($this->getCustomerName()); // combines localizable information with a customer name
    yield '<br><br>';
    yield $this->translate('thanks_for_ordering'); // a Localizable from a static dictionary
    yield from [ '<table><tr><th>', $this->translate('product_category'), '</th></tr>' ];
    foreach ($this->products as $product) {
      yield from [ '<tr><td>', $product->name, '</td></tr>' ]; // $product->name is a localizable
    }
    yield '</table>';
    yield $this->getSignature(); // another localizable
  }
}
```

The email generator can simply return a mix of markup and localizables to be converted to their
translated equivalents at a later stage.

## Motivation

There are different ways of doing localization in PHP (array dictionaries, [gettext](https://www.php.net/manual/en/book.gettext.php),
framework-specific implementations, etc.). Sometimes end-users can enter their own translations on a
per-object basis - for example, using a database table. Code that is responsible for producing a
locale-specific output (such as an HTML page, a JSON object, a PDF, an e-mail, etc.), or other libraries
that want to work with localizable objects, should not need to know about the underlying dictionary
implementation of such objects.

Pluralization is not part of this library, for now.
